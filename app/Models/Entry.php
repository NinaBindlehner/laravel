<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'padlet_id',
        'user_id',
    ];

    /**
     * 1 Beitrag gehört zu einem User
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 1 Beitrag gehört zu einem Padlet
     */
    public function padlet() : BelongsTo
    {
        return $this->belongsTo(Padlet::class);
    }

    /**
     * 1 Eintrag hat viele Kommentare
     */
    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 1 Eintrag hat viele Ratings
     */
    public function ratings() : HasMany
    {
        return $this->hasMany(Rating::class);
    }
}
