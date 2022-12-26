@extends('layouts.global')

@section('title', 'Trashed Categories')

@section('pageTitle', 'Trashed Categories')

@section('content')
    <div class="col-md-8">
        <form action="{{ route('categories.trash') }}" method="get">
            {{-- @csrf --}}

            <div class="input-group mb-3">
                <input type="text" name="search" class="form-control" placeholder="Search by name"
                    aria-label="Recipient's username" aria-describedby="button-addon2" value="{{ request()->search }}">
                <div class="input-group-append">
                    <button class="btn btn-primary mr-4" type="submit" id="button-addon2">Search</button>
                </div>
                <a href="{{ route('categories.trash') }}" class="btn btn-dark btn-lg" id="button-addon2">Reset</a>
            </div>
        </form>
    </div>
    <div class="col-md-4 text-right">
        <a href="{{ route('categories.index') }}" class="btn btn-link btn-outline-primary btn-lg mr-2">Back</a>
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
                @forelse ($deleted_categories as $deleted_category)
                    <tr>
                        <th scope="row">{{ $loop->iteration }}</th>
                        <td>{{ $deleted_category->name }}</td>
                        <td>{{ $deleted_category->slug }}</td>
                        <td class="w-25">
                            <img src="{{ $deleted_category->getCategoryImage() }}" alt="Category image"
                                class="img-fluid rounded-circle img-thumbnail w-25">
                        </td>
                        <td>
                            <a href="{{ route('categories.restore', $deleted_category->slug) }}"
                                class="btn btn-success btn-sm my-1"
                                onclick="return confirm('Are you sure restore category {{ $deleted_category->name }} ?')">Restore</a>

                            <a href="#" class="btn btn-danger btn-sm" data-toggle="modal"
                                data-target="#deleteCategory{{ $deleted_category->slug }}Modal">Delete</a>
                        </td>

                        {{-- Delete Permanently Category Modal --}}
                        <div class="modal fade" id="deleteCategory{{ $deleted_category->slug }}Modal" tabindex="-1"
                            role="dialog" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">Delete permanently category</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Are you sure delete category {{ $deleted_category->name }} permanently ?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                        <form action="{{ route('categories.force-delete', $deleted_category->slug) }}"
                                            method="POST" id="myfr">
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
                        <td colspan="5">
                            <p class="font-weight-bold text-center text-monospace">{{ 'Categories not available in trash' }}</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ $deleted_categories->withQueryString()->onEachSide(2)->links() }}
        </div>
    </div>
@endsection
