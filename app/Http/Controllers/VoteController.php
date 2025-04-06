<?php

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Vote;
use Illuminate\Http\Request;

class VoteController extends Controller
{
    public function vote(Request $request, Album $album)
    {
        $request->validate([
            'vote' => 'required|in:up,down',
        ]);

        $userId = auth()->id;

        Vote::updateOrCreate(
            ['user_id' => $userId, 'album_id' => $album->id],
            ['vote' => $request->vote]
        );

        return back()->with('success', 'Your vote has been recorded.');
    }
}
