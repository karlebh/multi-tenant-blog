<?php

namespace App\Traits;

use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

trait MethodTrait
{
    use ResponseTrait;

    public function upload(Request $request): array
    {
        $request->validate([
            'files.*' => [
                'required',
                'file',
                'mimes:txt,csv,png,jpg,jpeg,gif,pdf',
                'max:30720'
            ],
        ]);

        $storedFiles = [];
        $uploadId = time() . Str::random(10);

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {

                $folder =  uniqid(true);
                $fileName = Str::random(10) . '-' . $file->getClientOriginalName();

                $path = $file->storeAs("uploads/{$folder}", $fileName, 'public');

                $storedFiles[] = $path;
            }
        }

        return $storedFiles;
    }

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
