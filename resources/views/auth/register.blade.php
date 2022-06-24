<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap material admin template">
    <meta name="author" content="">

    <title>Halaman Masuk</title>

    <link rel="apple-touch-icon" href="{{ asset('public/themeforest/page-base/images/apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('public/themeforest/page-base/images/favicon.ico') }}">

    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/css/bootstrap-extend.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/page-base/css/site.min.css') }}">

    <!-- Plugins -->
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/animsition/animsition.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/asscrollable/asScrollable.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/switchery/switchery.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/intro-js/introjs.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/slidepanel/slidePanel.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/flag-icon-css/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/waves/waves.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/page-base/examples/css/pages/login-v3.css') }}">


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

<body class="animsition page-login-v3 layout-full">
    <!--[if lt IE 8]>
        <p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
    <![endif]-->


    <!-- Page -->
    <div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">>
        <div class="page-content vertical-align-middle">
            <div class="panel">
                <div class="panel-body">
                    <div class="brand">
                        <img class="brand-img" src="{{ asset('public/themeforest/page-base/images/logo-colored.png') }}" alt="...">
                        <h2 class="brand-text font-size-18">PRIVATE ECOMMERCE</h2>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form method="post" action="{{ route('registerUser') }}" autocomplete="off">
                        @csrf

                        <div class="form-group form-material floating" data-plugin="formMaterial">
                            <input type="text" class="form-control" name="name" />
                            <label class="floating-label">Nama</label>
                        </div>

                        <div class="form-group form-material floating" data-plugin="formMaterial">
                            <input type="email" class="form-control" name="email" />
                            <label class="floating-label">Email</label>
                        </div>

                        <div class="form-group form-material floating" data-plugin="formMaterial">
                            <input type="text" class="form-control" name="address" />
                            <label class="floating-label">Alamat</label>
                        </div>

                        <div class="form-group form-material floating" data-plugin="formMaterial">
                            <input type="text" class="form-control" name="no_handphone" />
                            <label class="floating-label">No Telepon</label>
                        </div>

                        <div class="form-group form-material floating" data-plugin="formMaterial">
                            <input type="password" class="form-control" name="password" />
                            <label class="floating-label">Password</label>
                        </div>

                        <!-- <div class="form-group clearfix">
                            <div class="checkbox-custom checkbox-inline checkbox-primary checkbox-lg float-left">
                                <input type="checkbox" id="inputCheckbox" name="group_id" value="AGENT">
                                <label for="inputCheckbox">Daftar sebagai Agent</label>
                            </div>
                        </div>
                        <div class="form-group clearfix">
                            <div class="checkbox-custom checkbox-inline checkbox-primary checkbox-lg float-left">
                                <input type="checkbox" id="inputCheckbox" name="group_id" value="DRIVER">
                                <label for="inputCheckbox">Daftar sebagai Driver</label>
                            </div>
                        </div> -->

                        <button type="submit" class="btn btn-primary btn-block btn-lg mt-40">Daftar</button>
                    </form>
                    <p>Sudah memiliki akun ? Silahkan <a href="{{ url('login') }}">Masuk</a></p>
                </div>
            </div>

            <footer class="page-copyright page-copyright-inverse">
                <p>© 2021. All RIGHT RESERVED.</p>
        </div>
        </footer>
    </div>
    </div>
    <!-- End Page -->

    <!-- Core  -->
    <script src="{{ asset('public/themeforest/global/vendor/babel-external-helpers/babel-external-helpers.js') }}">
    </script>
    <script src="{{ asset('public/themeforest/global/vendor/jquery/jquery.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/popper-js/umd/popper.min.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/bootstrap/bootstrap.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/animsition/animsition.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/mousewheel/jquery.mousewheel.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/asscrollbar/jquery-asScrollbar.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/asscrollable/jquery-asScrollable.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/ashoverscroll/jquery-asHoverScroll.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/waves/waves.js') }}"></script>

    <!-- Plugins -->
    <script src="{{ asset('public/themeforest/global/vendor/switchery/switchery.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/intro-js/intro.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/screenfull/screenfull.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/slidepanel/jquery-slidePanel.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/vendor/jquery-placeholder/jquery.placeholder.js') }}"></script>

    <!-- Scripts -->
    <script src="{{ asset('public/themeforest/global/js/Component.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/js/Plugin.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/js/Base.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/js/Config.js') }}"></script>

    <script src="{{ asset('public/themeforest/page-base/js/Section/Menubar.js') }}"></script>
    <script src="{{ asset('public/themeforest/page-base/js/Section/GridMenu.js') }}"></script>
    <script src="{{ asset('public/themeforest/page-base/js/Section/Sidebar.js') }}"></script>
    <script src="{{ asset('public/themeforest/page-base/js/Section/PageAside.js') }}"></script>
    <script src="{{ asset('public/themeforest/page-base/js/Plugin/menu.js') }}"></script>

    <script src="{{ asset('public/themeforest/global/js/config/colors.js') }}"></script>
    <script src="{{ asset('public/themeforest/page-base/js/config/tour.js') }}"></script>
    <script>
        Config.set('assets', '../../assets');
    </script>

    <!-- Page -->
    <script src="{{ asset('public/themeforest/page-base/js/Site.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/js/Plugin/asscrollable.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/js/Plugin/slidepanel.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/js/Plugin/switchery.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/js/Plugin/jquery-placeholder.js') }}"></script>
    <script src="{{ asset('public/themeforest/global/js/Plugin/material.js') }}"></script>

    <script>
        (function(document, window, $) {
            'use strict';

            var Site = window.Site;
            $(document).ready(function() {
                Site.run();
            });
        })(document, window, jQuery);
    </script>
</body>

</html>