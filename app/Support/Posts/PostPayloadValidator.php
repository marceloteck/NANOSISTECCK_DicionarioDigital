<?php

namespace App\Support\Posts;

use App\Models\PostTag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PostPayloadValidator
{
    public function validate(array $payload, bool $isUpdate = false): array
    {
        $rules = [
            'title' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:180'],
            'slug' => [$isUpdate ? 'sometimes' : 'required', 'string', 'max:190', 'alpha_dash'],
            'excerpt' => ['nullable', 'string', 'max:350'],
            'hero_title' => ['nullable', 'string', 'max:180'],
            'hero_summary' => ['nullable', 'string', 'max:350'],
            'quick_answer' => ['nullable', 'string', 'max:500'],
            'content_html' => [$isUpdate ? 'sometimes' : 'required', 'string'],
            'featured_image' => ['nullable', 'url', 'max:2048'],
            'featured_image_alt' => ['nullable', 'string', 'max:180'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:170'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:2048'],
            'schema_type' => ['nullable', 'string', 'max:60'],
            'search_intent' => ['nullable', Rule::in(['informational', 'transactional', 'navigational', 'commercial', 'tool-support', 'tutorial', 'glossary'])],
            'content_type' => ['nullable', 'string', 'max:80'],
            'category_name' => ['nullable', 'string', 'max:120'],
            'category_id' => ['nullable', 'exists:post_categories,id'],
            'author_name' => ['nullable', 'string', 'max:120'],
            'is_published' => ['nullable', 'boolean'],
            'is_indexable' => ['nullable', 'boolean'],
            'published_at' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['draft', 'published'])],
            'faq_json' => ['nullable', 'array'],
            'faq_json.*.question' => ['required_with:faq_json', 'string', 'max:255'],
            'faq_json.*.answer' => ['required_with:faq_json', 'string'],
            'related_keywords' => ['nullable', 'array'],
            'related_keywords.*' => ['string', 'max:80'],
            'tags' => ['nullable', 'array'],
            'cta_title' => ['nullable', 'string', 'max:180'],
            'cta_text' => ['nullable', 'string', 'max:500'],
            'cta_button_text' => ['nullable', 'string', 'max:120'],
            'cta_button_url' => ['nullable', 'url', 'max:2048'],
        ];

        $messages = [
            'category_id.exists' => 'A categoria informada não existe.',
            'category_name.max' => 'category_name deve ter no máximo 120 caracteres.',
            'featured_image.url' => 'featured_image deve ser uma URL válida.',
            'canonical_url.url' => 'canonical_url deve ser uma URL válida.',
            'cta_button_url.url' => 'cta_button_url deve ser uma URL válida.',
            'faq_json.*.question.required_with' => 'Cada item de FAQ precisa de pergunta.',
            'faq_json.*.answer.required_with' => 'Cada item de FAQ precisa de resposta.',
        ];

        $validator = Validator::make($payload, $rules, $messages);

        $validator->after(function ($validator) use ($payload): void {
            $isPublishing = (($payload['status'] ?? null) === 'published') || (($payload['is_published'] ?? false) === true || (int) ($payload['is_published'] ?? 0) === 1);

            if ($isPublishing) {
                foreach (['title', 'slug', 'excerpt', 'content_html', 'seo_title', 'meta_description'] as $requiredField) {
                    if (blank($payload[$requiredField] ?? null)) {
                        $validator->errors()->add($requiredField, "O campo {$requiredField} é obrigatório para publicar.");
                    }
                }

                if (blank($payload['category_name'] ?? null) && blank($payload['category_id'] ?? null)) {
                    $validator->errors()->add('category_name', 'O campo category_name é obrigatório para publicar.');
                }
            }

            foreach (($payload['tags'] ?? []) as $index => $tag) {
                if (is_int($tag) || ctype_digit((string) $tag)) {
                    if (! PostTag::query()->whereKey((int) $tag)->exists()) {
                        $validator->errors()->add("tags.{$index}", 'Tag informada não existe.');
                    }

                    continue;
                }

                if (! is_string($tag) || trim($tag) === '' || mb_strlen(trim($tag)) > 80) {
                    $validator->errors()->add("tags.{$index}", 'Tag deve ser ID válido ou texto de até 80 caracteres.');
                }
            }
        });

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}
