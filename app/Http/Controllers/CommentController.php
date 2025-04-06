<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateCommentRequest;
use App\Http\Requests\UpdateCommentRequest;
use App\Models\Comment;
use App\Models\User;
use App\Traits\MethodHelper;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    use MethodHelper;

    public function store(CreateCommentRequest $request, $tenant_id)
    {
        $tenant = $this->findTenant($tenant_id);

        if (! $tenant) {
            return redirect()->back()->with('error', 'Tenant not found');
        }

        $comment = Comment::create($request->validated());

        if (! $comment) {
            return redirect()->back()->with('error', 'Could not create comment');
        }

        return redirect()->back()->with('success', 'Comment created successfully');
    }

    public function edit($tenant_id, $comment_id)
    {
        $result = $this->findTenantAndComment($tenant_id, $comment_id);

        if (!is_array($result)) {
            return redirect()->back()->with('error', 'Tenant or Comment not found');
        }

        return view('comments.edit', [
            'tenant' => $result['tenant'],
            'comment' => $result['comment'],
        ]);
    }

    public function update(UpdateCommentRequest $request, $tenant_id, $comment_id)
    {
        $result = $this->findTenantAndComment($tenant_id, $comment_id);

        if (!is_array($result)) {
            return redirect()->back()->with('error', 'Tenant or Comment not found');
        }

        $result['comment']->update($request->validated());

        return redirect()->back()->with('success', 'Comment updated successfully');
    }

    public function destroy($tenant_id, $comment_id)
    {
        $result = $this->findTenantAndComment($tenant_id, $comment_id);

        if (!is_array($result)) {
            return redirect()->back()->with('error', 'Tenant or Comment not found');
        }

        $result['comment']->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully');
    }
}
