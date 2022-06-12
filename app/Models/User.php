<?php

namespace App\Models;

use App\Models\Master\Page;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Support\Facades\Storage;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'foto',
        'username',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function encryptedId()
    {
        return Crypt::encryptString($this->id);
    }

    public function activities()
    {
        return $this->hasMany(Activity::class, 'causer_id', 'id');
    }

    public function takeImage()
    {
        if ($this->foto === null) {
            return asset("images/no-image.png");
        } else {
            $exist = Storage::exists($this->foto);

            if ($exist) {
                return asset("storage/" . $this->foto);
            } else {
                return asset("images/no-image.png");
            }
        }
    }

    public function pages()
    {
        return $this->hasMany(Page::class, 'created_by', 'id');
    }
}
