<?php

namespace App\Support\Posts;

use DOMDocument;
use DOMElement;
use DOMNode;
use Illuminate\Support\Str;

class PostContentFormatter
{
    protected array $allowedTags = [
        'div', 'p', 'br', 'strong', 'em', 'b', 'i', 'u', 'ul', 'ol', 'li', 'a',
        'h2', 'h3', 'h4', 'blockquote', 'code', 'pre', 'span', 'img', 'table',
        'thead', 'tbody', 'tr', 'th', 'td', 'hr',
    ];

    protected array $globalAllowedAttributes = [
        'id', 'class', 'title', 'aria-label', 'aria-hidden', 'role',
    ];

    protected array $tagAttributes = [
        'a' => ['href', 'target', 'rel'],
        'img' => ['src', 'alt', 'width', 'height', 'loading'],
    ];

    public function prepare(string $html): array
    {
        $content = trim($html);

        if ($content === '') {
            return ['content_html' => '', 'toc' => [], 'reading_time' => 1];
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        @$dom->loadHTML('<?xml encoding="utf-8" ?><div id="post-root">'.$content.'</div>', LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

        $root = $dom->getElementById('post-root');
        if (! $root instanceof DOMElement) {
            return ['content_html' => $content, 'toc' => [], 'reading_time' => $this->calculateReadingTime($content)];
        }

        $this->sanitizeNode($root);

        $toc = [];
        $usedIds = [];
        foreach ($root->getElementsByTagName('*') as $node) {
            if (! $node instanceof DOMElement) {
                continue;
            }

            $tagName = strtolower($node->tagName);
            if (! in_array($tagName, ['h2', 'h3'], true)) {
                continue;
            }

            $label = trim($node->textContent);
            if ($label === '') {
                continue;
            }

            $id = $node->getAttribute('id');
            if ($id === '') {
                $id = Str::slug($label);
            }
            $id = $this->resolveUniqueId($id, $usedIds);
            $node->setAttribute('id', $id);

            $toc[] = [
                'id' => $id,
                'label' => $label,
                'level' => $tagName,
            ];
        }

        $htmlOutput = '';
        foreach ($root->childNodes as $childNode) {
            $htmlOutput .= $dom->saveHTML($childNode);
        }

        return [
            'content_html' => $htmlOutput,
            'toc' => $toc,
            'reading_time' => $this->calculateReadingTime($htmlOutput),
        ];
    }

    protected function sanitizeNode(DOMNode $node): void
    {
        if ($node instanceof DOMElement) {
            $blockedTags = ['script', 'style', 'object', 'embed'];
            $tagName = strtolower($node->tagName);

            if (in_array($tagName, $blockedTags, true)) {
                $node->parentNode?->removeChild($node);

                return;
            }

            if (! in_array($tagName, $this->allowedTags, true) && $tagName !== 'div') {
                $this->unwrapNode($node);

                return;
            }

            if ($node->hasAttributes()) {
                $attrsToRemove = [];
                foreach ($node->attributes as $attribute) {
                    $name = strtolower($attribute->nodeName);
                    $value = trim((string) $attribute->nodeValue);

                    if (str_starts_with($name, 'on')) {
                        $attrsToRemove[] = $attribute->nodeName;
                    }

                    if (! $this->isAllowedAttribute($tagName, $name)) {
                        $attrsToRemove[] = $attribute->nodeName;
                    }

                    if (in_array($name, ['href', 'src'], true) && ! $this->isSafeUrl($value)) {
                        $attrsToRemove[] = $attribute->nodeName;
                    }
                }

                foreach ($attrsToRemove as $attr) {
                    $node->removeAttribute($attr);
                }

                if ($tagName === 'a' && $node->getAttribute('target') === '_blank' && ! $node->hasAttribute('rel')) {
                    $node->setAttribute('rel', 'noopener noreferrer');
                }
            }
        }

        if (! $node->hasChildNodes()) {
            return;
        }

        $children = [];
        foreach ($node->childNodes as $child) {
            $children[] = $child;
        }

        foreach ($children as $child) {
            $this->sanitizeNode($child);
        }
    }

    protected function isAllowedAttribute(string $tagName, string $attribute): bool
    {
        return in_array($attribute, $this->globalAllowedAttributes, true)
            || in_array($attribute, $this->tagAttributes[$tagName] ?? [], true);
    }

    protected function isSafeUrl(string $url): bool
    {
        if ($url === '') {
            return false;
        }

        $normalized = strtolower($url);
        if (str_starts_with($normalized, 'javascript:') || str_starts_with($normalized, 'data:')) {
            return false;
        }

        return str_starts_with($normalized, 'http://')
            || str_starts_with($normalized, 'https://')
            || str_starts_with($normalized, 'mailto:')
            || str_starts_with($normalized, 'tel:')
            || str_starts_with($normalized, '/')
            || str_starts_with($normalized, '#');
    }

    protected function unwrapNode(DOMElement $node): void
    {
        $parent = $node->parentNode;
        if (! $parent instanceof DOMNode) {
            return;
        }

        while ($node->firstChild) {
            $parent->insertBefore($node->firstChild, $node);
        }
        $parent->removeChild($node);
    }

    protected function resolveUniqueId(string $id, array &$usedIds): string
    {
        $base = Str::slug($id) ?: 'secao';
        $candidate = $base;
        $suffix = 2;

        while (in_array($candidate, $usedIds, true)) {
            $candidate = "{$base}-{$suffix}";
            $suffix++;
        }

        $usedIds[] = $candidate;

        return $candidate;
    }

    public function calculateReadingTime(string $html): int
    {
        $plainText = trim(preg_replace('/\s+/', ' ', strip_tags($html)) ?? '');
        $wordCount = str_word_count($plainText);

        return max(1, (int) ceil($wordCount / 220));
    }

    public function normalizeFaq(mixed $faq): array
    {
        if (! is_array($faq)) {
            return [];
        }

        return array_values(array_filter(array_map(static function ($item): ?array {
            if (! is_array($item)) {
                return null;
            }

            $question = trim((string) ($item['question'] ?? ''));
            $answer = trim((string) ($item['answer'] ?? ''));

            if ($question === '' || $answer === '') {
                return null;
            }

            return ['question' => $question, 'answer' => $answer];
        }, $faq)));
    }
}
