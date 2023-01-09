<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * constant of property names
     *
     * @var string
     */
    private const ROUTE_INDEX = 'orders.index', ROUTE_TRASH = 'orders.trash', STATUS = ['SUBMIT', 'PENDING', 'SUCCESS', 'FAILED'];

    /**
     * authorizing the order controller
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('authRole:ADMIN')->only(['destroy', 'indexTrash', 'showTrash', 'restore', 'forceDelete']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = $this->getAllOrdersBySearch($request->keyword, $request->status, 10);
        $status = self::STATUS;
        return view('pages.orders.index', compact('orders', 'status'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show(Order $order)
    {
        return view('pages.orders.show', compact('order'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return mixed
     */
    public function destroy(Order $order)
    {
        $successMessage = 'Order successfully deleted';
        $failedMessage = 'Failed to delete order';

        return $this->checkProcess(self::ROUTE_INDEX, $successMessage, function () use ($order, $failedMessage) {
            if (!$order->update(['deleted_by' => auth()->id()])) throw new \Exception($failedMessage);
            if (!$order->delete()) throw new \Exception($failedMessage);
        }, true);
    }

    /**
     * Display a listing of the deleted resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function indexTrash(Request $request)
    {
        $orders = $this->getAllOrdersBySearch($request->keyword, $request->status, 10, true);
        $status = self::STATUS;
        return view('pages.orders.trash.index-trash', compact('orders', 'status'));
    }

    /**
     * Display the specified deleted resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function showTrash(Order $order)
    {
        return view('pages.orders.trash.show-trash', compact('order'));
    }

    /**
     * restore the specified deleted resource.
     *
     * @param  \App\Order  $order
     * @return mixed
     */
    public function restore(Order $order)
    {
        $successMessage = 'Order succussfully restored';
        $failedMessage = 'Failed to restore order';

        return $this->checkProcess(self::ROUTE_TRASH, $successMessage, function () use ($order, $failedMessage) {
            if (!$order->update(['deleted_by' => null])) throw new \Exception($failedMessage);
            if (!$order->restore()) throw new \Exception($failedMessage);
        }, true);
    }

    /**
     * remove the specified deleted resource.
     *
     * @param  \App\Order  $order
     * @return mixed
     */
    public function forceDelete(Order $order)
    {
        $successMessage = 'Order successfully deleted permanently';
        $failedMessage = 'Failed to delete order permanently';

        return $this->checkProcess(self::ROUTE_TRASH, $successMessage, function () use ($order, $failedMessage) {
            if ($order->books()->detach() > 0) {
                if (!$order->forceDelete()) throw new \Exception($failedMessage);
            } else {
                throw new \Exception($failedMessage);
            }
        }, true);
    }

    /**
     * query all orders by search
     *
     * @param  string $keyword
     * @param  string $status
     * @param  int $number (define paginate data per page)
     * @param  bool $onlyDeleted (only trashed when delete using soft delete)
     * @return \illuminate\Pagination\LengthAwarePaginator
     */
    private function getAllOrdersBySearch(?string $keyword, ?string $status, int $number, bool $onlyDeleted = false)
    {
        $orders = Order::with(['user', 'books' => fn ($query) => $query->where('title', 'LIKE', "%$keyword%")])
            ->where(fn ($query) => $query->getCustomer($keyword)->getBooks($keyword));

        if ($status && in_array($status, self::STATUS)) $orders->where('status', $status);

        if ($onlyDeleted) $orders->onlyTrashed();

        return $orders->latest()
            ->paginate($number);
    }

    /**
     * Check one or more processes and catch them if fail
     *
     * @param  string $routeName
     * @param  string $successMessage
     * @param  callable $action
     * @param  bool $dbTransaction (use database transaction for multiple queries)
     * @return \Illuminate\Http\Response
     */
    private function checkProcess(string $routeName, string $successMessage, callable $action, ?bool $dbTransaction = false)
    {
        try {
            if ($dbTransaction) DB::beginTransaction();

            $action();

            if ($dbTransaction) DB::commit();
        } catch (\Exception $e) {
            if ($dbTransaction) DB::rollback();

            return redirect()->route($routeName)
                ->with('failed', $e->getMessage());
        }

        return redirect()->route($routeName)
            ->with('success', $successMessage);
    }
}
