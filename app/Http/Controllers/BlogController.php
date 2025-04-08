<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Traits\MethodTrait;

class BlogController extends Controller
{
    use MethodTrait;

    public function index(User $tenant)
    {
        $blogs = Blog::where('user_id', $tenant->id)->get();

        $posts =  Post::where('user_id', $tenant->id)->latest()->paginate(20);

        return view('blogs.index', compact('blogs', 'tenant', 'posts'));
    }

    public function edit(User $tenant, Blog $blog)
    {
        return view('blogs.edit', compact('blog', 'tenant'));
    }

    public function update(UpdateBlogRequest $request, User $tenant, Blog $blog)
    {
        $blog->update($request->validated());

        return redirect()->route('blogs.index', $tenant->id)->with('success', 'Blog details updated successfully');
    }
}
