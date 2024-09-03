<?php

declare(strict_types=1);

namespace App\Actions;

use App\Models\Image;
use App\Models\Post;
use App\Models\User;

final class NewPost
{
    private Post $post;

    public function handle(
        User $user,
        array $attributes,
        ?Post $parent = null,
    ): Post {
        $this->create($user, $attributes, $parent);

        $this->setParent($parent);

        $this->addImages($attributes);

        return $this->post;
    }

    private function create(User $user, array $attributes, ?Post $parent): Post
    {
        $this->post = $user
            ->posts()
            ->create([
                'parent_id' => $parent?->id,
                'body' => $attributes['body'],
            ]);

        $this->post->setRelation('user', $user);

        return $this->post;
    }

    private function setParent(?Post $parent): void
    {
        if (is_null($parent)) {
            return;
        }

        $parent->load('user');
        $this->post->setRelation('parent', $parent);
    }

    private function addImages(array $attributes): void
    {
        if (! array_key_exists('images', $attributes)) {
            return;
        }

        $images = Image::whereIn('id', $attributes['images'])->get(['path', 'size']);

        $postImages = $this->post
            ->images()
            ->createMany($images->toArray());

        $images = Image::whereIn('id', $attributes['images'])->delete();

        $this->post->setRelation('images', $postImages);
    }
}
