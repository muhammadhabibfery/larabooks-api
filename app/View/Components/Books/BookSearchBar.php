<?php

namespace App\View\Components\Books;

use Illuminate\View\Component;

class BookSearchBar extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $route, $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route, $type)
    {
        $this->route = $route;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.books.book-search-bar');
    }
}
