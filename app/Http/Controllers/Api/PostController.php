<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
    public function index(int $tenant_id)
    {
        try {
            $tenant =  User::find($tenant_id);

            if (! $tenant) {
                return response()->json(['message' => 'This tenant does not exist']);
            }

            $posts = Post::with(['comments', 'likes', 'user'])->where('user_id', $tenant->id)->paginate(10);

            return response()->json(['posts' => $posts]);
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json(['message' => 'Server error']);
        }
    }

    public function store(CreatePostRequest $request, int $tenant_id)
    {
        try {
            $tenant =  User::find($tenant_id);

            if (! $tenant) {
                return response()->json(['message' => 'This tenant does not exist']);
            }

            $requestData = $request->validated();

            $post = Post::create([
                'user_id' => $tenant->id,
                'title' => $requestData['title'],
                'content' => $requestData['content'],
                // 'files' => $requestData['files'],
            ]);

            if (! $post) {
                return response()->json(['message' => 'Could not create post']);
            }

            return response()->json(['message' => 'Post created succesfully', 'post' => $post]);
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json(['message' => 'Server error']);
        }
    }

    public function show(int $id, int $tenant_id)
    {
        try {
            $tenant =  User::find($tenant_id);

            if (! $tenant) {
                return response()->json(['message' => 'This tenant does not exist']);
            }

            $post = Post::find($id);

            if (! $post) {
                return response()->json(['message' => 'This post does not exist']);
            }

            return response()->json(['message' => 'Post retrieved succesfully', 'post' => $post]);
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json(['message' => 'Server error']);
        }
    }

    public function update(UpdatePostRequest $request, int $tenant_id, int $id)
    {
        try {
            $tenant =  User::find($tenant_id);

            if (! $tenant) {
                return response()->json(['message' => 'This tenant does not exist']);
            }

            $post = Post::find($id);

            if (! $post) {
                return response()->json(['message' => 'This post does not exist']);
            }

            $post->title =  $request->title;
            $post->content =  $request->content;
            // $post->files =  $request->files;
            $post->save();

            return response()->json(['message' => 'Post updated succesfully', 'post' => $post->fresh()]);
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json(['message' => 'Server error']);
        }
    }

    public function destroy(int $tenant_id, $id)
    {
        try {
            $tenant =  User::find($tenant_id);

            if (! $tenant) {
                return response()->json(['message' => 'This tenant does not exist']);
            }

            $post = Post::find($id);

            if (! $post) {
                return response()->json(['message' => 'This post does not exist']);
            }

            $post->delete();

            return response()->json(['message' => 'Post deleted succesfully']);
        } catch (\Exception $exception) {
            Log::info($exception->getMessage());
            return response()->json(['message' => 'Server error']);
        }
    }

    private function tenantChecker($tenant_id)
    {
        $tenant =  User::find($tenant_id);

        if (! $tenant) {
            return response()->json(['message' => 'This tenant does not exist']);
        }

        return $tenant;
    }
}
