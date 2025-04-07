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
    public function store(CreateCommentRequest $request)
    {
        $comment = Comment::create($request->validated());

        return redirect()->back()->with('success', 'Comment created successfully');
    }

    public function edit(User $tenant, Comment $comment)
    {
        return view('comments.edit', [
            'tenant' => $tenant,
            'comment' => $comment,
        ]);
    }

    public function update(UpdateCommentRequest $request, User $tenant, Comment $comment)
    {
        $comment->update($request->validated());

        return redirect()->back()->with('success', 'Comment updated successfully');
    }

    public function destroy(User $tenant, Comment $comment)
    {
        $comment->delete();

        return redirect()->back()->with('success', 'Comment deleted successfully');
    }
}
