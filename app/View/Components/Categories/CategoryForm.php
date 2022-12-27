<?php

namespace App\View\Components\Categories;

use Illuminate\View\Component;

class CategoryForm extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $route, $category;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route, $category)
    {
        $this->route = $route;
        $this->category = $category;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.categories.category-form');
    }
}
