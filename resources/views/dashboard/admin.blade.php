@php
	use App\Http\Controllers\Helper;
@endphp
@extends('app')

@section('css')
    <link rel="stylesheet" href="{{ asset('themeforest/page-base/examples/css/widgets/statistics.css') }}">
@endsection

@section('page')
    <div class="page-content">
        <div class="row">
            {{-- <div class="col-xl-8 col-lg-12">
                <div class="row">
                    <div class="col-lg-6">
                        <!-- Card Franchisee-->
                        <div class="card p-30 flex-row justify-content-between">
                            <div class="white">
                                <i class="icon icon-circle icon-2x md-accounts bg-red-600" aria-hidden="true"></i>
                            </div>
                            <div class="counter counter-md counter text-right">
                                <div class="counter-number-group">
                                    <span class="counter-number-related text-capitalize">Umum</span>
                                </div>
                                <span
                                    class="counter-number" style="color: blue; font-size:24pt;">{{ App\User::where('id_group', '2')->get()->count() }}</span>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Card Toko-->
                        <div class="card p-30 flex-row justify-content-between">
                            <div class="counter counter-md text-left">
                                <div class="counter-number-group">
                                    <span class="counter-number-related text-capitalize">Level 1</span>
                                    
                                </div>
                                <span
                                    class="counter-number" style="color: red; font-size:24pt;">{{ App\User::where('id_group', '3')->get()->count() }}</span>
                            </div>
                            <div class="white">
                                <i class="icon icon-circle icon-2x md-accounts bg-blue-600" aria-hidden="true"></i>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Card Sales-->
                        <div class="card p-30 flex-row justify-content-between">
                            <div class="white">
                                <i class="icon icon-circle icon-2x md-accounts bg-red-600" aria-hidden="true"></i>
                            </div>
                            <div class="counter counter-md counter text-right">
                                <div class="counter-number-group">
                                    <span class="counter-number-related text-capitalize">Level 2</span>
                                </div>
                                <span
                                    class="counter-number" style="color: blue; font-size:24pt;">{{ App\User::where('id_group', '4')->get()->count() }}</span>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Card Umum-->
                        <div class="card p-30 flex-row justify-content-between">
                            <div class="counter counter-md text-left">
                                <div class="counter-number-group">
                                    <span class="counter-number-related text-capitalize">Level 3</span>
                                </div>
                                <span
                                    class="counter-number" style="color: red; font-size:24pt;">{{ App\User::where('id_group', '5')->get()->count() }}</span>
                            </div>
                            <div class="white">
                                <i class="icon icon-circle icon-2x md-accounts bg-blue-600" aria-hidden="true"></i>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Card Supplier-->
                        <div class="card p-30 flex-row justify-content-between">
                            <div class="white">
                                <i class="icon icon-circle icon-2x md-accounts bg-red-600" aria-hidden="true"></i>
                            </div>
                            <div class="counter counter-md counter text-right">
                                <div class="counter-number-group">
                                    <span class="counter-number-related text-capitalize">Level 4</span>
                                </div>
                                <span
                                    class="counter-number" style="color: blue; font-size:24pt;">{{ App\User::where('id_group', '6')->get()->count() }}</span>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Card Driver-->
                        <div class="card p-30 flex-row justify-content-between">
                            <div class="counter counter-md text-left">
                                <div class="counter-number-group">
                                    <span class="counter-number-related text-capitalize">Sales</span>
                                </div>
                                <span
                                    class="counter-number" style="color: red; font-size:24pt;">{{ App\User::where('id_group', '7')->get()->count() }}</span>
                            </div>
                            <div class="white">
                                <i class="icon icon-circle icon-2x md-accounts bg-blue-600" aria-hidden="true"></i>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Card -->
                        <div class="card card-block p-30">
                            <div class="counter counter-md text-left">
                                <div class="counter-label text-uppercase mb-5">Jumlah Orderan Pada <b id="year">{{Helper::tgl_full(date('Y-m-d'),104)}}</b></div>
                                <div class="counter-number-group mb-10">
                                    <span class="counter-number">{{ App\PurchaseOrder::all()->count() }}</span>
                                </div>
                                <div class="counter-label">
                                    @php
                                        $jumlah = App\PurchaseOrder::all()->count();
                                        $pur = ($jumlah/100);
                                    @endphp
                                    <div class="progress progress-xs mb-10">
                                        <div class="progress-bar progress-bar-striped active progress-bar-danger" aria-valuenow="{{$jumlah}}"
                                            aria-valuemin="0" aria-valuemax="100" style="width: {{$jumlah}}%" role="progressbar">
                                        </div>
                                    </div>
                                    <div class="counter counter-sm text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-icon blue-600 mr-5"><i class="md-trending-up"></i></span>
                                            <span class="counter-number">{{$pur}}%</span>
                                            <span class="counter-number-related">more than last year</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>

                    <div class="col-lg-6">
                        <!-- Card -->
                        <div class="card card-block p-30">
                            <div class="counter counter-md text-left">
                                <div class="counter-label text-uppercase mb-5">Pending Order Pada <b id="year">{{Helper::tgl_full(date('Y-m-d'),104)}}</b></div>
                                <div class="counter-number-group mb-10">
                                    <span class="counter-number">{{ App\Order::where('status', 'BELUM DISETUJUI')->get()->count() }}</span>
                                </div>
                                <div class="counter-label">
                                    @php
                                        $count = App\Order::where('status', 'BELUM DISETUJUI')->get()->count();
                                        $per = ($count/100);
                                    @endphp
                                    <div class="progress progress-xs mb-5">
                                        <div class="progress-bar progress-bar-striped active progress-bar-info" aria-valuenow="{{$count}}"
                                            aria-valuemin="0" aria-valuemax="100" style="width: {{$count}}%" role="progressbar">
                                        </div>
                                    </div>
                                    <div class="counter counter-sm text-left">
                                        <div class="counter-number-group">
                                            <span class="counter-icon red-600 mr-5"><i class="md-trending-down"></i></span>
                                            <span class="counter-number">{{$per}}%</span>
                                            <span class="counter-number-related">less than last year</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- End Card -->
                    </div>
                </div>
            </div>

            <div class="col-xl-2 col-md-6">
                <!-- Card Produk-->
                <div class="card card-block p-30 bg-orange-600 text-center vertical-align h-300">
                    <div class="counter counter-lg counter-inverse vertical-align-middle">
                        <span class="counter-number"> {{ App\barang::all()->count() }} </span>
                        <div class="counter-label text-capitalize" style="font-weight: bold;">Produk</div>
                    </div>
                </div>
                <!-- End Card -->
            </div>
            <div class="col-xl-2 col-md-6">
                <!-- Card Kategori Produk-->
                @php
                    $barang_al = App\barang::select('barang_alias')->groupBy('barang_alias')->get('barang_alias')->count();
                @endphp
                <div class="card card-block p-30 bg-green-600 text-center vertical-align h-300">
                    <div class="counter counter-lg counter-inverse vertical-align-middle">
                        <span class="counter-number"> {{$barang_al}}</span>
                        <div class="counter-label text-capitalize" style="font-weight: bold;">Kategori</div>
                    </div>
                </div>
                <!-- End Card -->
            </div> --}}
        </div>
    </div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $("#year").append(date("Y", strtotime($tgl));
    });
</script>
@endsection
