<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Album;
use Inertia\Response;
use App\Enums\Album\Active;
use App\Enums\User\Role;

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
            ->groupBy('albums.id')
            ->orderBy('total_votes', 'desc')
            ->orderBy('song_name', 'asc')
            ->when($query, function ($queryBuilder) use ($query) {
                return $queryBuilder->where('song_name', 'like', '%' . $query . '%');
            })
            ->paginate($pagination);

        return Inertia::render('Welcome', [
            'query' => $query ?? '',
            'albums' => $albums,
        ]);
    }
}
