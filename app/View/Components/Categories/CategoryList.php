<?php

namespace App\View\Components\Categories;

use Illuminate\View\Component;

class CategoryList extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $categories, $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($categories, $type)
    {
        $this->categories = $categories;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.categories.category-list');
    }
}
