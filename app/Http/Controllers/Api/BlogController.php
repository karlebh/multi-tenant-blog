<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\Post;
use App\Models\User;
use App\Traits\MethodTrait;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use ResponseTrait, MethodTrait;


    public function index(int $tenant_id)
    {
        $tenant = $this->findTenant($tenant_id);

        $posts =  Post::where('user_id', $tenant_id)->latest()->paginate(20);

        if (! $posts) {
            $this->notFoundResponse('Posts could not be retreived');
        }

        return $this->successResponse('All blog details', [
            'blog' => $tenant->blog,
            'tenant' => $tenant,
            'posts' => $posts,
        ]);
    }

    public function update(UpdateBlogRequest $request, int $tenant_id)
    {
        $tenant = $this->findTenant($tenant_id);

        $tenant->blog->update($request->validated());

        $processedFile = $this->processFile($request);

        if (! empty($processedFile)) {
            $tenant->blog->update([
                'file' => $processedFile,
            ]);
        }

        return $this->successResponse('Blog details updated successfully', ['blog' => $tenant->blog->fresh()]);
    }
}
