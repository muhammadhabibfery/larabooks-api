<?php

namespace App;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the user that owns the order
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The books that belong to the order
     */
    public function books()
    {
        return $this->belongsToMany(Book::class)
            ->withPivot(['quantity'])
            ->withTimestamps();
    }

    /**
     * get the order's status with custom attribute
     *
     * @return string
     */
    public function getStatusColorAttribute()
    {
        if ($this->status === 'SUBMIT') return 'text-warning';
        if ($this->status === 'PENDING') return 'text-primary';
        if ($this->status === 'SUCCESS') return 'text-success';
        if ($this->status === 'FAILED') return 'text-danger';
    }

    /**
     * Scope a query to include the user relations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetCustomer($query, $keyword)
    {
        return $query->whereHas('user', fn (Builder $query) => $query->where('name', 'LIKE', "%$keyword%"));
    }

    /**
     * Scope a query to include the books relations.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGetBooks($query, $keyword)
    {
        return $query->orWhereHas('books', fn (Builder $query) => $query->where('title', 'LIKE', "%$keyword%"));
    }

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName()
    {
        return 'invoice_number';
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
        return $this->withTrashed()->where('invoice_number', $value)->firstOrFail();
    }
}
