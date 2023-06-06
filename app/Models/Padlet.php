<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Padlet extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'is_public',
        'user_id',
    ];

    /**
     * mehrere Padlets gehören zu mehreren Usern (m:n)
     */
    public function users() : BelongsToMany
    {
        return $this->belongsToMany(User::class, 'padlets_users')->withTimestamps();
    }

    /**
     * 1 Padlet gehört zu 1 User
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 1 Padlet hat viele Einträge
     */
    public function entries() : HasMany
    {
        return $this->hasMany(Entry::class);
    }
}
