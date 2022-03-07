@include('template.header')

@if ($showNavigation ?? true)
    @include('template.nav')
@endif
<!-- Page -->
@if ($showNavigation ?? true)
    <div class="page">
        @yield('page')
    </div>
@else
    <div class="page" style="margin-left: 0px">
        <div class="page-content container-fluid">
            @yield('page')
        </div>
    </div>
@endif
<!-- End Page -->
@include('template.footer')
