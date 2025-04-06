<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LikeRequest;
use App\Models\Like;
use App\Models\User;
use App\Traits\ResponseTrait;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    use ResponseTrait;
    public function store(LikeRequest $request)
    {
        try {
            $models = [
                'post' => \App\Models\Post::class,
                'comment' => \App\Models\Comment::class,
            ];

            $likeType = $models[$request->likeable_type];

            $like = Like::create([
                'user_id' => auth()->id() ?? null,
                'likeable_id' => $request->likeable_id,
                'likeable_type' => $likeType
            ]);

            return $this->successResponse('Like created successfully', ['like' => $like]);
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Sever error', $exception);
        }
    }
    public function destroy($id)
    {
        try {
            $like = Like::find($id);

            if (! $like) {
                return $this->notFoundResponse('Like not found');
            }

            $like->delete();

            return $this->successResponse('Like deleted successfully');
        } catch (\Exception $exception) {
            return $this->serverErrorResponse('Sever error', $exception);
        }
    }
}
