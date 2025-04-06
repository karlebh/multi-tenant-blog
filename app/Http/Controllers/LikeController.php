<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Models\Like;
use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function store(LikeRequest $request)
    {
        $models = [
            'post' => Post::class,
            'comment' => Comment::class,
        ];

        if (! $models[$request->likeable_type]) {
            return redirect()->back()->with('error', 'Invalid likeable type');
        }

        $likeType = $models[$request->likeable_type];

        $like = Like::create([
            'user_id' => auth()->id(),
            'likeable_id' => $request->likeable_id,
            'likeable_type' => $likeType,
        ]);

        return redirect()->back()->with('success', 'Like created successfully');
    }

    public function destroy($id)
    {
        $like = Like::find($id);

        if (! $like) {
            return redirect()->back()->with('error', 'Like not found');
        }

        $like->delete();

        return redirect()->back()->with('success', 'Like deleted successfully');
    }
}
