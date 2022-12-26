@extends('layouts.global')

@section('title', 'Categories List')

@section('pageTitle', 'Daftar Kategory')

@section('content')
    <div class="col-md-8">
        <form action="{{ route('categories.index') }}" method="get">
            {{-- @csrf --}}

            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Search by name"
                    aria-label="Recipient's username" aria-describedby="button-addon2" value="{{ request()->search }}">
                <div class="input-group-append">
                    <button class="btn btn-primary mr-4" type="submit" id="button-addon2">Search</button>
                </div>
                <a href="{{ route('categories.index') }}" class="btn btn-dark btn-lg" id="button-addon2">Reset</a>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-right">
        <a href="{{ route('categories.trash') }}" class="btn btn-danger btn-lg mr-2">Trash</a>
    </div>
    <div class="col-md-12 my-4">
        <a href="{{ route('categories.create') }}" class="btn btn-blue btn-block float-right">Create Category</a>
    </div>

    <div class="col-12">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th scope="col">No.</th>
                    <th scope="col">Name</th>
                    <th scope="col">Slug</th>
                    <th scope="col">Category Image</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <th scope="row">{{ $categories->currentPage() * 10 - 10 + $loop->iteration }}</th>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->slug }}</td>
                        <td class="w-25">
                            <img src="{{ $category->getCategoryImage() }}" alt="Category image"
                                class="img-fluid rounded-circle img-thumbnail w-25">
                        </td>
                        <td>
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-success btn-sm my-1">Detail</a>
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning btn-sm my-1">Edit</a>
                            <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteCategory{{ $category->slug }}Modal">Soft Delete</a>
                        </td>

                        {{-- Soft Delete Category Modal --}}
                        <div class="modal fade" id="deleteCategory{{ $category->slug }}Modal" tabindex="-1" role="dialog"
                            aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Move Category To Trash</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure move category {{ $category->name }} to trash ?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <form action="{{ route('categories.destroy', $category) }}" method="POST" id="myfr">
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
                        <td colspan="5">
                            <p class="font-weight-bold text-center text-monospace">{{ 'Categories not available' }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $categories->withQueryString()->onEachSide(2)->links() }}
        </div>
    </div>
@endsection
