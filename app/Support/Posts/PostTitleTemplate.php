<?php

namespace App\Support\Posts;

class PostTitleTemplate
{
    public function suggest(string $keyword, string $intent = 'informational', ?int $year = null): array
    {
        $keyword = trim($keyword);
        if ($keyword === '') {
            return [];
        }

        $year ??= (int) now()->format('Y');

        return match ($intent) {
            'tutorial' => ["Como {$keyword}: passo a passo"],
            'commercial' => ["Melhor {$keyword}? Veja o que analisar"],
            'glossary' => ["{$keyword}: significado, exemplos e como usar"],
            'transactional' => ["{$keyword} em {$year}: guia completo"],
            default => [
                "{$keyword}: tudo o que você precisa saber",
                "{$keyword} em {$year}: guia completo",
            ],
        };
    }
}
