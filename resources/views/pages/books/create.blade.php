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
        <form action="{{ route('books.store') }}" method="POST" enctype="multipart/form-data" class="bg-white shadow-sm p-3"
            id="myfr">
            @csrf

            @include('books.partials.form')

            <button type="submit" class="btn btn-primary btn-block" id="btnfr">Create</button>
        </form>
    </div>
@endsection

@push('my_styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet">
@endpush

@push('my_scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.categories').select2({
                placeholder: '-- Select Categories --',
            });
        });

    </script>
@endpush
