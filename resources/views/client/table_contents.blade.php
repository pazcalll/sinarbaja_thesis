<div class="tab-pane active animation-slide-left" id="proses" role="tabpanel">
    <div class="example-wrap">
        <div class="table-responsive">
            <table class="table w-full responsive display nowrap" id="table-prosses">
                <thead>
                    <tr style="text-align: center">
                        <th width="2%"></th>
                        <th width="4%">No.</th>
                        <th width="20%">No. Nota</th>
                        <th width="15%">Tanggal Pesan</th>
                        <th width="15%">Total Pesanan</th>
                        <th width="1%">Detail</th>
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
                                    <th>No. Tagihan</th>
                                    <th>Tanggal</th>
                                    <th>Metode Bayar</th>
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
                                    <th>No. Tagihan</th>
                                    <th>Tanggal</th>
                                    <th>Driver</th>
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
<!-- Tab Pesanan Tertunda -->
<div class="tab-pane animation-slide-left" id="tertunda" role="tabpanel">
    <div class="example-wrap">
        <br>
        <h4 class="example-title">Daftar Pesanan Tertunda</h4>
        <div class="example">
            <div class="table-responsive">
                <table class="table w-full responsive display nowrap" id="tabel_pending" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Nama</th>
                            <th>No. Nota</th>
                            <th>Tanggal Pesan</th>
                            <th>Total Pesanan</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="tab-pane animation-slide-left" id="return" role="tabpanel">
    <div class="example-wrap">
        <br>
        <h4 class="example-title">Daftar Return Pesanan</h4>
        <div class="example">
            <div class="table-responsive">
                <table class="table w-full responsive display nowrap" id="tabel_return" style="width: 100%;">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Tgl Return</th>
                            <th>Nama</th>
                            <th>No. Nota</th>
                            <th>Qty</th>
                            <th>Alasan</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="tab-pane animation-slide-left" id="pesanan" role="tabpanel">
    <div class="example-wrap">
        <br>
        <h4 class="example-title">Daftar Riwayat Pesanan</h4>
        <div class="example">
            <div class="table-responsive">
                <table class="table display w-full display nowrap" id="tabel_riwayat">
                    <thead>
                        <tr>
                            <th></th>
                            <th>No.</th>
                            <th>No. Nota</th>
                            <th>Tanggal Pesan</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    @php $no = 1; @endphp
                    @foreach($riwayat as $i => $arr)
                    <tbody class="table-section" data-plugin="tableSection">
                        <tr>
                            <td><i class="table-section-arrow"></td>
                            <td>{{$no++}}</td>
                            <td>{{$arr->no_nota}}</td>
                            <td colspan="4"><span class='text-muted'><i class='icon md-time'></i> {{$arr->created_at}}</span></td>
                        </tr>
                    </tbody>
                    <tbody>
                        <tr>
                            <th></th>
                            <th>NAMA PRODUK</th>
                            <th>STATUS</th>
                            <th>QTY</th>
                            <th>HARGA</th>
                            <th>TOTAL</th>
                        </tr>
                        @foreach($arr->orders as $j => $p)
                        <tr>
                            <td></td>

                            <td style="color: blue;">{{$p->nama_barang}}</td>
                            <td>
                                @if ($p->status == 'AWAL PESAN')
                                    <span class="badge badge-secondary">AWAL PESAN</span>
                                @elseif ($p->status == 'BELUM DISETUJUI')
                                    <span class="badge badge-danger">BELUM DISETUJUI</span>
                                @elseif ($p->status == 'DISETUJUI SEBAGIAN')
                                    <span class="badge badge-success">DISETUJUI SEBAGIAN</span>
                                @elseif ($p->status == 'DISETUJUI SEMUA')
                                    <span class="badge badge-success">DISETUJUI SEMUA</span>
                                @endif
                            </td>
                            <td>{{$p->qty}}</td>
                            <td>Rp. {{number_format($p->harga_order,2,',','.')}}</td>
                            <td>Rp. {{number_format($p->qty * $p->harga_order,2,',','.')}}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    @endforeach
                </table>
            </div>
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
                    @php $no = 1; @endphp

                    @foreach($pesananSelesai as $i => $arr)
                    {{-- {{dd($pesananSelesai)}} --}}
                        <tbody class="table-section" data-plugin="tableSection">
                            <tr>
                                <td><i class="table-section-arrow"></td>
                                <td>{{$no++}}</td>
                                <td>{{$arr->tagihans[0]->no_tagihan}}</td>
                                <td>{{$arr->tagihans[0]->metode_bayar}}</td>
                                <td>Rp. {{number_format($arr->tagihans[0]->nominal_total,2,',','.')}}</td>
                                <td>
                                    @if ($arr->tagihans[0]->status == 'LUNAS')
                                        <span class="badge badge-success">LUNAS</span>
                                    @elseif ($arr->tagihans[0]->status == 'DIBAYAR SEBAGIAN')
                                        <span class="badge badge-danger">DIBAYAR SEBAGIAN</span>
                                    @endif
                                </td>
                                <td colspan="4"><span class='text-muted'><i class='icon md-time'> </i> {{$arr->created_at->format('l, d-m-Y')}}</span></td>
                            </tr>
                        </tbody>
                        <tbody>
                            <tr>
                                <th></th>
                                <th></th>
                                <th colspan="2">Nama Produk</th>
                                <th>Qty</th>
                                <th>Potongan</th>
                                <th>Total Harga</th>
                            </tr>
                            @foreach($arr->orders as $j => $p)
                            <tr>
                                <td></td>
                                <td></td>
                                <td colspan="2" style="color: blue;">{{$p->nama_barang}}</td>
                                <td>{{$p->qty}}</td>
                                <td>Rp {{number_format($p->potongan_order_rp,2,',','.')}}</td>
                                <td>Rp {{number_format($p->harga_order*$p->qty,2,',','.')}}</td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    @endforeach

                </table>
            </div>
        </div>
    </div>
</div>