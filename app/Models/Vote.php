<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
    protected $fillable = [
        'album_id',
        'user_id',
        'vote',
    ];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }
}
