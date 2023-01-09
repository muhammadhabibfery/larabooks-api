<div class="col-md-12">
    <div class="card mb-3">
        <div class="row no-gutters">
            <div class="col-md-8">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="card-title">Invoice Number</h5>
                            <p class="card-text">{{ $order->invoice_number }}</p>
                            <h5 class="card-title">Customer Name</h5>
                            <p class="card-text">{{ $order->user->name }}</p>
                            <h5 class="card-title">Detail Book(s)</h5>
                            <ul>
                                @foreach ($order->books as $book)
                                <li class="card-text">
                                    {{ $book->title }} : {{ $book->pivot->quantity }}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="col-6">
                            <h5 class="card-title">Total Price</h5>
                            <p class="card-text">{{ currencyFormat($order->total_price) }}</p>
                            <h5 class="card-title">Date</h5>
                            <p class="card-text">{{ transformDateFormat($order->created_at) }}</p>
                            <h5 class="card-title">Book status</h5>
                            <p class="card-text text-monospace {{ $order->status_color }}">
                                {{ $order->status }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="card-text d-inline-block mr-2">
                            <small class="text-muted">
                                Created at {{ $order->created_at->diffForHumans() }}
                            </small>
                        </p>
                        @if ($order->updated_by)
                        <p class="card-text d-inline-block mr-2">
                            <small class="text-muted">
                                Last updated by {{ createdUpdatedDeletedBy($order->updated_by) }}
                                ({{ $order->updated_at->diffForHumans() }})
                            </small>
                        </p>
                        @endif
                        @if ($order->deleted_by)
                        <p class="card-text d-inline-block mr-2">
                            <small class="text-muted">
                                Deleted by {{ createdUpdatedDeletedBy($order->deleted_by) }}
                                ({{ $order->deleted_at->diffForHumans() }})
                            </small>
                        </p>
                        @endif
                        <a href="{{ $route }}" class="btn btn-link btn-outline-primary float-right">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
