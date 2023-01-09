<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Provincy extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the cities for the provincy
     */
    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
