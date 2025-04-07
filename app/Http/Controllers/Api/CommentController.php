<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Comment;
use App\Models\User;
use App\Traits\MethodTrait;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CommentController extends Controller
{
    use ResponseTrait, MethodTrait;

    public function store(CreateCommentRequest $request, $tenant_id)
    {
        try {
            $tenant =  $this->findTenant($tenant_id);

            $comment = Comment::create($request->validated());

            if (! $comment) {
                return $this->badRequestResponse('Could not create comment');
            }

            return $this->successResponse('Comment created successfully', ['comment' => $comment]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Server error', $exception);
        }
    }

    public function update(UpdateCommentRequest $request, int $tenant_id, $comment_id)
    {
        try {
            $result = $this->findTenantAndComment($tenant_id, $comment_id);

            $result['comment']->update($request->validated());

            return $this->successResponse('comment updated successfully', [
                'comment' => $result['comment']->fresh()
            ]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Server error', $exception);
        }
    }
    public function delete(int $tenant_id, int $comment_id)
    {
        $result = $this->findTenantAndComment($tenant_id, $comment_id);

        $result['comment']->delete();

        return $this->successResponse('comment deleted successfully');
    }
}
