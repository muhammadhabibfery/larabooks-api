@extends('layouts.global')

@section('title', 'List Of Category')

@section('pageTitle', 'Daftar Kategory')

@section('content')
<x-categories.category-search-bar :route="route('categories.index')" type="index" />

<div class="col-md-12 my-4">
    <a href="{{ route('categories.create') }}" class="btn btn-blue btn-block float-right">Create Category</a>
</div>

@isAdmin(auth()->user()->role)
<div class="col-md-12 mb-4">
    <a href="{{ route('categories.trash') }}" class="btn btn-orange btn-block text-white float-right">Deleted
        Categories</a>
</div>
@endif

<x-categories.category-list :categories="$categories" type="index" />
@endsection
