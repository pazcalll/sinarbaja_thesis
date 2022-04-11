@extends('template.pages.datatable-client', [
'page' => 'Data Tracking',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Data Tracking', 'link' => '', 'active' => 'active']
]
])

@section('header-tabel')
<header class="panel-heading">
    <div class="panel-actions"></div>
    <h3 class="panel-title">List Tracking Pembelian</h3>
</header>
@endsection

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead>
        <tr>
            <th>Nama Produk</th>
            <th>QTY</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>OPPO v1</td>
            <td>1</td>
            <td>
                <div class="badge badge-table badge-success">Dalam Perjalanan ke Agent</div>
            </td>
        </tr>
    </tbody>
</table>
@endsection