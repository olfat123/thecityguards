{{--
  Template Name: Careers
--}}
@extends('layouts.app')

@section('content')
    @while (have_posts())
        @php(the_post())
        @include('partials.page-header')
        @foreach ($vacancies as $item)
            <x-vacancy id="{{ $item }}"></x-vacancy>
        @endforeach
    @endwhile
@endsection
