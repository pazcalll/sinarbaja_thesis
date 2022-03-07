@extends('template.pages.datatable', [
'page' => 'Bayar Tagihan',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Tagihan', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Bayar Tagihan', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead>
        <tr style="text-align: center">
            <th class="w-50">
            </th>
            <th>
                No. Nota
            </th>
            <th>
                Nominal
            </th>
            <th>
                Metode Pembayaran
            </th>
            <th>
                Upload Bukti Bayar
            </th>
        </tr>
    </thead>
    <tbody class="table-section" data-plugin="tableSection">    
        <tr>
            <td>
                <div class="checkbox-custom checkbox-warning">
                    <input type="checkbox" id="inputUnchecked">
                    <label for="inputUnchecked"></label>
                </div>
            </td>
            <td class="font-weight-medium">
                <span> KWSUPER03 </span>
            </td>
            <td>
                <span class="font-weight-medium">Rp 1.000.000</span>
            </td>
            <td>
                <span class="font-weight-medium">Cash</span>
            </td>
            <td class="text-center">
                <button class="btn btn-sm btn-icon btn-pure btn-default on-default" data-target="#modalUploadBukti" data-toggle="modal" type="button" data-original-title="Bayar">
                    <a href="#" data-toggle="tooltip" data-original-title="UploadBukti"><i class="icon md-upload" aria-hidden="true"></i></a>
                </button>
            </td>
        </tr>
    </tbody>
</table>
@endsection

@section('modal')
<div class="modal fade example-modal-md modal-3d-sign" id="modalUploadBukti" aria-hidden="true" aria-labelledby="modalUploadBukti" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Upload Bukti Bayar</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Upload File</h4>
                    <div class="example">
                        <input type="file" id="UploadBukti" data-plugin="dropify" data-default-file="" />
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><a href="#">Batal</a></button>
                <button type="button" class="btn btn-primary"><a href="#" style="color: beige;">Upload</a></button>
            </div>
        </div>
    </div>
</div>
@endsection