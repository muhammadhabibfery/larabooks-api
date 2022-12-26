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
    public $route, $user, $roles;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route, $user, $roles)
    {
        $this->route = $route;
        $this->user = $user;
        $this->roles = $roles;
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
