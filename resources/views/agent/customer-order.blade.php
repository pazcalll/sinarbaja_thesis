@extends('template.pages.datatable-client')

@section('header-tabel')
    <header class="panel-heading">
        <div class="panel-actions"></div>
        <h3 class="panel-title">Pembelian customer</h3>
    </header>
@endsection

@section('table')
    <table class="table table-bordered table-hover table-striped" id="exampleAddRow">
        <thead>
            <tr>
                <th>Nama Customer</th>
                <th>Nama Produk</th>
                <th>QTY</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>OPPO v1</td>
                <td>1</td>
                <td>MENUNGGU KONFIRMASI</td>
            </tr>
            <tr>
                <td>OPPO v2</td>
                <td>1</td>
                <td>DIKIRIM</td>
            </tr>
            <tr>
                <td>OPPO v3</td>
                <td>1</td>
                <td>PENDING</td>
            </tr>
        </tbody>
    </table>
@endsection
