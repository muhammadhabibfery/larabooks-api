@extends('layouts.global')

@section('title', 'Detail Deleted User')

@section('pageTitle', 'Detail User Terhapus')

@section('content')
<x-users.user-detail :user="$user" :route="route('users.trash')" />
@endsection
