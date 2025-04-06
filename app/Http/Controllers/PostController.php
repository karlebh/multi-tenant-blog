<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use App\Models\User;
use App\Traits\MethodHelper;
use Illuminate\Http\Request;

class PostController extends Controller
{
    use MethodHelper;

    public function index(int $tenant_id)
    {
        $tenant = $this->findTenant($tenant_id);

        if (!$tenant) {
            return redirect()->back()->with('error', 'Tenant not found');
        }

        $posts = Post::with(['comments', 'likes', 'user'])
            ->where('user_id', $tenant->id)
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    public function create(int $tenant_id)
    {
        $tenant = $this->findTenant($tenant_id);

        if (!$tenant) {
            return redirect()->back()->with('error', 'Tenant not found');
        }

        return view('posts.create', compact('tenant'));
    }

    public function store(CreatePostRequest $request, int $tenant_id)
    {
        $tenant = $this->findTenant($tenant_id);

        if (!$tenant) {
            return redirect()->back()->with('error', 'Tenant not found');
        }

        $post = Post::create([
            'user_id' => $tenant->id,
            'title' => $request->validated()['title'],
            'content' => $request->validated()['content'],
        ]);

        if (!$post) {
            return redirect()->back()->with('error', 'Could not create post');
        }

        return redirect()->route('posts.index', $tenant->id)
            ->with('success', 'Post created successfully');
    }

    public function show(int $tenant_id, int $id)
    {
        $result = $this->findTenantAndPost($tenant_id, $id);

        if (!$result) {
            return redirect()->back()->with('error', 'Post or Tenant not found');
        }

        return view('posts.show', compact('result'));
    }

    public function edit(int $tenant_id, int $id)
    {
        $result = $this->findTenantAndPost($tenant_id, $id);

        if (!$result) {
            return redirect()->back()->with('error', 'Post or Tenant not found');
        }

        return view('posts.edit', compact('result'));
    }

    public function update(UpdatePostRequest $request, int $tenant_id, int $id)
    {
        $result = $this->findTenantAndPost($tenant_id, $id);

        if (!$result) {
            return redirect()->back()->with('error', 'Post or Tenant not found');
        }

        $result['post']->update($request->validated());

        return redirect()->route('posts.show', [$tenant_id, $id])
            ->with('success', 'Post updated successfully');
    }

    public function destroy(int $tenant_id, int $id)
    {
        $result = $this->findTenantAndPost($tenant_id, $id);

        if (!$result) {
            return redirect()->back()->with('error', 'Post or Tenant not found');
        }

        $result['post']->delete();

        return redirect()->route('posts.index', $tenant_id)
            ->with('success', 'Post deleted successfully');
    }
}
