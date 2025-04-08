<?php

namespace App\Http\Controllers;

use App\Http\Requests\LikeRequest;
use App\Models\Like;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function toggle(LikeRequest $request, $owner)
    {
        $like = Like::where('likeable_id', $request->likeable_id)
            ->where('likeable_type', $request->likeable_type)
            ->where('user_id', auth()->id())
            ->first();

        if (auth()->user() && $like) {
            $like->delete();
        } else {
            Like::create([
                'user_id' => auth()->id() ?? null,
                'likeable_id' => $request->likeable_id,
                'likeable_type' => $request->likeable_type,
            ]);
        }

        return redirect()->back();
    }
}
