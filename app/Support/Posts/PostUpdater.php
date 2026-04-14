<?php

namespace App\Support\Posts;

use App\Models\Post;

class PostUpdater
{
    public function __construct(protected PostCreator $creator)
    {
    }

    public function update(Post $post, array $payload, ?int $userId = null): Post
    {
        return $this->creator->update($post, $payload, $userId);
    }
}
