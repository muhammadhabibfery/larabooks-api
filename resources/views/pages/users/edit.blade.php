@extends('layouts.global')

@section('title', 'Edit User')

@section('pageTitle', 'Edit User')

@section('content')
<div class="col-md-8">
    <div class="row">
        <div class="col-12">
            <a href="{{ route('users.index') }}" class="btn btn-link btn-outline-primary float-right">Back</a>
        </div>
    </div>

    <x-users.user-form :route="route('users.update', $user)" :user="$user" :roles="$roles" />
</div>
@endsection
