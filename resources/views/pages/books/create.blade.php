@extends('layouts.global')

@section('title', 'Create Book')

@section('pageTitle', 'Tambah Buku')

@section('content')
<div class="col-md-8">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('books.index') }}" class="btn btn-link btn-outline-primary float-right">Back</a>
        </div>
    </div>

    <x-books.book-form :route="route('books.store')" :categories="$categories" :status="$status" :cities="$cities"
        :book="null" />
</div>
@endsection

@push('my_styles')
@include('pages.includes._select2-style')
@endpush

@push('my_scripts')
@include('pages.books.includes._select2-books-script')
@include('pages.includes._jquery-mask')
@endpush
