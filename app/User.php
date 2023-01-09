<?php

namespace App;

use Illuminate\Support\Str;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use Notifiable, SoftDeletes, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['image', 'provincy_id'];

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

    /**
     * Get the orders for the user
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the city that owns the user
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * set the user's name
     *
     * @param  string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::title($value);
    }

    /**
     * set the user's role
     *
     * @param  string $value
     * @return void
     */
    public function setRoleAttribute($value)
    {
        $value = strtoupper($value);
        $this->attributes['role'] = json_encode([$value]);
    }

    /**
     * get the user's roles
     *
     * @return arrray
     */
    public function getRoleAttribute($value)
    {
        return json_decode($value);
    }

    /**
     * get the user's roles as string type
     *
     * @return string
     */
    public function getStringRoleAttribute()
    {
        return $this->role[0];
    }

    /**
     * get the user's avatar with custom directory path
     *
     * @return mixed
     */
    public function getAvatar()
    {
        return $this->avatar ? asset('storage/avatars/' . $this->avatar) : asset('images/no-user.jpg');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'username';
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param  mixed  $value
     * @param  string|null  $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->withTrashed()->where('username', $value)->firstOrFail();
    }
}
