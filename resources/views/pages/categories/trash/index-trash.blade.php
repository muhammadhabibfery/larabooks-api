@extends('layouts.global')

@section('title', 'List Of Deleted Category')

@section('pageTitle', 'Daftar Kategory Terhapus')

@section('content')
<x-categories.category-search-bar :route="route('categories.trash')" type="trash" />

<x-categories.category-list :categories="$categories" type="trash" />
@endsection
