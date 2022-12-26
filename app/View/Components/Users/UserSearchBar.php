<?php

namespace App\View\Components\Users;

use Illuminate\View\Component;

class UserSearchBar extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $route;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route)
    {
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.users.user-search-bar');
    }
}
