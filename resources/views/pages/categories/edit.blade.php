@extends('layouts.global')

@section('title', 'Edit Category')

@section('pageTitle', 'Edit Category')

@section('content')

<div class="col-md-8">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('categories.index') }}" class="btn btn-link btn-outline-primary float-right">Back</a>
        </div>
    </div>
    <form action="{{ route('categories.update', $category) }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow-sm p-3" id="myfr">
        @csrf
        @method('PATCH')

        @include('categories.partials.form',compact($category))

        <button type="submit" class="btn btn-primary btn-block" id="btnfr">Edit</button>
    </form>
</div>
@endsection
