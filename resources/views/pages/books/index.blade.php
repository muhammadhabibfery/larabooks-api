@extends('layouts.global')

@section('title', 'List of books')

@section('pageTitle', 'Daftar Buku')

@section('content')
<x-books.book-search-bar :route="route('books.index')" type="index" />

<div class="col-md-12 my-4">
    <a href="{{ route('books.create') }}" class="btn btn-blue btn-block float-right">Create Book</a>
</div>

@isAdmin(auth()->user()->role)
<div class="col-md-12 mb-4">
    <a href="{{ route('books.trash') }}" class="btn btn-orange btn-block text-white float-right">Deleted Books</a>
</div>
@endif

<x-books.book-list :books="$books" :status="$status" type="index" routeName="books.index" />
@endsection
