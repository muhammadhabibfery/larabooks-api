@extends('layouts.global')

@section('title', 'Detail Category')

@section('pageTitle', 'Detail Kategori')

@section('content')

<div class="col-md-10">
    <x-categories.category-detail :category="$category" :route="route('categories.index')" />
</div>
@endsection
