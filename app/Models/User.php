<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        //'password',
        'image',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * mehrere User haben mehrere Padlets (m:n)
     */
    public function padlets() : BelongsToMany
    {
        return $this->belongsToMany(Padlet::class, 'padlets_users')->withTimestamps();
    }

    /**
     * 1 User hat mehrere eigene Padlets
     */
    public function padlet() : hasMany
    {
        return $this->hasMany(Padlet::class);
    }

    /**
     * 1 User hat viele Einträge
     */
    public function entries() : HasMany
    {
        return $this->hasMany(Entry::class);
    }

    /**
     * 1 User hat viele Kommentare
     */
    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * 1 User hat viele Ratings
     */
    public function ratings() : HasMany
    {
        return $this->hasMany(Rating::class);
    }

    //identifiziert JWT
    public function getJWTIdentifier() {
        $this->getKey();
    }

    //schreibt User-id in JWT rein
    public function getJWTCustomClaims() {
        return ['user' => ['id' => $this->id]];
    }
}
