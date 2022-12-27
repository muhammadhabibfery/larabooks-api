<?php

namespace App\View\Components\Categories;

use Illuminate\View\Component;

class CategoryDetail extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $category, $route;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($category, $route)
    {
        $this->category = $category;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.categories.category-detail');
    }
}
