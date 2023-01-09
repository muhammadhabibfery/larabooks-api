@extends('layouts.global')

@section('title', 'Edit Order')

@section('pageTitle', 'Edit Order')

@section('content')

<div class="col-md-8">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('orders.index') }}" class="btn btn-link btn-outline-primary float-right">Back</a>
        </div>
    </div>
    <form action="{{ route('orders.update', $order) }}" method="POST" class="bg-white shadow-sm p-3" id="myfr">
        @csrf
        @method('PATCH')
        <div class="form-group">
            <label for="invoice_number">Invoice number</label>
            <input class="form-control" type="text" id="invoice_number" value="{{ $order->invoice_number }}" disabled>
        </div>
        <div class="form-group">
            <label for="buyer">Buyer</label>
            <input class="form-control" type="text" id="buyer" value="{{ $order->user->name }}" disabled>
        </div>
        <div class="form-group">
            <label for="created_at">Buyer</label>
            <input class="form-control" type="text" id="created_at"
                value="{{ transformDateFormat($order->created_at) }}" disabled>
        </div>
        <div class="form-group">
            <label for="created_at">Total Book(s) {{ $order->totalQuantity }}</label>
            <ul>
                @foreach ($order->Books as $book)
                <li>{{ $book->title }} : <b>{{ $book->pivot->quantity }}</b></li>
                @endforeach
            </ul>
        </div>
        <div class="form-group">
            <label for="total_price">Total price</label>
            <input class="form-control price" type="text" id="total_price" value="{{ $order->total_price }}" disabled>
        </div>
        <div class="form-group">
            <label for="status">Status</label>
            <select name="status" id="status" class="form-control @error('status') is-invalid @enderror">
                @foreach ($status as $s)
                <option value="{{ $s }}" {{ $order->status == $s ? 'selected' : '' }}>{{ $s }}</option>
                @endforeach
            </select>
            @error('status')
            <small class="fw-bold text-danger">{{ $message }}</small>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary btn-block" id="btnfr">Edit</button>
    </form>
</div>
@endsection

@push('my_scripts')
@include('pages.includes._jquery-mask')
@endpush
