<?php

namespace App\View\Components\Books;

use Illuminate\View\Component;

class BookList extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $books, $status, $type, $routeName;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($books, $status, $type, $routeName)
    {
        $this->books = $books;
        $this->status = $status;
        $this->type = $type;
        $this->routeName = $routeName;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.books.book-list');
    }
}
