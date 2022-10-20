<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap material admin template">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta http-equiv="cache-control" content="private, max-age=0, no-cache">
    <meta http-equiv="pragma" content="no-cache">
    <meta http-equiv="expires" content="0">

    <title>Thesis App</title>
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
    <link rel="apple-touch-icon" href="{{ asset('public/themeforest/page-base/images/apple-touch-icon.png') }}">
    
    <link rel="shortcut icon" href="{{ asset('public/themeforest/page-base/images/favicon.ico') }}">

    <link rel="stylesheet" href="{{ asset('public/themeforest/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/css/bootstrap-extend.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/page-base/css/site.min.css') }}">

    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/animsition/animsition.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/asscrollable/asScrollable.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/switchery/switchery.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/intro-js/introjs.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/slidepanel/slidePanel.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/flag-icon-css/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/waves/waves.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/chartist/chartist.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/chartist-plugin-tooltip/chartist-plugin-tooltip.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/select2/select2.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/bootstrap-tokenfield/bootstrap-tokenfield.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/bootstrap-tagsinput/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/bootstrap-select/bootstrap-select.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/icheck/icheck.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/switchery/switchery.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/asrange/asRange.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/ionrangeslider/ionrangeslider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/asspinner/asSpinner.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/clockpicker/clockpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/ascolorpicker/asColorPicker.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/bootstrap-touchspin/bootstrap-touchspin.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/jquery-labelauty/jquery-labelauty.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/bootstrap-datepicker/bootstrap-datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/bootstrap-maxlength/bootstrap-maxlength.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/timepicker/jquery-timepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/jquery-strength/jquery-strength.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/multi-select/multi-select.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/typeahead-js/typeahead.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/blueimp-file-upload/jquery.fileupload.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/dropify/dropify.css') }}">

    <link rel="stylesheet" href="{{ asset('public/themeforest/page-base/examples/css/dashboard/v1.css') }}">
    <script type="module" src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@5.5.2/dist/ionicons/ionicons.js"></script>
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/formvalidation/formValidation.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/page-base//examples/css/forms/validation.css') }}">

    <link rel="stylesheet" href="{{ asset('public/themeforest/page-base/examples/css/forms/advanced.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/toastr/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/page-base/examples/css/advanced/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/webui-popover/webui-popover.css') }}">
    
    @yield('css')

    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/fonts/material-design/material-design.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/fonts/brand-icons/brand-icons.min.css') }}">
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>

    <!-- Scripts -->
    <script src="{{ asset('public/themeforest/global/vendor/breakpoints/breakpoints.js') }}"></script>

    <script>
        Breakpoints();
    </script>
</head>

<body class="animation {{ $bodyType ?? 'dashboard' }}">
    <nav class="site-navbar navbar navbar-default navbar-fixed-top navbar-mega" role="navigation">
        <a class="navbar-header" style="color: #FFFFFF;" href="{{ url('/') }}">
            <strong class="" style="margin-left: 10%; padding: 10%;">
                SINARBAJA
            </strong>
        </a>

        <div class="navbar-container container-fluid">
            <!-- Navbar Collapse -->
            <div class="navbar-collapse navbar-collapse-toolbar" id="site-navbar-collapse">

                <!-- Navbar Toolbar Right -->
                <ul class="nav navbar-toolbar navbar-right navbar-toolbar-right">
                    @if (url()->current() === url('/'))
                        <li class="nav-item">
                            <a class="nav-link icon md-search" data-toggle="collapse" href="#" data-target="#site-navbar-search" role="button">
                                <span class="sr-only">Toggle Search</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::check())
                        @if (Auth::user()->id_group == 1)
                            <li class="nav-item dropdown">
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
                                    <div class="dropdown-menu-header">
                                        <h5><b>Notifikasi</b></h5>
                                    </div>
                                    <div class="list-group scrollable is-enabled scrollable-vertical" style="position: relative;">
                                        <div data-role="container" class="scrollable-container" style="height: 270px; width: 358px;">
                                            <div class="row d-flex justify-content-start scrollable-content" style="width: auto; " id="notifikasi">
                                            </div>
                                        </div>
                                        <div class="scrollable-bar scrollable-bar-vertical scrollable-bar-hide" draggable="false">
                                            <div class="scrollable-bar-handle" style="height: 198.151px; transform: translate3d(0px, 0px, 0px);"></div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        @else

                            <li class="nav-item dropdown">
                                <a class="nav-link waves-effect waves-light waves-round" data-toggle="dropdown" href="javascript:void(0)" aria-expanded="false" data-animation="scale-up" role="button">
                                    <i class="icon md-shopping-cart" aria-hidden="true"></i>
                                    <span id="keranjang_count" class="badge badge-pill badge-danger up badge-cart"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-right dropdown-menu-media" role="menu">
                                    <div class="dropdown-menu-header" style="background:#3498db">
                                        <h5 class="text-white"><b>KERANJANG SAYA</b></h5>
                                        <span class="badge border border-white text-white" style="cursor: pointer; font-size: 15px; background:#2980b9" data-target="#validasiNotifikasi" data-toggle="modal" id="span-checkout">Checkout</span>
                                    </div>

                                    <div class="list-group">
                                        <div data-role="container" style="height: 270px; width: 358px;">
                                            <div data-role="content" id="products"></div>
                                        </div>
                                    </div>
                                    <div class="dropdown-menu-footer">
                                        <a class="dropdown-menu-footer-btn waves-effect waves-light waves-round" href="javascript:void(0)" role="button">
                                            <b><span id="nominal-total"></span></b>
                                        </a>
                                        <a class="dropdown-item waves-effect waves-light waves-round" href="javascript:void(0)" role="menuitem">
                                            Total Pesanan
                                        </a>
                                    </div>
                                </div>
                            </li>
                        @endif

                        <li class="nav-item dropdown">
                            <a class="nav-link navbar-avatar" data-toggle="dropdown" href="#" aria-expanded="false" data-animation="scale-up" role="button">
                            <span class="avatar avatar-online">
                                    <img src="{{ asset('public/themeforest/global/portraits/5.jpg') }}" alt="...">
                                    <i></i>
                            </span>
                            </a>
                            <div class="dropdown-menu" role="menu">
                                @if (Auth::user()->id_group != 1)
                                    <span class="username ml-4" style="color: blue">{{ Auth::user()->name }}</span>
                                    <a class="dropdown-item" href="{{ url('/profile') }}" role="menuitem"><i class="icon md-account" aria-hidden="true"></i> Profil</a>
                                    <a class="dropdown-item" href="{{ url('/order') }}" role="menuitem"><i class="icon md-inbox" aria-hidden="true"></i>Pesanan</a>
                                    <div class="dropdown-divider" role="presentation"></div>
                                @endif

                                <form action="{{ url('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item" type="submit" role="menuitem" style="color: red">
                                        <i class="icon md-power" aria-hidden="true"></i> Logout
                                    </button>
                                </form>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link icon md-sign-in" href="{{ url('login') }}" role="button">
                                <span class="sr-only">Sign in</span>
                            </a>
                        </li>
                    @endif
                </ul>
                <!-- End Navbar Toolbar Right -->
            </div>
            <!-- End Navbar Collapse -->

            <!-- Site Navbar Seach -->
            <div class="collapse navbar-search-overlap" id="site-navbar-search">
                <form id="search_form">
                    <div class="form-group">
                        <div class="input-search">
                            <i class="input-search-icon md-search" aria-hidden="true"></i>
                            <input type="text" class="form-control" id="input_search" name="site-search" placeholder="Search...">
                            <button type="button" class="input-search-close icon md-close" data-target="#site-navbar-search" data-toggle="collapse" aria-label="Close"></button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- End Site Navbar Seach -->
        </div>
    </nav>

    @yield('modal')

    <!-- Modal -->
    <div class="modal fade example-modal-lg modal-3d-sign" id="validasiNotifikasi" aria-hidden="true" aria-labelledby="validasiNotifikasi" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-simple modal-center modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                    <h4 class="modal-title" style="color: blue;">Konfirmasi Keranjang Belanja</h4>
                </div>
                <div class="modal-body">
                    <div class="example-wrap">
                        <br>
                        <h4 class="example-title text-center">Daftar Belanja</h4>
                        <div class="example">
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-cart-header" width="100%">
                                    <thead>
                                        <tr style="text-align: center;">
                                            <th width="30%">Nama</th>
                                            <th width="10%">Jumlah Barang</th>
                                            <th width="20%">Total Harga</th>
                                            <th width="10%"></th>
                                        </tr>
                                    </thead>
                                    <tbody >
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" style="cursor: pointer" data-id="${index}" id="btn-buy">Beli Sekarang</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End Modal -->
