<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable = [
        'url', 'order'
    ];

    public function galleries()
    {
        return $this->belongsTo(Gallery::class);
    }
}

