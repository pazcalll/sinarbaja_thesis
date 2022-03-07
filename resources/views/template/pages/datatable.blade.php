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
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/bootstrap-sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/toastr/toastr.css') }}">
    @yield('style')
@endsection

@section('page')
    <div class="page-header">
        <h1 class="page-title"> {{ $page }} </h1>
        <ol class="breadcrumb">
            @foreach ($breadcumbs as $i => $item)
                <li class="breadcrumb-item {{ $item['active'] }}">
                    @if ($item['active'] == 'active')
                        {{ $item['nama'] }}
                    @else
                        <a href="{{ $item['link'] }}">{{ $item['nama'] }}</a>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>

    <div class="page-content">
        <div class="panel">
            @yield('header-tabel')
            <div class="panel-body">
                <div class="example">
                    @yield('top-panel')
                </div>
                <div class="table-responsive">
                    @yield('table')
                </div>

                <div class="example">
                    @yield('bottom-panel')
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <!-- Page -->
    <script src="{{ asset('public/themeforest/global/js/Plugin/datatables.js') }}"></script>

    <script src="{{ asset('public/themeforest/page-base/examples/js/tables/datatable.js') }}"></script>
    <script src="{{ asset('public/themeforest/page-base/examples/js/uikit/icon.js') }}"></script>

    <script src="{{ asset('public/themeforest/global/vendor/bootbox/bootbox.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/bootstrap-sweetalert/sweetalert.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/toastr/toastr.js') }}"></script>

    <script src="{{ asset('public/themeforest/global/js/Plugin/bootbox.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/js/Plugin/bootstrap-sweetalert.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/js/Plugin/toastr.js') }}"></script>

    <script src="{{ asset('public/themeforest/page-base/examples/js/advanced/bootbox-sweetalert.js') }}"></script>

    <script>
        function showSwalAlert({
            element: element,
            title: title,
            text: text,
            type: type,
            callback: callback
        }) {
            element.on("click", function() {
                swal({
                    title: title,
                    text: text,
                    type: type,
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: 'Ya',
                    closeOnConfirm: false, //closeOnCancel: false
                    cancelButtonText: 'Batal',
                }, function() {
                    if (callback != null) {
                        callback()
                    }
                })
            })
        }

    </script>

    @yield('script')
@endsection
