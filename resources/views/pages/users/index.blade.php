@extends('layouts.global')

@section('title', 'Users List')

@section('pageTitle', 'Daftar User')

@section('content')

<x-users.user-search-bar :route="route('users.index')" />

<div class="col-md-12 my-4">
    <a href="{{ route('users.create') }}" class="btn btn-blue btn-block float-right">Create User</a>
</div>
<div class="col-md-12 mb-4">
    <a href="{{ route('users.trash') }}" class="btn btn-orange btn-block float-right text-white">Deleted Users</a>
</div>

<x-users.user-list :users="$users" :roles="$roles" type="index" routeName="users.index" />
@endsection
