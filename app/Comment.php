<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'body', 'owner_id', 'gallery_id'
    ];

    public function gallery()
    {
        return $this->belongsTo(Gallery::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }
}

