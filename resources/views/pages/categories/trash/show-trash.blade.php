@extends('layouts.global')

@section('title', 'Detail Deleted Category')

@section('pageTitle', 'Detail Kategori Tehapus')

@section('content')

<div class="col-md-10">
    <x-categories.category-detail :category="$category" :route="route('categories.trash')" />
</div>
@endsection
