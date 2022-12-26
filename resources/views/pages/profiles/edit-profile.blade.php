@extends('layouts.global')

@section('title', 'Edit Profile')

@section('pageTitle', 'Edit Profil')

@section('content')
<div class="col-md-8">
    <div class="row">
        <div class="col-12">
            <a href="{{ checkRole(['CUSTOMER'], $user->role) ? route('home') : route('dashboard') }}"
                class="btn btn-link btn-outline-primary float-right">Back</a>
        </div>
    </div>
    <form action="{{ route('profiles.update', $user) }}" method="POST" enctype="multipart/form-data"
        class="bg-white shadow-sm p-3" id="myfr">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="name">Name</label>
            <input class="form-control @error('name') is-invalid @enderror" type="text" name="name" id="name"
                value="{{ old('name', $user->name) }}">
            @error('name')
            <small class="fw-bold invalid-feedback">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input class="form-control @error('email') is-invalid @enderror" type="text" name="email" id="email"
                value="{{ old('email', $user->email) }}">
            @error('email')
            <small class="fw-bold invalid-feedback">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="username">Username</label>
            <input class="form-control" type="text" name="username" id="username" value="{{ $user->username }}"
                disabled>
        </div>
        <div class="form-group">
            <label for="phone">Phone number</label>
            <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror"
                value="{{ old('phone', $user->phone) }}">
            @error('phone')
            <small class="fw-bold invalid-feedback">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="address">Address</label>
            <textarea name="address" id="address"
                class="form-control @error('address') is-invalid @enderror">{{ old('address', $user->address) }}</textarea>
            @error('address')
            <small class="fw-bold invalid-feedback">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <div class="row">
                <div class="col-4">
                    <p>Current Avatar</p>
                    <img src="{{ $user->getAvatar() }}" alt="avatar user"
                        class="img-thumbnail img-fluid rounded-circle w-50">
                </div>
                <div class="col-8 my-auto">
                    <label for="image">Avatar image</label>
                    <input id="image" name="image" type="file"
                        class="form-control @error('image') is-invalid @enderror">
                    @error('image')
                    <small class="fw-bold invalid-feedback">{{ $message }}</small>
                    @enderror
                </div>
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-block" id="btnfr">Edit</button>
    </form>
</div>
@endsection
