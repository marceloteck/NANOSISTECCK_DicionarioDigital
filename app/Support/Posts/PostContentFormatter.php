<?php

namespace App\Support\Posts;

use DOMDocument;
use DOMElement;
use DOMNode;
use Illuminate\Support\Str;

class PostContentFormatter
{
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
                $node->setAttribute('id', $id);
            }

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
            if (in_array(strtolower($node->tagName), $blockedTags, true)) {
                $node->parentNode?->removeChild($node);

                return;
            }

            if ($node->hasAttributes()) {
                $attrsToRemove = [];
                foreach ($node->attributes as $attribute) {
                    $name = strtolower($attribute->nodeName);
                    if (str_starts_with($name, 'on')) {
                        $attrsToRemove[] = $attribute->nodeName;
                    }

                    if (in_array($name, ['href', 'src'], true) && str_contains(strtolower($attribute->nodeValue), 'javascript:')) {
                        $attrsToRemove[] = $attribute->nodeName;
                    }
                }

                foreach ($attrsToRemove as $attr) {
                    $node->removeAttribute($attr);
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
