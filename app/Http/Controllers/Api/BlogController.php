<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateBlogRequest;
use App\Http\Requests\UpdateBlogRequest;
use App\Models\Blog;
use App\Models\User;
use App\Traits\MethodTrait;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    use ResponseTrait, MethodTrait;

    public function store(CreateBlogRequest $request, int $tenant_id)
    {
        $tenant = $this->findTenant($tenant_id);

        $blog = Blog::create(array_merge($request->validated(), ['user_id' => $tenant->id]));

        if (! $blog) {
            return $this->badRequestResponse('Could not add blog details');
        }

        return $this->successResponse('Blog details added successfullt', ['blog' => $blog]);
    }

    public function update(UpdateBlogRequest $request, int $tenant_id, int $blog_id)
    {
        $tenant = $this->findTenant($tenant_id);

        $blog = Blog::where('id', $blog_id)->update(array_merge($request->validated()));

        if (! $blog) {
            return $this->badRequestResponse('Could not update blog details');
        }

        return $this->successResponse('Blog details updated successfullt', ['blog' => $blog->fresh()]);
    }
}
