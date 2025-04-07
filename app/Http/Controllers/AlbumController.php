<?php

namespace App\Http\Controllers;

use App\Enums\Album\Active;
use App\Enums\User\Role;
use App\Enums\Vote\VoteType;
use App\Models\Album;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class AlbumController extends Controller
{
    public function index(Request $request): Response
    {
        $auth = auth()->user();
        $query = $request->input('query', '');
        $pagination = 12;
        $is_admin = $auth->role === Role::ADMIN->value;

        $voteStatsSubquery = DB::table('votes')
            ->selectRaw('
                album_id,
                COUNT(CASE WHEN vote = "up" THEN 1 END) -
                COUNT(CASE WHEN vote = "down" THEN 1 END) AS total_votes,
                MAX(CASE WHEN user_id = ? THEN vote END) AS user_vote
            ', [$auth->id])
            ->groupBy('album_id');

        $albums = Album::query()
            ->leftJoinSub($voteStatsSubquery, 'vote_stats', function ($join) {
                $join->on('albums.id', '=', 'vote_stats.album_id');
            })
            ->select('albums.*')
            ->addSelect('vote_stats.total_votes', 'vote_stats.user_vote')
            ->where('albums.active', Active::YES)
            ->when($query, function ($q) use ($query) {
                return $q->where('albums.song_name', 'like', '%' . $query . '%');
            })
            ->orderByDesc('vote_stats.total_votes')
            ->orderBy('albums.song_name')
            ->paginate($pagination);

        return Inertia::render('Dashboard', [
            'pagination' => $pagination,
            'user' => auth()->user(),
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
