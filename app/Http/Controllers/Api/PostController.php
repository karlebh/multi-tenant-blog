<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use App\Traits\MethodHelper;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    use MethodHelper, ResponseTrait;

    public function index(int $tenant_id)
    {
        try {
            $tenant = $this->findTenant($tenant_id);

            $posts = Post::with(['comments', 'likes', 'user'])->where('user_id', $tenant->id)->paginate(10);

            return $this->successResponse('Post retrieved succesfully', ['posts' => $posts]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Server error', $exception);
        }
    }

    public function store(CreatePostRequest $request, int $tenant_id)
    {
        try {
            $tenant =  $this->findTenant($tenant_id);

            $requestData = $request->validated();

            $post = Post::create([
                'user_id' => $tenant->id,
                'title' => $requestData['title'],
                'content' => $requestData['content'],
                // 'files' => $requestData['files'],
            ]);

            if (! $post) {
                return $this->badRequestResponse('Could not create post');
            }

            return $this->successResponse('Post created succesfully', ['post' => $post]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Server error', $exception);
        }
    }

    public function show(int $id, int $tenant_id)
    {
        try {
            $result = $this->findTenantAndPost($tenant_id, $id);
            $this->successResponse('Post retrieved succesfully', ['post' => $result['post']]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Server error', $exception);
        }
    }

    public function update(UpdatePostRequest $request, int $tenant_id, int $id)
    {
        try {
            $result = $this->findTenantAndPost($tenant_id, $id);

            $result['post']->update([$request->validated()]);

            $this->successResponse('Post updated succesfully', ['post' => $result['post']->fresh()]);
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

    private function findTenantAndPost(int $tenant_id, int $post_id)
    {
        $tenant = User::find($tenant_id);
        if (! $tenant) {
            return response()->json(['message' => 'This tenant does not exist'], 404);
        }

        $post = Post::find($post_id);
        if (! $post) {
            return response()->json(['message' => 'This post does not exist'], 404);
        }

        return [
            'tenant' => $tenant,
            'post'   => $post,
        ];
    }
}
