<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'title', 'body','slug','author_id','image_url'
    ];

    function author() : HasOne
    {
        return $this->hasOne(User::class,'id','author_id') ;
    }

    function likes() : HasMany
    {
        return $this->hasMany(Like::class,'post_id','id') ;
    }
}
