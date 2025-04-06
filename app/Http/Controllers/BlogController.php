<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Traits\MethodHelper;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use MethodHelper;

    public function index(int $tenant_id)
    {
        $tenant = $this->findTenant($tenant_id);
        $blogs = Blog::where('user_id', $tenant->id)->get();

        $posts =  Post::latest()->paginate(20);

        return view('blogs.index', compact('blogs', 'tenant', 'posts'));
    }

    public function edit(int $tenant_id, int $blog_id)
    {
        $tenant = $this->findTenant($tenant_id);
        $blog = Blog::findOrFail($blog_id);

        return view('blogs.edit', compact('blog', 'tenant'));
    }

    public function update(UpdateBlogRequest $request, int $tenant_id, int $blog_id)
    {
        $tenant = $this->findTenant($tenant_id);

        $blog = Blog::findOrFail($blog_id);
        $blog->update($request->validated());

        return redirect()->route('blogs.index', $tenant_id)->with('success', 'Blog details updated successfully');
    }
}
