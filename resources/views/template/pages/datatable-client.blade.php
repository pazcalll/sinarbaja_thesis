@php
$showNavigation = false;
$bodyType = 'site-menubar-unfold';
@endphp

@extends('app')

@section('css')
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/page-base/examples/css/tables/datatable.css') }}">

    @yield('style')
@endsection

@section('page')
    <div class="page-content">
        <div class="panel">
            @yield('header-tabel')
            <div class="panel-body">
                @yield('top-panel')
                @yield('table')
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Page -->
    <script src="{{ asset('public/themeforest/global/js/Plugin/datatables.js') }}"></script>

    <script src="{{ asset('public/themeforest/page-base/examples/js/tables/datatable.js') }}"></script>
    <script src="{{ asset('public/themeforest/page-base/examples/js/uikit/icon.js') }}"></script>

    @yield('script')
@endsection
