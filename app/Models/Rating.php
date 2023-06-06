<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'number',
    ];

    /**
     * 1 Rating gehört zu einem User
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * 1 Rating gehört zu einem Beitrag
     */
    public function entry() : BelongsTo
    {
        return $this->belongsTo(Entry::class);
    }
}
