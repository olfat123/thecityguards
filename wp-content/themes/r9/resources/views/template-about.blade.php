{{--
  Template Name: About
--}}
@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php(the_post())
        @include('partials.page-header')
    @endwhile
@endsection
@section('modals')
@endsection
