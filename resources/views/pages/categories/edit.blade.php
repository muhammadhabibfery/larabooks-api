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

    <x-categories.category-form :route="route('categories.update', $category)" :category="$category" />
</div>
@endsection
