<?php

namespace App\Http\Controllers;

use App\Enums\Album\Active;
use App\Models\Album;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function index(Request $request): Response
    {
        $query = $request->input('query', '');
        $pagination = 12;

        $albums = Album::select('albums.*')
            ->selectRaw('
                COUNT(CASE WHEN votes.vote = "up" THEN 1 END) - COUNT(CASE WHEN votes.vote = "down" THEN 1 END) AS total_votes
            ')
            ->leftJoin('votes', 'votes.album_id', '=', 'albums.id')
            ->where('active', Active::YES)
            ->groupBy(
                'albums.id',
                'albums.user_id',
                'albums.song_name',
                'albums.created_at',
                'albums.updated_at',
                'albums.deleted_at'
            )
            ->orderBy('total_votes', 'desc')
            ->orderBy('song_name', 'asc')
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('song_name', 'like', '%'.$query.'%');
            })
            ->paginate($pagination);

        return Inertia::render('Welcome', [
            'pagination' => $pagination,
            'query' => $query ?? '',
            'albums' => $albums,
        ]);
    }
}
