<div class="col-12 mt-4">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Invoice number</th>
                <th scope="col">Customer Name</th>
                <th scope="col">Total price</th>
                <th scope="col">Status</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
            <tr>
                <th scope="row">{{ (($orders->currentPage() * 10) - 10) + $loop->iteration }}</th>
                <td>{{ $order->invoice_number }}</td>
                <td>
                    <p class="fw-bold">{{ $order->user->name }}</p>
                </td>
                <td>{{ currencyFormat($order->total_price) }}</td>
                <td>
                    <span class="text-monospace fw-bold {{ $order->status_color }}">{{ $order->status }}</span>
                </td>
                <td>
                    @if ($type === 'index')
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-success btn-sm my-1">Detail</a>
                    @else
                    <a href="{{ route('orders.trash.show', $order) }}" class="btn btn-success btn-sm my-1">Detail</a>
                    <a href="{{ route('orders.trash.restore', $order) }}" class="btn btn-warning btn-sm my-1"
                        onclick="return confirm('Are you sure want to restore order {{ $order->invoice_number }} ?')">Restore</a>
                    @endif
                    @isAdmin(auth()->user()->role)
                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                        data-target="#deleteOrder{{ $order->invoice_number }}Modal">Delete</a>
                    @endif
                </td>

                {{-- Delete Order Modal --}}
                <div class="modal fade" id="deleteOrder{{ $order->invoice_number }}Modal" tabindex="-1" role="dialog"
                    aria-labelledby="deleteOrderModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete Order</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure want to delete order {{ $order->invoice_number }} {{ $type === 'trash' ?
                                    '
                                    permanently' : '' }} ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <form
                                    action="{{ $type === 'index' ? route('orders.destroy', $order) : route('orders.trash.force-delete', $order) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </tr>
            @empty
            <tr>
                <td colspan="8">
                    <p class="font-weight-bold text-center text-monospace">
                        {{ $type === 'trash' ? 'Deleted orders' : 'orders' }} not available
                    </p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $orders->withQueryString()->onEachSide(2)->links() }}
    </div>
</div>
