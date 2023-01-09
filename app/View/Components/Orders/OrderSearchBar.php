<?php

namespace App\View\Components\Orders;

use Illuminate\View\Component;

class OrderSearchBar extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $route, $status, $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($route, $status, $type)
    {
        $this->route = $route;
        $this->status = $status;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.orders.order-search-bar');
    }
}
