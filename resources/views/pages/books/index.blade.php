@extends('layouts.global')

@section('title', 'List of books')

@section('pageTitle', 'Daftar Buku')

@section('content')
    <div class="col-md-8">
        <form action="{{ route('books.index') }}" method="get">
            {{-- @csrf --}}

            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control"
                    placeholder="Search by title, author, publisher, or categories" aria-label="Recipient's username"
                    aria-describedby="button-addon2" value="{{ request()->search }}">
                <div class="input-group-append">
                    <button class="btn btn-primary mr-4" type="submit" id="button-addon2">Search</button>
                </div>
                <a href="{{ route('books.index') }}" class="btn btn-dark btn-lg" id="button-addon2">Reset</a>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-right">
        <a href="{{ route('books.trash') }}" class="btn btn-danger btn-lg mr-2">Trash</a>
    </div>

    <div class="row my-2">
        {{-- <div class="col-md-6"></div> --}}
        <div class="col">
            <ul class="nav nav-pills card-header-pills pl-4">
                <li class="nav-item">
                    <a class="nav-link {{ request()->action == null && request()->is('books') ? 'active' : '' }}"
                        href="{{ route('books.index') }}">All</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->action == 'publish' ? 'active' : '' }}"
                        href="{{ route('books.index', ['action' => 'publish', 'search' => request()->search]) }}">Publish</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->action == 'draft' ? 'active' : '' }}"
                        href="{{ route('books.index', ['action' => 'draft', 'search' => request()->search]) }}">Draft</a>
                </li>
            </ul>
        </div>
    </div>
    <hr class="my-3">

    <div class="col-md-12 my-4">
        <a href="{{ route('books.create') }}" class="btn btn-blue btn-block float-right">Create Book</a>
    </div>

    <div class="col-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Cover</th>
                    <th scope="col">Title</th>
                    <th scope="col">Author</th>
                    <th scope="col">Status</th>
                    <th scope="col">Categories</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Price</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($books as $book)
                    <tr>
                        <th scope="row">{{ $books->currentPage() * 10 - 10 + $loop->iteration }}</th>
                        <td class="w-25">
                            <img src="{{ $book->getCoverBook() }}" alt="Cover book"
                                class="img-fluid rounded-circle img-thumbnail w-25">
                        </td>
                        <td>{{ $book->title }}</td>
                        <td>{{ $book->author }}</td>
                        <td>
                            <span class="{{ $book->status == 'PUBLISH' ? 'text-blue' : 'text-danger' }} text-monospace">
                                {{ $book->status }}
                            </span>
                        </td>
                        <td>
                            <ul>
                                @forelse ($book->categories as $categories)
                                    <li>{{ $categories->name }}</li>
                                @empty
                                    <span class="fw-bold"> - </span>
                                @endforelse
                            </ul>
                        </td>
                        <td>{{ $book->stock }}</td>
                        <td>{{ $book->price }}</td>
                        <td>
                            <a href="{{ route('books.show', $book) }}" class="btn btn-success btn-sm my-1">Detail</a>
                            <a href="{{ route('books.edit', $book) }}" class="btn btn-warning btn-sm my-1">Edit</a>
                            <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteBook{{ $book->slug }}Modal">Soft Delete</a>
                        </td>

                        {{-- Soft Delete Book Modal --}}
                        <div class="modal fade" id="deleteBook{{ $book->slug }}Modal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteBookModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Move Book To Trash</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure move book {{ $book->title }} to trash ?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <form action="{{ route('books.destroy', $book) }}" method="POST" id="myfr">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-primary" id="btnfr">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9">
                            <p class="font-weight-bold text-center text-monospace">{{ 'Books not available' }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $books->withQueryString()->onEachSide(2)->links() }}
        </div>
    </div>
@endsection
