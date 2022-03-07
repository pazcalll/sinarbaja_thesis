@extends('template.pages.datatable', [
'page' => 'Tagihan Masuk',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Tagihan', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Tagihan Masuk', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead>
        <tr>
            <th class="hidden-sm-down w-200">
                Tgl tagihan
            </th>
            <th class="hidden-sm-down w-200">
                Tgl jatuh tempo
            </th>
            <th>
                No. Nota
            </th>
            <th class="hidden-sm-down w-200">
                Tgl pesan
            </th>
            <th>
                Nominal
            </th>
            <th>
                Status
            </th>
            <th>
                Action
            </th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td class="hidden-sm-down">
                <span class="text-muted">April 01, 2021</span>
            </td>
            <td class="hidden-sm-down">
                <span class="text-muted">April 10, 2021</span>
            </td>
            <td>
                <span class="font-weight-medium">Project #25369</span>
            </td>
            <td class="hidden-sm-down">
                <span class="text-muted">January 01, 2021</span>
            </td>
            <td>
                <span class="font-weight-medium">10000000</span>
            </td>
            <td>
                <div class="badge badge-table badge-warning">Dibayar Sebagian</div>
            </td>
            <td align="center">
                <button class="btn btn-sm btn-icon btn-pure btn-default on-default" data-target="#modalBayar" data-toggle="modal" type="button" data-original-title="Bayar">
                    <a href="#" data-toggle="tooltip" data-original-title="Bayar"><i class="icon md-money-box" aria-hidden="true"></i></a>
                </button>
            </td>
        </tr>
    </tbody>
</table>
@endsection

@section('modal')
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
                                        <th class="hidden-sm-down w-200">
                                            Tgl Bayar
                                        </th>
                                        <th>Metode Bayar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1.</td>
                                        <td>
                                            <span class="text-muted"><i class="icon md-time"></i> April 01,
                                                2021</span>
                                        </td>
                                        <td>Cash</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default"><a href="#">Batal</a></button>
                <button type="button" class="btn btn-primary"><a href="#" style="color: beige;">Bayar</a></button>
            </div>
        </div>
    </div>
</div>
@endsection