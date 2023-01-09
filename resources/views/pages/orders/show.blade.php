@extends('layouts.global')

@section('title', 'Detail Order')

@section('pageTitle', 'Detail Order')

@section('content')
<x-orders.order-detail :order="$order" :route="route('orders.index')" />
@endsection
