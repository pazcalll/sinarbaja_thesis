<div class="tab-pane active animation-slide-left" id="proses" role="tabpanel">
    <div class="example-wrap">
        <div class="table-responsive">
            <table class="table w-full responsive display nowrap" id="table-unaccepted">
                <thead>
                    <tr style="text-align: center">
                        <th width="5%"></th>
                        <th width="5%">No.</th>
                        <th width="50%">No. Nota</th>
                        <th width="20%">Tanggal Pesan</th>
                        <th width="20%">Total Pesanan</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Tab Pesanan Proses -->
<div class="tab-pane animation-slide-left" id="pesanan_proses" role="tabpanel">
    <div class="">
        <div class="panel">
            <div class="panel-body nav-tabs-animate nav-tabs-horizontal" data-plugin="tabs">
                <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                    <li class="nav-item" role="presentation"><a class="active nav-link btn-outline-info" data-toggle="tab" href="#diproses-content" aria-controls="diproses-content" role="tab">Diproses</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link btn-outline-info" data-toggle="tab" href="#dikirim-content" aria-controls="dikirim-content" role="tab">Dikirim</a></li>
                </ul>

                <div class="tab-content">

                    <div id="diproses-content" class="table-responsive">
                        <table class="table w-full responsive display nowrap" id="table-pesanan-proses">
                            <thead>
                                <tr style="text-align: center">
                                    <th width="1%"></th>
                                    <th>No. Nota</th>
                                    {{-- <th>No. Tagihan</th> --}}
                                    <th>Tanggal</th>
                                    {{-- <th>Metode Bayar</th> --}}
                                    <th>Total</th>
                                    <th>Status Bayar</th>
                                    <th>Status Pengiriman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div id="dikirim-content" class="table-responsive">
                        <table class="table w-full responsive display nowrap" id="table-pesanan-kirim">
                            <thead>
                                <tr style="text-align: center">
                                    <th width="1%"></th>
                                    <th>No. Nota</th>
                                    {{-- <th>No. Tagihan</th> --}}
                                    <th>Tanggal</th>
                                    {{-- <th>Driver</th> --}}
                                    <th>Total</th>
                                    <th>Status Bayar</th>
                                    <th>Status Pengiriman</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                </div>
            </div>
            <!-- End Panel -->
        </div>
    </div>
</div>

<!-- Pesanan Selesai -->
<div class="tab-pane animation-slide-left" id="pesanan_selesai" role="tabpanel">
    <div class="example-wrap">
        <br>
        <h4 class="example-title">Daftar Pesanan Selesai</h4>
        <div class="example">
            <div class="table-responsive">
                <table class="table display w-full display nowrap" id="table-pesanan-selesai">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>No. Tagihan</th>
                            <th>Metode Bayar</th>
                            <th>Total</th>
                            <th>Status Bayar</th>
                            <th>Tanggal Pesan</th>
                        </tr>
                    </thead>
                    
                </table>
            </div>
        </div>
    </div>
</div>