<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the books for the city
     */
    public function books()
    {
        return $this->hasMany(Book::class);
    }

    /**
     * Get the users for the city
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }

    /**
     * Get the provincy that owns the city
     */
    public function provincy()
    {
        return $this->belongsTo(Provincy::class);
    }
}
