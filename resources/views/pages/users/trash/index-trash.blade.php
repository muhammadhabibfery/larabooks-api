@extends('layouts.global')

@section('title', 'Deleted Users List')

@section('pageTitle', 'Daftar User Terhapus')

@section('content')

<x-users.user-search-bar :route="route('users.trash')" />

<x-users.user-list :users="$users" :roles="$roles" type="trash" routeName="users.trash" />
@endsection
