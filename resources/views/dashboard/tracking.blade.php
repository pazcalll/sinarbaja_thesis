@extends('template.pages.datatable', [
'page' => 'Data Tracking',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Data Tracking', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead>
        <tr>
            <th>
                No. Nota
            </th>
            <th>
                Pembeli
            </th>
            <th class="hidden-sm-down w-200">
                Tgl Kirim
            </th>
            <th>
                Alamat
            </th>
            <th>
                Driver
            </th>
            <th>
                Status
            </th>
        </tr>
    </thead>
    <tbody class="table-section active" data-plugin="tableSection">
        <tr>
            <td class="font-weight-medium">
                Project #25369
            </td>
            <td>
                <span class="font-weight-medium">Mahatta Maulana</span>
            </td>
            <td class="hidden-sm-down">
                <span class="text-muted">January 22, 2021</span>
            </td>
            <td>
                RT 004 RW 003 Lingk. Centong Kel. Bawang Kec. Pesantren Kota Kediri Jawa Timur, 64136
            </td>
            <td>Ahmad Jaya</td>
            <td>
                <span class="badge badge-round badge-success">
                    Perjalanan ke Agen Terdekat
                </span>
            </td>
        </tr>
    </tbody>
    <tbody>
        <tr>
            <td></td>
            <td colspan="4" class="font-weight-medium text-success">
                IPhone Pro Max 12
            </td>
            <td>11</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4" class="font-weight-medium text-success">
                IPhone Pro Max 12
            </td>
            <td>11</td>
        </tr>
        <tr>
            <td></td>
            <td colspan="4" class="font-weight-medium text-success">
                IPhone Pro Max 12
            </td>
            <td>11</td>
        </tr>
    </tbody>
</table>

<!-- <table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead>
        <tr>
            <th>No.</th>
            <th>Pembeli</th>
            <th>Alamat</th>
            <th>Produk</th>
            <th>QTY</th>
            <th>Tgl Pesan</th>
            <th>Tgl Kirim</th>
            <th>Driver</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>1. </td>
            <td>Barokjaya</td>
            <td width="25%">RT 004 RW 003 Lingk. Centong Kel. Bawang Kec. Pesantren Kota Kediri Jawa Timur, 64136</td>
            <td>Infinix Note 7 Lite</td>
            <td>1</td>
            <td>21 January 2021</td>
            <td>22 January 2021</td>
            <td>Ahmad Jaya</td>
            <td>
                <span class="badge badge-round badge-success">
                    Perjalanan ke Agen Terdekat
                </span>
            </td>
        </tr>
    </tbody>
</table> -->
@endsection