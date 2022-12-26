@extends('layouts.global')

@section('title', 'Detail Book')

@section('pageTitle', 'Detail Buku')

@section('content')

<div class="col-md-8">

    <div class="card mb-3">
        <div class="row no-gutters">
            <div class="col-md-4 align-self-center">
                <img src="{{ $book->getCoverBook() }}" class="card-img" alt="avatar book">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <h5 class="card-title">Book title</h5>
                            <p class="card-text">{{ $book->title }}</p>
                            <h5 class="card-title">Description</h5>
                            <p class="card-text">{{ $book->description }}</p>
                            <h5 class="card-title">Book author</h5>
                            <p class="card-text">{{ $book->author }}</p>
                            <h5 class="card-title">Book publisher</h5>
                            <p class="card-text">{{ $book->publisher }}</p>
                        </div>
                        <div class="col-6">
                            <h5 class="card-title">Book Categories</h5>
                            <ul>
                                @foreach ($book->categories as $book_category)
                                <li>{{ $book_category->name }}</li>
                                @endforeach
                            </ul>
                            <h5 class="card-title">Book price</h5>
                            <p class="card-text">{{ $book->price }}</p>
                            <h5 class="card-title">Book stock</h5>
                            <p class="card-text">{{ $book->stock }}</p>
                            <h5 class="card-title">Book status</h5>
                            <p
                                class="card-text text-monospace {{ $book->status == 'PUBLISH' ? 'text-blue' : 'text-danger' }}">
                                {{ $book->status }}
                            </p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="card-text d-inline-block">
                            <small class="text-muted">Last updated {{ $book->created_at->diffForHumans() }}</small>
                            <span class="ml-4 mr-1 oi oi-eye"></span>
                            {{ $book->views }}
                        </p>
                        <a href="{{ route('books.index') }}"
                            class="btn btn-link btn-outline-primary float-right">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
