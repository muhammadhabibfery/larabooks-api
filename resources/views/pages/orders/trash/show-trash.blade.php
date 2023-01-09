@extends('layouts.global')

@section('title', 'Detail Deleted Order')

@section('pageTitle', 'Detail Order Terhapus')

@section('content')
<x-orders.order-detail :order="$order" :route="route('orders.trash')" />
@endsection
