@php
$id = Auth::id();
$showNavigation = false;
$bodyType = 'site-menubar-unfold site-menubar-show site-navbar-collapse-show';
@endphp

@extends('app')

@section('css')
<style>
    td.details-control {
        background: url('../resources/details_open.png') no-repeat center center;
        cursor: pointer;
    }

    tr.details td.details-control {
        background: url('../resources/details_close.png') no-repeat center center;
    }
</style>
@endsection

@section('modal')
<div class="modal fade example-modal-lg modal-3d-sign"  data-toggle="modal" id="modalPembayaran" aria-hidden="true" aria-labelledby="modalPembayaran" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="titleMPembayaran" style="color: blue;">Pembayaran</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Daftar Pembayaran</h4>
                    <div class="example">
                        <div class="table-responsive">
                            <form id="form-list-pickup" method="post">
                                @csrf
                                <input type="hidden" name="pembayaran">

                                <table class="table table-bordered table-hover table-striped" id="tb_pembayaran">
                                    <thead id="thead">
                                        <tr style="text-align: center;">
                                            <th>No.</th>
                                            <th>Tagihan</th>
                                            <th width="20%">Total</th>
                                            <th>Metode Pembayaran</th>
                                            <th>Upload Bukti Pembayaran</th>
                                        </tr>
                                    </thead>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning">Bayar</button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-target="#modalTagihan" id="back2">Back</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal Upload Transfer -->
<div class="modal fade example-modal-md modal-3d-sign" id="uploadBukti" aria-hidden="true" aria-labelledby="uploadBukti" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" style="color: blue">Upload Bukti Bayar</h4>
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

@section('js')
<script>
    var user = `{{Auth::user()->group_id}}`;
    var table;
    $(document).ready(function() {
        $('#btn-pay').click(function() {
            $('#modalPembayaran').modal('show')
            $('#modalTagihan').modal('hide')
            $headTable = $('#thead')
            $table = $('#tb_pembayaran')

            const id = $(this).data('id')
            const no_nota = $(this).data('no_nota')
            const data = table.row().data()[id]

            $('input[name=id]').val(data.id)
                var templatePbyn = '';
                data.tagihans.forEach(tagihan, _index => {
                    var total= tagihan.nominal_total != null ? 'Rp' + tagihan.nominal_total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") : '-';
                    
                    templatePbyn = `
                        <tbody class="table-section" data-plugin="tableSection">
                            <tr style="cursor: pointer; text-align: center;">
                                <td class="text-center"></i></td>
                                <td> ${ _index+1 } </td>
                                <td> Tagihan ${ _index+1 }
                                <input type="hidden" id="id_tagihan" name="id_tagihan" value="${tagihan.id}"></td>
                                <td class="font-weight-medium text-danger">${ total }</td>
                                <td class="font-weight-medium text-danger">
                                <select class="option-bayar" id="metode" required="">
                                    <option value="Alfamart/Indomaret>Alfamart/Indomaret</option>
                                    <option value="Dana>Dana</option>
                                    <option value="Go-Pay>GO-Pay</option>
                                    <option value="Link Aja">Link Aja</option>
                                    <option value="OVO">OVO</option>
                                    <option vlaue="Transfer Bank">Transfer Bank</option>   
                                </select>
                                </td>
                                <td class="text-center">
                                <button class="btn btn-sm btn-icon btn-pure btn-default on-default" data-target="#uploadBukti" data-toggle="modal" type="button" data-original-title="Bayar">
                                    <a href="#modalPembayaran" data-toggle="tooltip" data-original-title="UploadBukti"><i class="icon md-upload" aria-hidden="true"></i></a>
                                </button>
                                </td>
                            </tr>
                        </tbody>`
                });
        });
    })
</script>