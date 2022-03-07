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

@section('page')
<div class="row">
    <div class="col-lg-12">
        <!-- Panel -->
        <div class="panel">
            <div class="panel-body nav-tabs-animate nav-tabs-horizontal" data-plugin="tabs">
                <ul class="nav nav-tabs nav-tabs-line" role="tablist">
                    {{-- @if (Auth::user()->group_id === 'DRIVER') --}}
                    @if (App\GroupUser::where('id', Auth::user()->id_group)->get('group_name')->first()->group_name == 'DRIVER')
                    <li class="nav-item" role="presentation"><a class="active nav-link" data-toggle="tab" href="#proses" aria-controls="proses" role="tab">Pesanan Barang</a></li>
                    {{-- @elseif (Auth::user()->group_id === 'AGENT' || Auth::user()->group_id === 'CUSTOMER') --}}
                    {{-- @elseif (App\GroupUser::where('id', Auth::user()->id_group)->get('group_name')->first()->group_name == 'CUSTOMER' || App\GroupUser::where('id', Auth::user()->id_group)->get('group_name')->first()->group_name == 'AGENT') --}}
                    @elseif (App\GroupUser::where('id', Auth::user()->id_group)->get('group_name')->first()->group_name != 'DRIVER' || App\GroupUser::where('id', Auth::user()->id_group)->get('group_name')->first()->group_name != 'ADMINISTRATOR')
                    <li class="nav-item" role="presentation"><a class="active nav-link" data-toggle="tab" href="#proses" aria-controls="proses" role="tab">Pesanan Masuk</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" onclick="btnRefresh()" href="#pesanan_proses" aria-controls="pesanan_proses" role="tab">Pesanan Proses</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" onclick="btnRefresh()" href="#pesanan_selesai" aria-controls="pesanan_selesai" role="tab">Pesanan Selesai</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" onclick="btnRefresh()" href="#tertunda" aria-controls="tertunda" role="tab">Pesanan Tertunda</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" onclick="btnRefresh()" href="#return" aria-controls="return" role="tab">Return Pesanan</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" onclick="btnRefresh()" href="#pesanan" aria-controls="pesanan" role="tab">Riwayat Pesanan</a></li>
                    @endif
                </ul>

                <div class="tab-content">

                    @include('client.table_contents')

                </div>
            </div>
            <!-- End Panel -->
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade example-modal-lg modal-3d-sign" id="modalPickup" aria-hidden="true" aria-labelledby="modalPickup" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Detail Pengiriman Barang</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Daftar barang yang akan dikirim</h4>
                    <div class="example">
                        <div class="table-responsive">
                            <form id="form-list-pickup">
                                @csrf
                                <input type="hidden" name="id">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Produk</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-pickup">
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary btn-pickup" id="validateButton1">Pickup</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade example-modal-lg modal-3d-sign" id="modalArrive" aria-hidden="true" aria-labelledby="modalArrive" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Detail Pengiriman Barang</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Daftar barang yang sampai</h4>
                    <div class="example">
                        <div class="table-responsive">
                            <form id="form-list-pickup">
                                @csrf
                                <input type="hidden" name="id">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Produk</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-pickup">
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary btn-arrive" id="validateButton1">Selesai Pengiriman</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade example-modal-lg modal-3d-sign" id="modalConfirm" aria-hidden="true" aria-labelledby="modalConfirm" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Detail Pengiriman Barang</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Daftar barang yang sampai</h4>
                    <div class="example">
                        <div class="table-responsive">
                            <form id="form-list-pickup">
                                @csrf
                                <input type="hidden" name="id">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Produk</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-pickup">
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Back</button>
                <button type="submit" class="btn btn-primary btn-confirm" id="validateButton1">Konfirmasi</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade example-modal-lg modal-3d-sign" id="modalCek" aria-hidden="true" aria-labelledby="modalCek" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Detail Pengiriman Barang</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Daftar barang yang sampai</h4>
                    <div class="example">
                        <div class="table-responsive">
                            <form id="form-list-pickup">
                                @csrf
                                <input type="hidden" name="id">

                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama Produk</th>
                                            <th>Qty</th>
                                        </tr>
                                    </thead>
                                    <tbody class="tbody-pickup">
                                    </tbody>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
<!--Modal Tagihan -->
<div class="modal fade example-modal-lg modal-3d-sign" id="modalTagihan" aria-hidden="true" aria-labelledby="modalTagihan" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="titleMTagihan" style="color: blue;">Tagihan</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Daftar barang yang sampai</h4>
                    <div class="example">
                        <div class="table-responsive">
                            <form id="form-list-pickup">
                                @csrf
                                <input type="hidden" name="tagihan">

                                <table class="table table-bordered table-hover table-striped" id="tb_tagihan">
                                    <thead id="thead">
                                        <tr style="text-align: center;">
                                            <th></th>
                                            <th>No.</th>
                                            <th width="10%">Tagihan</th>
                                            <th width="15%">Tanggal</th>
                                            <th>Driver</th>
                                            <th width="12%">Total</th>
                                            <th>Status Bayar</th>
                                            <th>Status Pengiriman</th>
                                            <th width="35%">Aksi</th>
                                        </tr>
                                    </thead>
                                </table>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
@include('client.modal.modal_pembayaran')
@include('client.modal.modal_transfer')
@include('client.modal.modal_return')
@include('client.modal.modal_confirm_pesanan')

@endsection

@section('js')
<script>
    var table2;
    var user = `{{Auth::user()->group_id}}`;
    var table;
    let tablePesananProses = null
    let tagihanCollections = [];
    let toPayment = [];
    let reasons = [];
    let obtainedIndex = 0
    let newImg = null
    var form_data = new FormData();
    // data.tagihans.forEach((tagihan, _index) => {
    $(document).ready(function() {
        // console.log(user);
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        table = $('#table-prosses').DataTable({
            processing: true,
            serverSide: true,
            bInfo: false,
            ajax: `{{ url("data/order/$id/prosses") }}`,
            columns: [{
                    "class": "details-control",
                    "orderable": false,
                    "data": null,
                    "defaultContent": "",
                    render: (data, type, row, meta) => {
                        return `<i class="table-section-arrow">`
                    }
                },
                {
                    data: null,
                    render: (data, type, row, meta) => {
                        return meta.row + 1
                    }
                },
                {
                    data: 'no_nota'
                },
                {
                    data: 'created_at',
                    render: (data, type, row, meta) => moment(data).format('dddd, DD MMMM YYYY')
                },
                {
                    data: 'order_awal',
                    render: (data, type, row, meta) => {
                        let price = 0
                        data.forEach((item, index) => {
                            price += (item.harga_order * item.qty)
                        })
                        // console.log(data[meta.row+1])
                        var formatter = new Intl.NumberFormat('id-ID', {
                            style: 'currency',
                            currency: 'IDR',
                        });

                        return formatter.format(price)
                    }
                },
                {
                    data: 'id',
                    render: (data, type, row, meta) => {
                        console.log(row)
                        if (row.no_nota.length > 0) {
                            return `<button class="btn btn-sm btn-icon btn-pure btn-default on-default btn-detail" data-id="${ meta.row }" data-no_nota="${ row.no_nota }" id="cetak-pesanan" onclick="" type="button">
                                        <a href="{{ route('cetak_pesanan') }}?no_nota=${ row.no_nota }" target="_blank" data-toggle="tooltip" data-original-title="Detail"><i class="icon md-file" aria-hidden="true" style="cursor: pointer"></i></a>
                                    </button>`
                        }else {
                            return '-'
                        }
                    }
                }
            ]
        });

        tablePesananProses = $('#table-pesanan-proses')
        tablePesananKirim = $('#table-pesanan-kirim')
        function tbodyFiller(obj) {
            let table_proses = ''
            obj.tagihans.forEach((obj_nested, _index_nested) => {
                table_proses += `
                <tbody class="table-section" data-plugin="tableSection">
                    <tr>
                        <td>
                            <i class="table-section-arrow">
                        </td>
                        <td>
                            ${obj.no_nota}
                        </td>
                        <td>
                            ${obj_nested.no_tagihan}
                        </td>
                        <td>
                            ${moment(obj_nested.created_at).format('DD MMMM YYYY')}
                        </td>
                        <td>
                            `
                    obj_nested.driver_id != null ? table_proses += `${obj_nested.driver.name}` : table_proses += `${obj_nested.metode_bayar}`
                    table_proses += `
                        </td>
                        <td>
                            Rp ${obj_nested.nominal_total.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")}
                        </td>
                        <td>
                            `
                            if(obj_nested.status == 'BELUM DIBAYAR') table_proses+=`<span class="badge badge-secondary">${obj_nested.status}</span>`
                            else if (obj_nested.status == 'DIBAYAR SEBAGIAN') table_proses+=`<span class="badge badge-danger">${obj_nested.status}</span>`
                            else if (obj_nested.status == 'LUNAS') table_proses+=`<span class="badge badge-success">${obj_nested.status}</span>`
                            table_proses +=`
                        </td>
                        <td>
                        `
                    if(obj_nested.trackings.length == 0) table_proses+='<span class="badge badge-secondary">NOT IN PROCESS</span>'
                    else{
                        if(obj_nested.tracking_newest[0].status == 'WAITING TO PICKUP') table_proses+=`<span class="badge badge-secondary">${obj_nested.tracking_newest[0].status}</span>`
                        else if(obj_nested.tracking_newest[0].status == 'SENDING') table_proses+=`<span class="badge badge-info">${obj_nested.tracking_newest[0].status}</span>`
                        else if(obj_nested.tracking_newest[0].status == 'ARRIVED') table_proses+=`<span class="badge badge-success">${obj_nested.tracking_newest[0].status}</span>`
                        else if(obj_nested.tracking_newest[0].status == 'ARRIVED WITH RETURN') table_proses+=`<span class="badge badge-danger">${obj_nested.tracking_newest[0].status}</span>`
                    }
                    table_proses +=`
                        </td>
                        <td>
                            <a href="{{route('cetak_tagihan')}}?no_nota=${ obj.no_nota }&id_tagihan=${ obj_nested.id }" target="_blank" type="button" name="" id="" class="btn btn-xs btn-primary" style="width: 80pt;"><i class="icon md-money-box" aria-hidden="true"></i>Lihat Tagihan</a>
                            `
                            if(obj_nested.metode_bayar == 'COD') {
                                table_proses += ``
                            }else if(obj_nested.metode_bayar == 'Transfer') {
                                if(obj_nested.status == 'BELUM DIBAYAR') {
                                    table_proses += `<a id="btn-pay${obj_nested.id}" name="pembayaran" data-toggle="modal" href="no_nota=${ obj.no_nota }&id_tagihan=${ obj_nested.id }" type="button" class="btn btn-xs btn-warning pembayaran" style="width: 80pt;" data-no-tagihan="${obj_nested.no_tagihan}" style="ml-2"><i class="icon md-money" aria-hidden="true"></i>Pembayaran</a>`
                                }else if(obj_nested.status == 'DIBAYAR SEBAGIAN' || 'LUNAS') {
                                    table_proses += ``
                                }
                            }
                                table_proses +=`
                            `
                            if(obj_nested.tracking_newest.length == 0) {
                                table_proses += ``
                            }else {
                                table_proses += `<a href="{{url('data')}}/order/pesanan-diterima/${obj_nested.id}" class="btn btn-xs btn-success arrived" style="width: 80pt; text-align: center;" aria-hidden="true">Pesanan Diterima</a>`
                            }
                                table_proses += `
                            `
                    if (obj_nested.trackings.length>0) {
                        if(obj_nested.trackings[0].status == 'ARRIVED WITH RETURN') {
                            table_proses +=`<a id="btn-return" name="return" data-toggle="modal" href="no_nota=${ obj.no_nota }&id_tagihan=${ obj_nested.id }" type="button" class="btn btn-xs btn-danger"><i class="icon md-assignment-return" aria-hidden="true"></i>Return</a>`
                        }
                    }
                    table_proses += `</td>
                                </tr>
                            </tbody>
                        <tbody>
                        <tr>
                            <td></td>
                            <td class="font-weight-bold" colspan="5">NAMA PRODUK</td>
                            <td class="font-weight-bold">QTY</td>
                            <td class="font-weight-bold" colspan="2">RINCIAN HARGA</td>
                        </tr>`
                // console.log(obj_nested)
                obj_nested.orders.forEach((order, _index) => {
                    console.log(order)
                    table_proses += `<tr>
                                        <td></td>
                                        <td class="font-weight-medium text-primary" colspan="5">
                                            ${ order.nama_barang }
                                        <td>${ order.qty }</td>
                                        <td colspan="2">${ 'Rp '+(order.qty * order.harga_order).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")  }</td>
                                    </tr>`
                })
                table_proses += `</tbody>`
            });
            return table_proses
        }
        function tableFiller(response) {
            response.forEach((obj,_index)=>{
                console.log(obj)
                table_proses = tbodyFiller(obj)
                if (obj.tagihans[0].trackings.length > 0) {
                    if (obj.tagihans[0].trackings[0].status != "ARRIVED"
                    // && obj.tagihans[0].status != "LUNAS"
                  ) {
                        tablePesananKirim.append(table_proses)
                    }
                }else{
                    tablePesananProses.append(table_proses)
                }
            })
        }
        $.ajax({
            url: '{{ url("data/order/$id/pesanan-proses") }}',
            type: 'GET',
            success: (response) => {
                console.log(response)
                tableFiller(response)
                //confirm pesana

                //pembayaran
                response.forEach((obj, _index) => {
                    obj.tagihans.forEach((tagihan, _index) => {
                        $(`#btn-pay${tagihan.id}`).click(function() {
                            tagihanCollections=[]
                            $('#modalPembayaran').modal('show')
                            // $('#modalTagihan').modal('hide')
                            $headTable = $('#thead')
                            $table = $('#tb_pembayaran')
                            $noTagihanText = $('#noTagihanText')
                            $table.empty();
                            let tagihan_id = []
                            let po_id = []
                            // const id = $(this).data('id')
                            // const no_nota = $(this).data('no_nota')
                            // const data = table.row().data()[id]

                            // $('input[name=id]').val(data.id)
                            // console.log(data)
                            var templatePbyn = `
                                <thead id="thead">
                                    <tr style="text-align: center;">
                                        <th>No.</th>
                                        <!-- <th>Tagihan</th> -->
                                        <th>Jumlah Bayar</th>
                                        <th>Metode Pembayaran</th>
                                        <th>Upload Bukti Pembayaran</th>
                                    </tr>
                                </thead>
                            `;
                            let accumulationAmount = 0
                            // data.tagihans.forEach((tagihan, _index) => {
                            var total= tagihan.nominal_total != null ? 'Rp ' + tagihan.nominal_total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") : '-';
                            tagihan_id.push(tagihan.id)
                            po_id.push(tagihan.po_id)
                            tagihanCollections.push(tagihan)

                            templatePbyn += `
                                <tbody class="table-section" data-plugin="tableSection">
                                    <tr style="cursor: pointer; text-align: center;">
                                        <!-- <td class="text-center"></i></td> -->
                                        <td> ${ _index+1 } </td>
                                        <!-- <td> Tagihan ${ _index+1 }</td> -->
                                        <!-- <td class="font-weight-medium text-danger">${ total }</td> -->
                                        <td class="font-weight-medium text-danger"><p style="display:flex" id="paid${tagihan.id}">Rp <input type="number" id="uangTerkirim${tagihan.id}" name="uangTerkirim" value="${parseInt(tagihan.nominal_total)}" max="${parseInt(tagihan.nominal_total)}" class="form-control" style="max-width:100%; margin-left:10px; margin-right: 0; padding-right:0;"></p></td>
                                        <td class="font-weight-medium text-danger">
                                        <input type="hidden" id="id_tagihan" name="id_tagihan" value="${tagihan.id}">
                                        <select class="form-control option-bayar" id="metode" required="">
                                            <option value="Alfamart/Indomaret">Alfamart/Indomaret</option>
                                            <option value="Dana">Dana</option>
                                            <option value="Go-Pay">GO-Pay</option>
                                            <option value="Link Aja">Link Aja</option>
                                            <option value="OVO">OVO</option>
                                            <option vlaue="Transfer Bank">Transfer Bank</option>
                                        </select>
                                        </td>
                                        <td class="text-center">`

                                    templatePbyn += `
                                        <span id="uploadStatus${tagihan_id}"></span>
                                        `

                            templatePbyn += `
                                        <button class="btn btn-sm btn-icon btn-pure btn-default on-default hide${tagihan.id}" onclick="collectTagihan(${_index+1})" data-target="#uploadBukti" data-toggle="modal" type="button" data-original-title="Bayar">
                                            <a href="#modalPembayaran" data-toggle="tooltip" data-original-title="UploadBukti"><i class="icon md-upload" aria-hidden="true"></i></a>
                                        </button>
                                        </td>
                                    </tr>
                                </tbody>`
                            // ===================== reactivate again to display the table =========================
                            // $table.append(templatePbyn)
                            accumulationAmount = parseInt(tagihan.nominal_total)
                            let amountStr = accumulationAmount.toString()
                            // });
                            console.log(tagihanCollections)
                            let paymentForeigns = [tagihan_id, po_id]

                            // =========================== FORM ============================
                            $('#accumulation').html(amountStr.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."))
                            $('#jumlahBayarInput').val(`${parseInt(tagihan.nominal_total)}`)
                            $noTagihanText.html(`${tagihan.no_tagihan}`)
                            // ============================FORM =============================

                            // console.log('notagihan', tagihan.no_tagihan)
                            paymentAjax(paymentForeigns, tagihanCollections, accumulationAmount, tagihan.id)
                            $('input').keydown(function (e) {
                                if (e.keyCode == 13) {
                                    e.preventDefault();
                                    return false;
                                }
                            });
                        });
                    })
                })

                $('.arrived').on('click', function (e) {
                    e.preventDefault();
                    const url = $(this).attr('href');
                    swal({
                        title: 'Pesanan Diterima !',
                        text: 'Apakah anda menerima pesanan ?',
                        icon: 'info',
                        buttons: ["Tidak", "Ya"],
                    }).then(function(value) {
                        if(value) {
                            window.location.href = url;
                        }
                    })
                })

                function paymentAjax(paymentForeigns, tagihanCollections, accumulationAmount, tagihan_id){
                    $.ajax({
                        url: '{{ url("/order/getPayment") }}',
                        type: 'GET',
                        async: false,
                        data: {data: paymentForeigns},
                        success: (response) => {
                            console.log(response)
                            if (response.length > 0) {
                                response.forEach((tagihan, _index) => {
                                    console.log(tagihan)
                                    if (tagihan_id == tagihan.tagihan_id) {
                                        if (tagihan.valid == 0) $(`#uploadStatus${tagihan.tagihan_id}`).html('<span style="color:red">Bukti tagihan ditolak</span>');
                                        else if (tagihan.valid == 9) $(`#uploadStatus${tagihan.tagihan_id}`).html('<span style="color:orange">Menunggu konfirmasi admin</span>');
                                        else if (tagihan.valid == 1) $(`#uploadStatus${tagihan.tagihan_id}`).html('<span>Pembayaran diterima sebagian</span>');
                                        else if (tagihan.valid == 2) $(`#uploadStatus${tagihan.tagihan_id}`).html('<span style="color: green">Pembayaran diterima semua</span>');

                                        if (tagihan.valid == 0) $(`.inpBukti`).html('<span style="color:red">Bukti tagihan ditolak</span>');
                                        else if (tagihan.valid == 9) $(`.inpBukti`).html('<span style="color:orange">Menunggu konfirmasi admin</span>');
                                        else if (tagihan.valid == 1) $(`.inpBukti`).html('<span>Pembayaran diterima sebagian</span>');
                                        else if (tagihan.valid == 2) $(`.inpBukti`).html('<span style="color: green">Pembayaran diterima semua</span>');

                                        if(tagihan.valid == 2 || tagihan.valid == 1){
                                            $accumulationAmount = accumulationAmount - tagihan.nominal_bayar;
                                            $('#accumulation').html($accumulationAmount.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."))
                                        }
                                    }

                                    $(`#uangTerkirim${tagihan.tagihan_id}`).remove();
                                    $(`#paid${tagihan.tagihan_id}`).css("display", "block");
                                    $(`#paid${tagihan.tagihan_id}`).html("Rp "+tagihan.nominal_bayar.replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."));
                                    $(`.hide${tagihan.tagihan_id}`).remove();
                                    // ==================== for .form_pembayaran====================
                                    $("#submitFormBayar").css("visibility", "hidden");
                                    $('.divJumlahBayar').html(`<p>${parseInt(tagihan.nominal_bayar)}</p>`)
                                })
                            }else{
                                $("#submitFormBayar").css("visibility", "visible");
                                $('.divJumlahBayar').html(`<input type="number" class="form-control" id="jumlahBayarInput" name="jumlahBayarInput" value="">`)
                                $('#jumlahBayarInput').val(`${accumulationAmount}`)
                            }
                        }
                    })
                }
                //return
                $('#btn-return').click(function() {
                    $('#modalReturn').modal('show')
                    $headTable = $('#thead')
                    $table = $('#tb_return')
                    // $table.empty();
                    let tagihan_id = []
                    let po_id = []
                    var templateRtn = '';
                    let return_tagihan_id = []
                    response.forEach((obj, _index) => {
                        obj.tagihans.forEach((tagihan, _index) => {
                            return_tagihan_id.push(tagihan.id)
                        })
                        // console.log(response)
                        // console.log(return_tagihan_id[0])
                        $.ajax({
                            url: '{{ route("return_barang") }}',
                            type: 'GET',
                            data: {data: return_tagihan_id},
                            success: (response) => {
                                // console.log(response)
                                response.forEach((order, _index) => {
                                    templateRtn += `
                                    <tbody class="table-section" data-plugin="tableSection">
                                        <tr style="cursor: pointer; text-align: center;">
                                            <td> ${ _index+1 } </td>
                                            <td> ${ _index+1 } </td>
                                            <td class="font-weight-medium">${moment(order.created_at).format('dddd, DD MMMM YYYY')}</td>
                                            <td class="font-weight-medium">${ order.qty } </td>
                                            <td class="font-weight-medium">
                                                <input id="alasan${ _index+1 }" name="alasan" type="text" value="" placeholder="Alasan Return">
                                            </td>
                                        </tr>
                                    </tbody>`
                                    $table.append(templateRtn)
                                });
                            }
                        })
                    })
                });

                $('#back1').click(function() {
                    $('#modalReturn').modal('hide')
                });
                $('#back2').click(function() {
                    $('#modalPembayaran').modal('hide');
                })
                tablePesananProses.DataTable()
                tablePesananKirim.DataTable()
            }
        })

        function format(d) {
            console.log(d)
            var template = ''

            template += `
                <table class="table">
                    <thead>
                        <tr>
                            <th>NO</th>
                            <th>NAMA PRODUK</th>
                            <th>JUMLAH PESAN</th>
                            <th>JUMLAH DISETUJUI</th>
                            <th>STATUS TERKINI</th>
                        </tr>
                    </thead>
                    <tbody>`
            $.each(d.order_awal, function(i, v) {
                console.log(v)
                                // <td style="color: blue">${v.product.barang_nama}</td>
                template += `
                            <tr>
                                <td>${i+1}</td>
                                <td style="color: blue">${v.nama_barang}</td>
                                <td>${v.qty}</td>
                                `
                if (d.order_last_status[1].status == 'BELUM DISETUJUI') {
                    template += `<td>0</td>`
                } else {
                    template += `<td>${d.order_last_status[1].qty}</td>`
                }
                template += `
                <td>${d.order_last_status[1].status}</td>
                </tr>
                `

                console.log("pp",d)
            });
            template += `</tbody>
                </table>`

            return template
        }
        // Array to track the ids of the details displayed rows
        var detailRows = [];

        $('#table-prosses tbody').on('click', 'tr td.details-control', function() {
            var tr = $(this).closest('tr');
            var row = table.row(tr);
            var idx = $.inArray(tr.attr('id'), detailRows);

            if (row.child.isShown()) {
                tr.removeClass('details');
                tr.find(".table-section-arrow").attr('style', 'transform: rotate(0deg)')
                row.child.hide();

                // Remove from the 'open' array
                detailRows.splice(idx, 1);
            } else {
                tr.addClass('details');
                tr.find(".table-section-arrow").attr('style', 'transform: rotate(-180deg)')

                row.child(format(row.data())).show();

                // Add to the 'open' array
                if (idx === -1) {
                    detailRows.push(tr.attr('id'));
                }
            }
        });
        // Modal Tagihan
        table.on('draw', function() {
            $.each(detailRows, function(i, id) {
                $('#' + id + ' td.details-control').trigger('click');
            });
        });

        $('#table-prosses').on('click', 'td .go_tagihan', function() {

            $headTable = $('#thead')
            $table = $('#tb_tagihan')

            $table.empty();
            $table.append($headTable);

            const id = $(this).data('id')
            const no_nota = $(this).data('no_nota')
            const data = table.rows().data()[id]
            console.log(data)

            $('input[name=id]').val(data.id)
            var templateTghn = '';

            // let tagihanPerNota = null
            // $.ajax({
            //     url: '{{ url("order/payment") }}',
            //     type: 'GET',
            //     success: (res) => {
            //         console.log('payment Data', res)
            //         tagihanPerNota = res
            //     }
            // })

            data.tagihans.forEach((tagihan, _index) => {

                var total = tagihan.nominal_total != null ? 'Rp ' + tagihan.nominal_total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") : '-'
                var status = driver = '';

                if (tagihan.driver != null) {
                    driver = tagihan.driver.name

                    if (tagihan.tracking_newest[0].status == 'WAITING TO PICKUP') {
                        status_delivery = `<span class="badge badge-secondary">${ tagihan.tracking_newest[0].status }</span>`;
                    } else if (tagihan.tracking_newest[0].status == `SENDING`) {
                        status_delivery = `<span class="badge badge-primary">${ tagihan.tracking_newest[0].status }</span>`;
                    } else {
                        status_delivery = `<span class="badge badge-success">${ tagihan.tracking_newest[0].status }</span>`;
                    }

                } else {
                    status_delivery = '-'
                    driver = '-'
                }

                templateTghn += `
                        <tbody class="table-section" data-plugin="tableSection">
                            <tr style="cursor: pointer; text-align: center;">
                                <td class="text-center"><i class="table-section-arrow"></i></td>
                                <td>
                                    ${ _index+1 }
                                </td>
                                <td>
                                    Tagihan ${ _index+1 }
                                    <input type="hidden" id="id_tagihan" name="id_tagihan" value="${tagihan.id}">
                                </td>
                                <td class="font-weight-medium" >
                                    ${moment(tagihan.created_at).format('dddd, DD MMMM YYYY')}
                                </td>
                                <td class="font-weight-medium text-secondary">
                                    ${ driver } <i class="icon md-truck" aria-hidden="true"></i> ${status}
                                </td>
                                <td class="font-weight-medium text-danger">
                                    ${ total }
                                </td>
                                <td class="font-weight-medium text-danger">
                                    ${ tagihan.status }
                                </td>
                                <td class="font-weight-medium text-danger">
                                    ${ status_delivery }
                                </td>
                                <td class="font-weight-medium text-primary">`

                templateTghn += `<a href="{{route('cetak_tagihan')}}?no_nota=${ no_nota }&id_tagihan=${ tagihan.id }" target="_blank" type="button" name="" id="" class="btn btn-xs btn-primary"><i class="icon md-money-box" aria-hidden="true"></i>Tagihan</a>`
                templateTghn += `
                `
                templateTghn += `<a id="btn-pay${tagihan.id}" name="pembayaran" data-toggle="modal" href="no_nota=${ no_nota }&id_tagihan=${ tagihan.id }" type="button" class="btn btn-xs btn-warning pembayaran" style="ml-2"><i class="icon md-money" aria-hidden="true"></i>Bayar</a>`
                templateTghn += `
                `
                if (tagihan.tracking_newest.length>0) {
                    if(tagihan.tracking_newest[0].status == 'ARRIVED WITH RETURN') {
                        templateTghn +=`<a id="btn-return" name="return" data-toggle="modal" href="no_nota=${ no_nota }&id_tagihan=${ tagihan.id }" type="button" class="btn btn-xs btn-danger"><i class="icon md-assignment-return" aria-hidden="true"></i>Return</a>`
                    }
                }
                templateTghn +=`
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>`
                templateTghn += `<tr style="text-align: center">
                                    <td></td>
                                    <td class="font-weight-bold" colspan="5">NAMA PRODUK</td>
                                    <td class="font-weight-bold">QTY</td>
                                    <td class="font-weight-bold" colspan="2">RINCIAN HARGA</td>
                                </tr>`

                tagihan.orders.forEach((order, _index2) => {
                    templateTghn += `
                            <tr>
                                <td></td>
                                <td class="font-weight-medium text-primary" colspan="5">
                                    ${ order.product.nama }
                                </td>
                                <td>${ order.qty }</td>
                                <td colspan="2">${  'Rp '+(order.qty * order.product.harga).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")  }</td>
                            </tr>`
                })
            // foreach
                templateTghn += `</tbody>`


                $table.append(templateTghn)
                templateTghn = ''
                // console.log(_index);
            }) // foreach
            //pembayaran
            // table.on('draw', function() {
            //     $.each(detailRows, function(i, id) {
            //         $('#' + id + ' td.details-control').trigger('click');
            //     });
            // });

        })

        //button submit return
        $('#returns').click(function () {
            toastr.options = {
                positionClass: 'toast-bottom-right',
            }
            $.ajax({
                url: `{{ route("reasons") }}`,
                type: 'POST',
                data: data,
                success: (res) => {
                    toastr["success"]("Berhasil Memberikan Alasan Return")
                }
            })
        })

        $('.btn-pickup').click(function() {
            toastr.options = {
                positionClass: 'toast-bottom-right',
            }

            const data = $('#form-list-pickup').serialize()
            $.ajax({
                url: `{{ route("storePickup") }}`,
                type: 'POST',
                data: data,
                success: (res) => {
                    // console.log(res);
                    toastr.options.onShown = () => window.location.reload(true)
                    toastr["success"]("Berhasil mengubah data")
                }
            })
        })

        $('.btn-confirm').click(function() {

            toastr.options = {
                positionClass: 'toast-bottom-right',
            }

            const data = $('#form-list-pickup').serialize()
            $.ajax({
                url: `{{ route("storeConfirm") }}`,
                type: 'POST',
                data: data,
                success: (res) => {
                    // console.log(res);
                    toastr.options.onShown = () => window.location.reload(true)
                    toastr["success"]("Berhasil mengubah data")
                }
            })
        })

        $('.btn-arrive').click(function() {
            toastr.options = {
                positionClass: 'toast-bottom-right',
            }

            const data = $('#form-list-pickup').serialize()
            $.ajax({
                url: `{{ route("storeArrive") }}`,
                type: 'POST',
                data: data,
                success: (res) => {
                    // console.log(res);
                    toastr.options.onShown = () => window.location.reload(true)
                    toastr["success"]("Berhasil mengubah data")
                }
            })
        })

        var pending_column = ["id", "nama_produk", "no_nota", "tgl_pesan", "harga_order", "qty"];
        var return_column = ["id", "tgl_return", "nama_produk", "no_nota", "qty", "alasan", "status"];
        dataTable("#tabel_pending", pending_column, 'pending');
        dataTable("#tabel_return", return_column, 'return');
        $(document).ready(function() {
            $("#tabel_riwayat").DataTable();
            $("#table-pesanan-selesai").DataTable();
        });
    })
    // })

    $(`#form-bayar`).on('submit', function(e) {
        e.preventDefault()
        toPayment = []
        toPayment.push(tagihanCollections[0])
        console.log(toPayment)
        let id = toPayment[0]['id']
        let po_id = toPayment[0]['po_id']
        // let nominal_terkirim = $(`input[type=number]#uangTerkirim${toPayment[0]['id']}`).val()
        $('.inpHidden').empty()
        $(".inpHidden").append(`
            <input type='hidden' name='id' value='${id}'>
            <input type='hidden' name='po_id' value='${po_id}'>
        `)
        let form_data2 = new FormData(this);
        $.ajax({
            url: `{{ url('/order/upload') }}`,
            // encType: 'multipart/form-data',
            type: 'POST',
            contentType: false,
            // cache: false,
            // dataType: 'JSON',
            processData: false,
            data: form_data2,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: (res) => {
                $('.modal').modal('hide')
                toastr["success"]("Berhasil Upload Bukti Transfer")
            },
            error: (res) => {
                console.log(res.responseJSON);
                toastr["error"](res.responseJSON.message.jumlahBayarInput)
            }
        })
        console.log(new FormData(this))
    })

    $('#formImg').on('submit',function(e) {
        e.preventDefault();
        $('#uploadBukti').modal('hide');
        toPayment = []
        toPayment.push(tagihanCollections[0])
        console.log(toPayment)
        let id = toPayment[0]['id']
        let po_id = toPayment[0]['po_id']
        let nominal_total = toPayment[0]['nominal_total']
        let nominal_terkirim = $(`input[type=number]#uangTerkirim${toPayment[0]['id']}`).val()
        // console.log("nominal terkirim  ", $(`input[type=number]#uangTerkirim${toPayment[0]['id']}`).val())
        $("#wrapper-hidden").empty()
        $("#wrapper-hidden").append(`
            <input type='hidden' name='id' value='${id}'>
            <input type='hidden' name='po_id' value='${po_id}'>
            <input type='hidden' name='nominal_total' value='${nominal_total}'>
            <input type='hidden' name='nominal_terkirim' value='${nominal_terkirim}'>
        `)
        let form_data2 = new FormData(this);
        // form_data.append('data', toPayment)
        if(nominal_terkirim > nominal_total || nominal_terkirim < 100) toastr["error"]("Pembayaran melebihi limit / nilai field dibawah Rp 100")
        else
        $.ajax({
            url: `{{ url('/order/upload') }}`,
            // encType: 'multipart/form-data',
            type: 'POST',
            contentType: false,
            // cache: false,
            // dataType: 'JSON',
            processData: false,
            data: form_data2,
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: (res) => {
                console.log(res);
                // $accumulation = parseInt(res.tagihan_awal[0].nominal_total)-parseInt(res.data.nominal_bayar)
                // $('#accumulation').html($accumulation.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."))
                $(`#uploadStatus${res.data.tagihan_id}`).html('Telah di upload');
                console.log('obtained index ',obtainedIndex)
                $('.dropify-clear').click();
                // toPayment = []
                $('.md-upload').hide();
                $('.on-default').hide();
                toastr["success"]("Berhasil Upload Bukti Transfer")
            },
            error: (res) => {
                console.log(res.responseJSON);
                toastr["error"](res.responseJSON.message.inputBukti[0])
            }
        })
        console.log(toPayment[0]['id']);
    })

    $(document).on('change', '#inputBukti', function(e) {
        e.preventDefault();
        var name = document.getElementById("inputBukti").files[0].name;
        var ext = name.split('.').pop().toLowerCase();

        var oFReader = new FileReader();
        oFReader.readAsDataURL(document.getElementById("inputBukti").files[0]);
        var f = document.getElementById("inputBukti").files[0];
        newImg = f
        // console.log(form_data)
    })

    function btnRefresh() {
        table.ajax.reload();
    }

    function dataTable(table, column, url) {
        var $table = table;
        var $url = "{{url('/profile_')}}" + url;

        var $column = []
        for (i in column) {
            var a;
            if (i == 0) {
                $column.push({
                    "data": column[i],
                    "name": column[i],
                    "orderable": false,
                    "searchable": false,
                    "render": function(data, type, row, meta) {
                        return meta.row + 1;
                    }
                })
            } else {
                $column.push({
                    "data": column[i],
                    "name": column[i]
                })
            }
        }

        table2 = $($table).DataTable({
            processing: true,
            serverSide: false,
            dom: '',
            ajax: {
                url: $url,
                type: "GET"
            },
            columns: $column
        });
    }
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection
