<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The books that belong to the category
     */
    public function books()
    {
        return $this->belongsToMany(Book::class)
            ->withTimestamps();
    }

    /**
     * set the category's name and slug attribute
     *
     * @param string $value
     * @return void
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::title($value);
        $this->attributes['slug'] = Str::slug($value, '-');
    }

    /**
     * get the category's image with custom directory path
     *
     * @return mixed
     */
    public function getImage()
    {
        return $this->image ? asset('storage/categories/' . $this->image) : asset('images/no-image.png');
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'slug';
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
        return $this->withTrashed()->where('slug', $value)->firstOrFail();
    }
}
