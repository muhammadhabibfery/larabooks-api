<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    protected $guarded = [];

    /**
     * The books that belong to the category
     */
    public function books()
    {
        return $this->belongsToMany(Book::class)->withTimestamps();
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
        return ($this->category_image) ? asset('storage/images/' . $this->image) : asset('storage/default/no-image.png');
    }
}
