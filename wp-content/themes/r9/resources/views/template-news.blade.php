{{--
  Template Name: News
--}}
@extends('layouts.app')

@section('content')
  @while (have_posts())
    @php(the_post())
    @include('partials.page-header')
    @foreach ($news as $item)
      <x-news id="{{ $item }}"></x-news>
    @endforeach
  @endwhile
@endsection
