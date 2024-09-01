<?php

declare(strict_types=1);

namespace App\Actions;

use App\Contracts\NewModelAction;
use App\Models\Image;
use App\Models\Post;
use App\Models\User;

final class NewPost implements NewModelAction
{
    private Post $post;

    public function __construct(
        private User $user,
        private array $attributes,
        private Post $parent,
    ) {
        //
    }

    public function handle(): Post
    {
        $this->create();

        $this->setParent();

        $this->addImages();

        return $this->post;
    }

    private function create(): void
    {
        $this->post = $this
            ->user
            ->posts()
            ->create([
                'parent_id' => $this->parent?->id,
                'body' => $this->attributes['body'],
            ]);

        $this->post->setRelation('user', $this->user);
    }

    private function setParent(): void
    {
        if (is_null($this->parent)) {
            return;
        }

        $this->parent->load('user');
        $this->post->setRelation('parent', $this->parent);
    }

    private function addImages(): void
    {
        if (! array_key_exists('images', $this->attributes)) {
            return;
        }

        $images = Image::whereIn('id', $this->attributes['images'])->get(['path', 'size']);

        $postImages = $this->post
            ->images()
            ->createMany($images->toArray());

        $images = Image::whereIn('id', $this->attributes['images'])->delete();

        $this->post->setRelation('images', $postImages);
    }
}
