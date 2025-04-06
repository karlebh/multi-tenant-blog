<?php

namespace App\Traits;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;

trait MethodHelper
{
    use ResponseTrait;

    private function findTenant(int $tenant_id)
    {
        $tenant =  User::find($tenant_id);

        if (! $tenant) {
            return $this->badRequestResponse('This tenant does not exist');
        }

        return $tenant;
    }

    protected function findTenantAndComment(int $tenant_id, int $comment_id)
    {
        $tenant = User::find($tenant_id);

        if (! $tenant) {
            return $this->notFoundResponse('This tenant does not exist');
        }

        $comment =  Comment::find($comment_id);

        if (! $comment) {
            return $this->notFoundResponse('This comment does not exist');
        }

        return [
            'tenant' => $tenant,
            'comment'   => $comment,
        ];
    }

    private function findTenantAndPost(int $tenant_id, int $post_id)
    {
        $tenant = User::find($tenant_id);
        if (! $tenant) {
            return $this->notFoundResponse('This tenant does not exist');
        }

        $post = Post::find($post_id);

        if (! $post) {
            return $this->notFoundResponse('This post does not exist');
        }

        return [
            'tenant' => $tenant,
            'post'   => $post,
        ];
    }
}
