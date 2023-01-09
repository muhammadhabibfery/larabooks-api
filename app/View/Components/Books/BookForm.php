<?php

namespace App\View\Components\Books;

use Illuminate\View\Component;

class BookForm extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $route, $categories, $status, $cities, $book;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route, $categories, $status, $cities, $book)
    {
        $this->route = $route;
        $this->categories = $categories;
        $this->status = $status;
        $this->cities = $cities;
        $this->book = $book;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.books.book-form');
    }
}
