<div id="app">
    {{-- <div class="app-loader">
        <img class="object-fit-contain img-fluid" src="{{ asset('assets/images/preloader.gif') }}" alt="loading..."
            width="200" height="200">
    </div> --}}
    @include('sections.header')
    <main class="app-main" id="main-content" role="main">
        @yield('content')
    </main>
    @include('sections.footer')
</div>
<div id="portals">
    @yield('modals')
</div>
