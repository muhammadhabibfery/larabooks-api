<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Category extends Model
{
    use SoftDeletes;

    protected $guarded = ['image'];

    public function books()
    {
        return $this->belongsToMany(Book::class)->withTimestamps();
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::title($value);
        $this->attributes['slug'] = Str::slug($value, '-');
    }

    public function getCategoryImage()
    {
        return ($this->category_image) ? asset('/storage/' . $this->category_image) : asset('/storage/default/no-image.png');
    }
}
