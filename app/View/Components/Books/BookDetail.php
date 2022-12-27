<?php

namespace App\View\Components\Books;

use Illuminate\View\Component;

class BookDetail extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $book, $route;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($book, $route)
    {
        $this->book = $book;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.books.book-detail');
    }
}
