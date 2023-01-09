<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['image'];

    /**
     * Get the city that owns the book
     */
    public function city()
    {
        return $this->belongsTo(City::class);
    }

    /**
     * The categories that belong to the book
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class)
            ->withTimestamps();
    }

    /**
     * The orders that belong to the book
     */
    public function orders()
    {
        return $this->belongsToMany(Order::class)
            ->withPivot(['quantity'])
            ->withTimestamps();
    }

    /**
     * set the book's title
     *
     * @param  string $value
     * @return void
     */
    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = Str::title($value);
        $this->attributes['slug'] = Str::slug($value, '-');
    }

    /**
     * set the book's author
     *
     * @param  string $value
     * @return void
     */
    public function setAuthorAttribute($value)
    {
        $this->attributes['author'] = Str::title($value);
    }

    /**
     * set the book's publisher
     *
     * @param  string $value
     * @return void
     */
    public function setPublisherAttribute($value)
    {
        $this->attributes['publisher'] = Str::title($value);
    }

    /**
     * get the book's cover with custom directory path
     *
     * @return mixed
     */
    public function getCover()
    {
        return ($this->cover) ? asset('storage/books/' . $this->cover) : asset('images/no-image.png');
    }

    /**
     * Scope a query to include categories relations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetCategories($query, $keyword)
    {
        return $query->whereHas('categories', fn (Builder $query) => $query->where('name', 'LIKE', "%$keyword%"));
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
