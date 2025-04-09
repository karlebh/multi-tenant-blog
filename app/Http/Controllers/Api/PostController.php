<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Traits\MethodTrait;
use App\Traits\ResponseTrait;

class PostController extends Controller
{
    use MethodTrait, ResponseTrait;

    public function store(CreatePostRequest $request, int $tenant_id)
    {
        try {
            $tenant =  $this->findTenant($tenant_id);

            $requestData = $request->validated();

            $post = Post::create([
                'user_id' => $tenant->id,
                'blog_id' => $tenant->blog->id,
                'title' => $requestData['title'],
                'content' => $requestData['content'],
            ]);

            if (! $post) {
                return $this->badRequestResponse('Could not create post');
            }

            $processedFiles = $this->processFiles($request);

            $post->update(['files' => $processedFiles]);

            return $this->successResponse(
                'Post created succesfully',
                ['post' => array_merge($post->toArray(), ['tenant_id' => $post->user_id])]
            );
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Server error', $exception);
        }
    }

    public function show(int $tenant_id, int $id)
    {
        try {
            $result = $this->findTenantAndPost($tenant_id, $id);

            return $this->successResponse(
                'Post retrieved succesfully',
                ['post' => $result['post']]
            );
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Server error', $exception);
        }
    }

    public function update(UpdatePostRequest $request, int $tenant_id, int $id)
    {
        try {
            $result = $this->findTenantAndPost($tenant_id, $id);

            $result['post']->update([$request->validated()]);

            $processedFiles = $this->processFiles($request);

            if (! empty($processedFiles)) {
                $mergedFiles = array_merge(
                    $result['post']->files ?? [],
                    $processedFiles
                );

                $result['post']->update([
                    'files' => $mergedFiles
                ]);
            }

            return  $this->successResponse('Post updated succesfully', ['post' => $result['post']->fresh()]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Server error', $exception);
        }
    }

    public function destroy(int $tenant_id, $id)
    {
        try {
            $result = $this->findTenantAndPost($tenant_id, $id);

            $result['post']->delete();

            return $this->successResponse('Post deleted succesfully');
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Server error', $exception);
        }
    }
}
