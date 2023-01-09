<?php

namespace App\View\Components\Orders;

use Illuminate\View\Component;

class OrderList extends Component
{
    /**
     * The name of component's data
     *
     * @var mixed
     */
    public $orders, $type;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($orders, $type)
    {
        $this->orders = $orders;
        $this->type = $type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {
        return view('components.orders.order-list');
    }
}
