<?php

namespace App\View\Components\Users;

use Illuminate\View\Component;

class UserList extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $users, $roles, $type, $routeName;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($users, $roles, $type, $routeName)
    {
        $this->users = $users;
        $this->roles = $roles;
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
        return view('components.users.user-list');
    }
}
