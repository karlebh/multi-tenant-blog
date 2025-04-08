<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use App\Traits\MethodTrait;

class PostController extends Controller
{
    use MethodTrait;

    public function index(User $tenant)
    {
        $posts = Post::with(['comments', 'likes', 'user'])
            ->where('user_id', $tenant->id)
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create(User $tenant)
    {
        return view('posts.create', compact('tenant'));
    }

    public function store(CreatePostRequest $request, User $tenant)
    {
        $requestData = $request->validated();

        $post = Post::create([
            'user_id' => $tenant->id,
            'blog_id' => $tenant->blog->id,
            'title' => $requestData['title'],
            'content' => $requestData['content'],
        ]);

        if (!$post) {
            return redirect()->back()->with('error', 'Could not create post');
        }

        $processedFiles = $this->processFiles($request);

        $post->update(['files' => $processedFiles]);

        return redirect()->route('blogs.index', $tenant)
            ->with('success', 'Post created successfully');
    }

    public function show(User $tenant, Post $post)
    {
        return view('posts.show')->with(['post' => $post, 'tenant' => $tenant]);
    }

    public function edit(User $tenant, Post $post)
    {
        return view('posts.edit')->with(['post' => $post, 'tenant' => $tenant]);
    }

    public function update(UpdatePostRequest $request, User $tenant, Post $post)
    {
        $post->update($request->validated());

        $processedFiles = $this->processFiles($request);

        $mergedFiles = array_merge(
            $post->files ?? [],
            $processedFiles
        );

        $post->update([
            'files' => $mergedFiles
        ]);

        return redirect()->route('posts.show', [$tenant, $post])
            ->with('success', 'Post updated successfully');
    }

    public function destroy(User $tenant, Post $post)
    {
        $post->delete();

        return redirect()->route('posts.index', $tenant)
            ->with('success', 'Post deleted successfully');
    }
}
