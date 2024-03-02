@extends('layouts.app')

@section('content')
  @while (have_posts())
    @php(the_post())
    @include('partials.page-header')
    @if (sizeof($related_news))
      @foreach ($related_news as $item)
        <x-news id="{{ $item }}"></x-news>
      @endforeach
    @endif
  @endwhile
@endsection
