<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'post_code',
        'address',
        'building',
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

    // My_Listリレーション（中間テーブル経由でのItemとの多対多）
    public function myLists()
    {
        return $this->belongsToMany(Item::class, 'my_lists', 'user_id', 'item_id')
                    ->withTimestamps();
    }

    // Commentリレーション（1対多）
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Likeリレーション（1対多）
    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    // Purchaseリレーション（1対多）
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    // Sellリレーション（1対多）
    public function sells()
    {
        return $this->hasMany(Sell::class);
    }
}
