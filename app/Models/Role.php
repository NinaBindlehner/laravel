<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
    ];

    public function padletUser() : HasMany {
        return $this->hasMany(PadletUser::class);
    }

    //ev users + padlets belongstomany
    //1 Rolle gehört zu mehreren Usern
    /*public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }*/
}
