@extends('layouts.global')

@section('title', 'List of books')

@section('pageTitle', 'Daftar Buku')

@section('content')
<x-books.book-search-bar :route="route('books.trash')" type="trash" />

<x-books.book-list :books="$books" :status="$status" type="trash" routeName="books.trash" />
@endsection
