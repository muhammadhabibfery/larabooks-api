@extends('layouts.global')

@section('title', 'Detail Category')

@section('pageTitle', 'Detail Kategori')

@section('content')

<div class="col-md-8">

    <div class="card mb-3">
        <div class="row no-gutters">
            <div class="col-md-4 align-self-center">
                <img src="{{ $category->getCategoryImage() }}" class="card-img" alt="Category image">
            </div>
            <div class="col-md-8">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h5 class="card-title">Category name</h5>
                            <p class="card-text">{{ $category->name }}</p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <p class="card-text d-inline-block"><small class="text-muted">Last updated
                                {{ $category->created_at->diffForHumans() }}</small></p>
                        <a href="{{ route('categories.index') }}"
                            class="btn btn-link btn-outline-primary float-right">Back</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
@endsection
