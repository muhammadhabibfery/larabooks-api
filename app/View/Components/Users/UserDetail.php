<?php

namespace App\View\Components\Users;

use Illuminate\View\Component;

class UserDetail extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $user, $route;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($user, $route)
    {
        $this->user = $user;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.users.user-detail');
    }
}
