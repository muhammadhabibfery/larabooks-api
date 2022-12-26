@extends('layouts.global')

@section('title', 'Trashed Books')

@section('pageTitle', 'Trashed Books')

@section('content')
<div class="col-md-8">
    <form action="{{ route('books.trash') }}" method="get">
        {{-- @csrf --}}

        <div class="input-group mb-3">
            <input type="text" name="search" class="form-control"
                placeholder="Search by title, author, publisher, or categories" aria-label="Recipient's username"
                aria-describedby="button-addon2" value="{{ request()->search }}">
            <div class="input-group-append">
                <button class="btn btn-primary mr-4" type="submit" id="button-addon2">Search</button>
            </div>
            <a href="{{ route('books.trash') }}" class="btn btn-dark btn-lg" id="button-addon2">Reset</a>
        </div>
    </form>
</div>
<div class="col-md-4 text-right">
    <a href="{{ route('books.index') }}" class="btn btn-link btn-outline-primary btn-lg mr-2">Back</a>
</div>

<div class="row my-3">
    {{-- <div class="col-md-6"></div> --}}
    <div class="col">
        <ul class="nav nav-pills card-header-pills pl-4">
            <li class="nav-item">
                <a class="nav-link {{ request()->action == null && request()->is('books/trash') ? 'active' : '' }}"
                    href="{{route('books.trash')}}">All</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->action == 'publish' ? 'active' : '' }}"
                    href="{{ route('books.trash', ['action' => 'publish', 'search' => request()->search])}}">Publish</a>
            </li>
            <li class="nav-item">
                <a class="nav-link {{ request()->action == 'draft' ? 'active' : '' }}"
                    href="{{route('books.trash', ['action' => 'draft', 'search' => request()->search])}}">Draft</a>
            </li>
        </ul>
    </div>
</div>
<hr class="my-2">

<div class="col-12">
    <table class="table table-hover mt-2">
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
            @forelse ($deleted_books as $deleted_book)
            <tr>
                <th scope="row">{{ $loop->iteration }}</th>
                <td class="w-25">
                    <img src="{{ $deleted_book->getCoverBook() }}" alt="Cover book"
                        class="img-fluid rounded-circle img-thumbnail w-25">
                </td>
                <td>{{ $deleted_book->title }}</td>
                <td>{{ $deleted_book->author }}</td>
                <td>
                    <span class="{{ $deleted_book->status == 'PUBLISH' ? 'text-blue' : 'text-danger' }} text-monospace">
                        {{ $deleted_book->status }}
                    </span>
                </td>
                <td>
                    <ul>
                        @forelse ($deleted_book->categories as $categories)
                        <li>{{ $categories->name }}</li>
                        @empty
                        <span class="fw-bold"> - </span>
                        @endforelse
                    </ul>
                </td>
                <td>{{ $deleted_book->stock }}</td>
                <td>{{ $deleted_book->price }}</td>
                <td>
                    <a href="{{ route('books.restore', $deleted_book->slug) }}" class="btn btn-success btn-sm my-1"
                        onclick="return confirm('Are you sure restore book {{ $deleted_book->title }} ?')">Restore</a>

                    <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                        data-target="#deleteBook{{ $deleted_book->slug }}Modal">Delete</a>
                </td>

                {{-- Delete Permanently Book Modal --}}
                <div class="modal fade" id="deleteBook{{ $deleted_book->slug }}Modal" tabindex="-1" role="dialog"
                    aria-labelledby="deleteBookModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Delete permanently book</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure delete book {{ $deleted_book->title }} permanently ?</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                <form action="{{ route('books.force-delete', $deleted_book->slug) }}" method="POST" id="myfr">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-primary" id="btnfr">Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </tr>
            @empty
            <tr>
                <td colspan="9">
                    <p class="font-weight-bold text-center text-monospace">{{ 'Books not available in trash' }}</p>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $deleted_books->withQueryString()->onEachSide(2)->links() }}
    </div>
</div>
@endsection
