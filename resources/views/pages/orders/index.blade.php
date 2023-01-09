@extends('layouts.global')

@section('title', 'List Of Orders')

@section('pageTitle', 'Daftar Order')

@section('content')
<x-orders.order-search-bar :route="route('orders.index')" :status="$status" type="index" />

@isAdmin(auth()->user()->role)
<div class="col-md-12 mt-1 mb-5">
    <a href="{{ route('orders.trash') }}" class="btn btn-orange btn-block text-white float-right">Deleted Orders</a>
</div>
@endif

<x-orders.order-list :orders="$orders" type="index" />
@endsection

@push('my_styles')
@include('pages.includes._select2-style')
@endpush

@push('my_scripts')
@include('pages.orders.includes._select2-orders-script')
@endpush
