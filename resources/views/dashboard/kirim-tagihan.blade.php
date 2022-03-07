@extends('template.pages.datatable', [
'page' => 'Kirim Tagihan',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Tagihan', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Kirim Tagihan', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead>
        <tr>
            <th class="w-50">
            </th>
            <th>
                Toko
            </th>
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
        </tr>
    </thead>
    <tbody class="table-section active" data-plugin="tableSection">
        <tr>
            <td>
                <div class="checkbox-custom checkbox-warning">
                    <input type="checkbox" id="inputUnchecked">
                    <label for="inputUnchecked"></label>
                </div>
            </td>
            <td class="font-weight-medium">
                Mahatta Maulana
            </td>
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
        </tr>
    </tbody>
    <tbody>
        <tr>
            <th>
            </th>
            <th class="text-center">
                No.
            </th>
            <th class="hidden-sm-down w-200" colspan="2">
                Tgl Bayar
            </th>
            <th colspan="2">
                Nominal
            </th>
            <th colspan="2">
                Metode Bayar
            </th>
        </tr>
        <tr>
            <td></td>
            <td class="font-weight-medium text-center">
                1.
            </td>
            <td class="hidden-sm-down" colspan="2">
                <span class="text-muted">April 01, 2021</span>
            </td>
            <td colspan="2">
                <span class="font-weight-medium">1500000</span>
            </td>
            <td colspan="2">
                <span class="font-weight-medium">Cash</span>
            </td>
        </tr>
    </tbody>
</table>
@endsection