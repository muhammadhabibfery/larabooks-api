<?php

namespace App\View\Components\Orders;

use Illuminate\View\Component;

class OrderDetail extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $order, $route;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($order, $route)
    {
        $this->order = $order;
        $this->route = $route;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.orders.order-detail');
    }
}
