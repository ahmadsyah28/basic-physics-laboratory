{{-- resources/views/home.blade.php --}}
@extends('layouts.app')
@section('title', 'Beranda - Laboratorium Fisika Dasar')
@section('content')
    @include('components.hero')
    @include('components.about')
    @include('components.articles')
    @include('components.gallery')
@endsection
