@extends('layouts.global')

@section('title', 'List Of Orders')

@section('pageTitle', 'Daftar Order')

@section('content')
<div class="col-md-9">
    <form action="{{ route('orders.index') }}" method="get">
        {{-- @csrf --}}
        <div class="input-group mb-3">
            <input type="text" name="search" class="form-control w-50"
                placeholder="Search by email (Customer), book or status order" value="{{ request()->search }}">
            <div class=" mx-4 align-self-center">
                <select name="action" id="action" class="form-control">
                    <option value="">--ALL --</option>
                    <option value="SUBMIT" {{ request()->action == 'SUBMIT' ? 'selected' : '' }}>SUBMIT</option>
                    <option value="PROCESS" {{ request()->action == 'PROCESS' ? 'selected' : '' }}>PROCESS</option>
                    <option value="FINISH" {{ request()->action == 'FINISH' ? 'selected' : '' }}>FINISH</option>
                    <option value="CANCEL" {{ request()->action == 'CANCEL' ? 'selected' : '' }}>CANCEL</option>
                </select>
            </div>

            <button class="btn btn-primary mr-1" type="submit" id="button-addon2">Filter</button>
            <a href="{{ route('orders.index') }}" class="btn btn-dark ml-2" id="button-addon2">Reset</a>
        </div>
    </form>
</div>
<div class="col-md-12 my-4">
    <a href="{{ route('orders.create') }}" class="btn btn-blue btn-block float-right">Create Order</a>
</div>

<div class="col-12">
    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">No.</th>
                <th scope="col">Invoice number</th>
                <th scope="col">Status</th>
                <th scope="col">Customer</th>
                <th scope="col">Total quantity</th>
                <th scope="col">Order date</th>
                <th scope="col">Total price</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $order)
            <tr>
                <th scope="row">{{ (($orders->currentPage() * 10) - 10) + $loop->iteration }}</th>
                <td>{{ $order->invoice_number }}</td>
                <td>
                    @if ($order->status == 'SUBMIT')
                    <span class="text-monospace fw-bold badge-pill badge-warning">{{ $order->status }}</span>
                    @elseif($order->status == 'PROCESS')
                    <span class="text-monospace fw-bold badge-pill badge-success">{{ $order->status }}</span>
                    @elseif($order->status == 'FINISH')
                    <span class="text-monospace fw-bold badge-pill badge-primary">{{ $order->status }}</span>
                    @elseif($order->status == 'CANCEL')
                    <span class="text-monospace fw-bold badge-pill badge-danger">{{ $order->status }}</span>
                    @endif
                </td>
                <td>
                    <p class="fw-bold">{{ $order->user->name }}</p>
                    <small>{{ $order->user->email }}</small>
                </td>
                <td> <b>{{ $order->total_quantity }}</b> pc(s)</td>
                <td>{{ $order->created_at->format('l, d-F-Y') }}</td>
                <td>{{ $order->total_price }}</td>
                <td>
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-success btn-sm my-1">Detail</a>
                    <a href="{{ route('orders.edit', $order) }}" class="btn btn-warning btn-sm my-1">Edit</a>
                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                        data-target="#deleteOrder{{ $order->invoice_number }}Modal">Delete</a>
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
                                <p>Are you sure delete order {{ $order->name }} permanently ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <form action="{{ route('orders.destroy', $order) }}" method="POST">
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
                    <p class="font-weight-bold text-center text-monospace">{{ 'Orders not available' }}</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $orders->withQueryString()->onEachSide(2)->links() }}
    </div>
</div>
@endsection
