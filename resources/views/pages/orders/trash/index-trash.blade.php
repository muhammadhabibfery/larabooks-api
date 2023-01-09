@extends('layouts.global')

@section('title', 'List Of Deleted Orders')

@section('pageTitle', 'Daftar Order Terhapus')

@section('content')
<x-orders.order-search-bar :route="route('orders.trash')" :status="$status" type="trash" />

<x-orders.order-list :orders="$orders" type="trash" />
@endsection

@push('my_styles')
@include('pages.includes._select2-style')
@endpush

@push('my_scripts')
@include('pages.orders.includes._select2-orders-script')
@endpush
