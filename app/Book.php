<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    protected $guarded = ['image', 'action'];

    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->withTimestamps();
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class)
            ->withPivot(['quantity'])
            ->withTimestamps();
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = Str::title($value);
        $this->attributes['slug'] = Str::slug($value, '-');
    }

    public function getCoverBook()
    {
        return ($this->cover) ? asset('/storage/' . $this->cover) : asset('/storage/default/no-image.png');
    }
}
