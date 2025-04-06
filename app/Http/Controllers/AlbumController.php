<?php

namespace App\Http\Controllers;

use App\Enums\Album\Active;
use App\Enums\User\Role;
use Illuminate\Http\Request;
use App\Models\Album;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Vote;
use App\Enums\Vote\VoteType;

class AlbumController extends Controller
{

    public function index(Request $request): Response
    {
        $auth = auth()->user();
        $query = $request->input('query', '');
        $pagination = 12;
        $is_admin = $auth->role === Role::ADMIN->value;

        $albums = Album::select('albums.*')
            ->selectRaw('
                COUNT(CASE WHEN votes.vote = "up" THEN 1 END) - COUNT(CASE WHEN votes.vote = "down" THEN 1 END) AS total_votes
            ')
            ->selectRaw(
                '
             MAX(CASE WHEN votes.user_id = ? THEN votes.vote END) AS user_vote',
                [$auth->id]
            )
            ->leftJoin('votes', 'votes.album_id', '=', 'albums.id')
            ->where('active', Active::YES)
            ->groupBy('albums.id')
            ->orderBy('total_votes', 'desc')
            ->orderBy('song_name', 'asc')
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('song_name', 'like', '%' . $query . '%');
            })
            ->paginate($pagination);

        return Inertia::render('Dashboard', [
            'is_admin' => $is_admin,
            'query' => $query ?? '',
            'albums' => $albums,
        ]);
    }

    public function upvote(Request $request, Album $album)
    {
        $id = auth()->user()->id;

        Vote::updateOrCreate(
            ['user_id' => $id, 'album_id' => $album->id],
            ['vote' => VoteType::UP]
        );

        return redirect()->back()->with('success', 'Your vote has been recorded.');
    }

    public function downvote(Request $request, Album $album)
    {
        $id = auth()->user()->id;

        Vote::updateOrCreate(
            ['user_id' => $id, 'album_id' => $album->id],
            ['vote' => VoteType::DOWN]
        );

        return redirect()->back()->with('success', 'Your vote has been recorded.');
    }

    public function destroy(Request $request, Album $album)
    {
        $album->active = Active::NO;
        $album->save();
        $album->delete();

        return redirect()->back()->with('success', 'Album deleted successfully.');
    }
}
