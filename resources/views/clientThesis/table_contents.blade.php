<div class="tab-pane active animation-slide-bottom" id="proses" role="tabpanel">
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
<div class="tab-pane animation-slide-bottom" id="pesanan_proses" role="tabpanel">
    <div class="">
        <div class="panel">
            <div class="panel-body nav-tabs-animate nav-tabs-horizontal" data-plugin="tabs">
                <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                    <li class="nav-item" role="presentation"><a class="active nav-link btn-outline-info tab-unpaid" data-toggle="tab" href="#diproses-content" aria-controls="diproses-content" role="tab">Belum Dibayar</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link btn-outline-info tab-paid" data-toggle="tab" href="#dikirim-content" aria-controls="dikirim-content" role="tab">Sudah Dibayar</a></li>
                </ul>

                <div class="tab-content">

                    <div id="diproses-content" class="table-responsive tab-pane animation-slide-bottom">
                        <table class="table w-full table-striped responsive display nowrap" id="table-unpaid">
                            <thead>
                                <tr style="text-align: center">
                                    <th width="1%"></th>
                                    <th>No.</th>
                                    <th>No. Nota</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status Bayar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>

                    <div id="dikirim-content" class="table-responsive tab-pane animation-slide-bottom">
                        <table class="table w-full table-striped responsive display nowrap" id="table-paid">
                            <thead>
                                <tr>
                                    <th width="1%"></th>
                                    <th>No.</th>
                                    <th>No. Nota</th>
                                    <th>Tanggal</th>
                                    <th>Total</th>
                                    <th>Status Bayar</th>
                                    <th>Status Kirim</th>
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
<div class="tab-pane animation-slide-bottom" id="pesanan_selesai" role="tabpanel">
    <div class="example-wrap">
        <div class="example">
            <div class="table-responsive">
                <table class="table display w-full display nowrap" id="table-pesanan-selesai">
                    <thead>
                        <tr>
                            <th width="5%"></th>
                            <th width="5%">No.</th>
                            <th>No. Nota</th>
                            {{-- <th width="10%">Tipe User</th>
                            <th>Pembeli</th> --}}
                            <th>Tanggal Pesan</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>