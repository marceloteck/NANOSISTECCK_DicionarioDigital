<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        $postId = $this->route('post')?->id;

        return [
            'title' => ['sometimes', 'required', 'string', 'max:180'],
            'slug' => ['nullable', 'string', 'max:190', 'alpha_dash', Rule::unique('posts', 'slug')->ignore($postId)],
            'excerpt' => ['nullable', 'string', 'max:350'],
            'content_html' => ['sometimes', 'required', 'string'],
            'featured_image' => ['nullable', 'url', 'max:2048'],
            'featured_image_alt' => ['nullable', 'string', 'max:180'],
            'seo_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:170'],
            'meta_keywords' => ['nullable', 'string', 'max:255'],
            'canonical_url' => ['nullable', 'url', 'max:2048'],
            'schema_type' => ['nullable', 'string', 'max:60'],
            'search_intent' => ['nullable', Rule::in(['informational', 'transactional', 'navigational', 'commercial', 'tool-support', 'tutorial', 'glossary'])],
            'content_type' => ['nullable', 'string', 'max:80'],
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
            'tags.*' => ['exists:post_tags,id'],
        ];
    }
}
