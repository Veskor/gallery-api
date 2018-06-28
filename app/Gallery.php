<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $appends = ['cover_image'];

    protected $fillable = [
        'name', 'description', 'owner_id'
    ];

    public function images() 
    {
        return $this->hasMany(Image::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->with('owner');
    }

    public function owner()
    {
        return $this->belongsTo(User::class);
    }

    public function getCoverImageAttribute() {
    return $this->images()->orderBy('order', 'asc')->first();
    }


    public static function search($skip, $take, $term, User $owner=null)
    {
        $query = Gallery::query();
        $query->with([
            'owner',
            'images',
        ]);

        if(!empty($owner)){
            $query->where('owner_id', '=', $owner->id);
        }

        if(!empty($term)){
            $query->where(function($q) use ($term){
                $q->where('name', 'like', '%'.$term.'%')
                  ->orWhere('description','like', '%'.$term.'%')
                  ->orWhereHas('owner', function($q) use ($term){
                      $q->where('first_name', 'like', '%'.$term.'%')
                        ->orWhere('last_name','like', '%'.$term.'%');    
                  }); 
            });                  
        }

        $count = $query->count();
        $galleries = $query->skip($skip)
                            ->take($take)
                            ->orderBy('created_at', 'desc')
                            ->get();
                            
        return compact('count', 'galleries');
    }

}

