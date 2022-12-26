@extends('layouts.global')

@section('title', 'Create User')

@section('pageTitle', 'Tambah User')

@section('content')

<div class="col-md-10">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('users.index') }}" class="btn btn-link btn-outline-primary float-right">Back</a>
        </div>
    </div>

    <x-users.user-form :route="route('users.store')" :user="null" :roles="$roles" />
</div>
@endsection
