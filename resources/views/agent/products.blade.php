@php
$showNavigation = false;
$bodyType = 'site-menubar-unfold site-menubar-show site-navbar-collapse-show';
@endphp

@extends('app')

@section('page')
<div class="page-content container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <!-- Panel -->
            <div class="panel">
                <div class="panel-body nav-tabs-animate nav-tabs-horizontal">
                    <div class="example-wrap">
                        <div class="example">
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama</th>
                                            <th>Harga</th>
                                            <th>Stok</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1.</td>
                                            <td>Apple iPhone 12 Pro Max 128GB - Pacific Blue</td>
                                            <td>Rp. 20.499.000</td>
                                            <td>
                                                10
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2.</td>
                                            <td>Apple iPhone XR 64GB - Black</td>
                                            <td>Rp. 7.999.000</td>
                                            <td>
                                                5
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3.</td>
                                            <td>Apple iPhone XR 128GB - White</td>
                                            <td>Rp. 9.299.000</td>
                                            <td>
                                                7
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4.</td>
                                            <td>Apple iPhone 11 64G - Black</td>
                                            <td>Rp. 11.999.000</td>
                                            <td>
                                                4
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Panel -->
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade example-modal-md modal-3d-sign" id="modalEditProfile" aria-hidden="true" aria-labelledby="modalEditProfile" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Form Edit Profile</h4>
            </div>
            <form id="form-update-profile" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-body" style="margin-bottom: -50px; margin-left: 30px;">
                    <div class="row row-md">
                        <div class="col-xl-12">
                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label text-left">Nama
                                    <span class="required">*</span>
                                </label>
                                <div class="col-xl-12 col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ Auth::user()->name }}" required="">
                                </div>
                            </div>

                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label text-left">Alamat
                                    <span class="required">*</span>
                                </label>
                                <div class=" col-xl-12 col-md-6">
                                    <input type="text" class="form-control" name="address" value="{{ Auth::user()->address }}" required="">
                                </div>
                            </div>

                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label text-left">No. Telp
                                    <span class="required">*</span>
                                </label>
                                <div class=" col-xl-12 col-md-6">
                                    <input type="text" class="form-control" name="no_handphone" value="{{ Auth::user()->no_handphone }}" required="">
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-material col-xl-12" style="margin-bottom: 50px;">
                            <button type="button" class="btn btn-default" id="resetBtn2">Reset</button>
                            <button type="submit" name="submit" class="btn btn-primary" id="validate-button-update-profile">Simpan</button>
                        </div>
                    </div>
            </form>
        </div>
    </div>
</div>
</div>

<div class="modal fade example-modal-md modal-3d-sign" id="modalReturn" aria-hidden="true" aria-labelledby="modalReturn" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Form Return Pesanan</h4>
            </div>
            <form id="form-return" enctype="multipart/form-data">
                <div class="modal-body" style="margin-left: 30px;">
                    <div class="row row-md">
                        <div class="col-xl-9 form-horizontal">
                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label text-left">Tanggal return
                                    <span class="required">*</span>
                                </label>
                                <div class=" col-xl-12 col-md-6">
                                    <div class="example">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">
                                                    <i class="icon md-calendar" aria-hidden="true"></i>
                                                </span>
                                            </div>
                                            <input type="text" class="form-control" data-plugin="datepicker" required="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label text-left">Nama Produk
                                    <span class="required">*</span>
                                </label>
                                <div class=" col-xl-12 col-md-6">
                                    <input type="text" class="form-control" name="nama" placeholder="Iphone 12 Pro Max 128GB" required="">
                                </div>
                            </div>


                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label text-left">Kuantitas
                                    <span class="required">*</span>
                                </label>
                                <div class=" col-xl-12 col-md-6">
                                    <input type="text" class="form-control" name="stock" placeholder="12" required="">
                                </div>
                            </div>

                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label text-left">Alasan
                                    <span class="required">*</span>
                                </label>
                                <div class=" col-xl-12 col-md-6">
                                    <textarea class="form-control" name="alasan" rows="3" placeholder="Contoh : Charger tidak ada" required=""></textarea>
                                </div>
                            </div>


                        </div>

                        <div class="form-group form-material col-xl-12">
                            <button type="button" class="btn btn-default" id="resetBtn">Reset
                            </button>
                            <button type="submit" name="submit" class="btn btn-primary" id="validateButton1">Simpan
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade example-modal-lg modal-3d-sign" id="modalBayar" aria-hidden="true" aria-labelledby="modalBayar" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Konfirmasi Pembayaran Tagihan</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Daftar tagihan</h4>
                    <div class="example">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Nama</th>
                                        <th>Tanggal Pesan</th>
                                        <th>Total Pesanan</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>Apple iPhone 12 Pro Max 128GB - Pacific Blue</td>
                                        <td>
                                            <span class="text-muted"><i class="icon md-time"></i> Apr 12,
                                                2021</span>
                                        </td>
                                        <td>Rp. 20.499.000</td>
                                        <td>
                                            1
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="form-group form-material col-3" data-plugin="formMaterial">
                                <label class="form-control-label" for="select">Metode Pembayaran <span class="required">*</span></label>
                                <select class="form-control" id="select" required="">
                                    <option>Tunai/Cash</option>
                                    <option>Transfer Bank</option>
                                    <option>Indomaret/Alfamart</option>
                                    <option>Link Aja</option>
                                    <option>Dana</option>
                                    <option>OVO</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"><a href="{{ url('/client.profile') }}">Batal</a></button>
                <button type="button" class="btn btn-primary"><a href="{{ url('/client.profile') }}" style="color: beige;">Bayar</a></button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script src="{{ asset('public/themeforest/global/js/Plugin/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('public/themeforest/global/vendor/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('public/themeforest/page-base/examples/js/forms/validation.js') }}"></script>
<script src="{{ asset('public/themeforest/global/vendor/toastr/toastr.js') }}"></script>
<script src="{{ asset('public/themeforest/global/js/Plugin/toastr.js') }}"></script>
<script src="{{ asset('public/themeforest/page-base/examples/js/forms/validation.js') }}"></script>
<script src="{{ asset('public/themeforest/page-base//examples/js/forms/uploads.js') }}"></script>

<script>
    $(document).ready(function() {
        $("#resetBtn").click(function() {
            document.getElementById("form-return").reset();
            $("#modalReturn").modal();
        });
        $("#resetBtn2").click(function() {
            document.getElementById("form-update-profile").reset();
            $("#modalEditProfile").modal();
        });
    })
</script>
@endsection
