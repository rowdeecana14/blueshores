<?php

namespace App\Http\Controllers;

use App\Enums\Album\Active;
use App\Models\Album;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $query = $request->input('query', '');
        $pagination = 12;

        $voteStatsSubquery = DB::table('votes')
            ->selectRaw('
                album_id,
                COUNT(CASE WHEN vote = "up" THEN 1 END) -
                COUNT(CASE WHEN vote = "down" THEN 1 END) AS total_votes
            ')
            ->groupBy('album_id');

        $albums = Album::query()
            ->leftJoinSub($voteStatsSubquery, 'vote_stats', function ($join) {
                $join->on('albums.id', '=', 'vote_stats.album_id');
            })
            ->select('albums.*')
            ->addSelect('vote_stats.total_votes')
            ->where('albums.active', Active::YES)
            ->when($query, function ($q) use ($query) {
                return $q->where('albums.song_name', 'like', '%' . $query . '%');
            })
            ->orderByDesc('vote_stats.total_votes')
            ->orderBy('albums.song_name')
            ->paginate($pagination);

        return Inertia::render('Welcome', [
            'pagination' => $pagination,
            'query' => $query ?? '',
            'albums' => $albums,
        ]);
    }
}
