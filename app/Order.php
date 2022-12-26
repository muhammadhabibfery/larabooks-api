<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'total_price', 'invoice_number', 'status'
    ];

    // protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function books()
    {
        return $this->belongsToMany(Book::class)
            ->withPivot(['quantity'])
            ->withTimestamps();
    }

    public function getTotalQuantityAttribute()
    {
        $total_quantity = 0;

        foreach ($this->books as $book) {
            $total_quantity += $book->pivot->quantity;
        }

        return $total_quantity;
    }
}
