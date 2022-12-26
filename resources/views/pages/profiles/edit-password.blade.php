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
    <form action="{{ route('profiles.password.update', $user) }}" method="POST" class="bg-white shadow-sm p-3"
        id="myfr">
        @csrf
        @method('PATCH')

        <div class="form-group">
            <label for="current_password">Password saat ini</label>
            <input class="form-control @error('current_password') is-invalid @enderror" type="password"
                name="current_password" id="current_password">
            @error('current_password')
            <small class="fw-bold invalid-feedback">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="new_password">Password baru</label>
            <input class="form-control @error('new_password') is-invalid @enderror" type="password" name="new_password"
                id="new_password">
            @error('new_password')
            <small class="fw-bold invalid-feedback">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="new_password_confirmation">Konfirmasi Password</label>
            <input class="form-control @error('new_password_confirmation') is-invalid @enderror" type="password"
                name="new_password_confirmation" id="new_password_confirmation">
            @error('new_password_confirmation')
            <small class="fw-bold invalid-feedback">{{ $message }}</small>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-block" id="btnfr">Edit</button>
    </form>
</div>
@endsection
