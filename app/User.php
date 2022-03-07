<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Profil;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // 'name', 'email', 'address', 'no_handphone', 'password', 'group_id', 'id_group'
        'id','name', 'email', 'address', 'no_handphone', 'password', 'id_group', 'id_profil'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'id');
    }
    public function group_user(){
        return $this->belongsTo(GroupUser::class, 'id_group');
    }
    public function user() {
        return $this->belongTo(User::class, 'id');
    }

    public function groupUser() {
        return $this->belongsTo(GroupUser::class, 'id_group', 'id' );
    }

    public function profil() {
        return $this->belongsTo(Profil::class, 'id_profil');
    }
}
