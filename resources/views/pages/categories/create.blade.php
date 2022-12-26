@extends('layouts.global')

@section('title', 'Create Category')

@section('pageTitle', 'Tambah Category')

@section('content')
    <div class="col-md-8">
        <div class="row">
            <div class="col-12">
                <a href="{{ route('categories.index') }}" class="btn btn-link btn-outline-primary float-right">Back</a>
            </div>
        </div>
        <form action="{{ route('categories.store') }}" method="post" enctype="multipart/form-data"
            class="bg-white shadow-sm p-3" id="myfr">
            @csrf

            @include('categories.partials.form')

            <button type="submit" class="btn btn-primary btn-block" id="btnfr">Create</button>
        </form>
    </div>
@endsection
