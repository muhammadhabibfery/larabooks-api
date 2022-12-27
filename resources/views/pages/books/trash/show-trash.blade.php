@extends('layouts.global')

@section('title', 'Detail Book')

@section('pageTitle', 'Detail Buku')

@section('content')
<x-books.book-detail :book="$book" :route="route('books.trash')" />
@endsection
