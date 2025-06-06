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

    public function processFiles(Request $request)
    {
        $request->validate([
            'files.*' => [
                'required',
                'file',
                'mimes:png,jpg,jpeg,gif',
                'max:30720'
            ],
        ]);

        $storedFiles = [];

        if ($request->hasFile('files')) {
            foreach ($request->file('files') as $file) {

                $fileName = Str::random(10) . '-' . $file->getClientOriginalName();

                $path = $file->storeAs("uploads", str_replace(' ', '', $fileName), 'public');

                $storedFiles[] = $path;
            }
        }

        return $storedFiles;
    }

    public function processFile(Request $request)
    {
        $request->validate([
            'file.*' => [
                'required',
                'file',
                'mimes:png,jpg,jpeg,gif',
                'max:30720'
            ],
        ]);

        $file = $request->file;

        if ($request->hasFile('file')) {
            $fileName = Str::random(10) . '-' . $file->getClientOriginalName();
            $path = $file->storeAs("uploads", str_replace(' ', '', $fileName), 'public');

            return $path;
        }
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
