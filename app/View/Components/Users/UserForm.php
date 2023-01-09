<?php

namespace App\View\Components\Users;

use Illuminate\View\Component;

class UserForm extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $route, $roles, $cities, $user;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route, $roles, $cities, $user)
    {
        $this->route = $route;
        $this->roles = $roles;
        $this->cities = $cities;
        $this->user = $user;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.users.user-form');
    }
}
