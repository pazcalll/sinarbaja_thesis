@extends('template.pages.datatable', [
'page' => 'Tagihan',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Pesanan', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Tagihan', 'link' => '', 'active' => 'active']
]
])

@section('bottom-panel')
<button type="button" class="btn btn-primary waves-effect waves-classic" data-target="#modal-select-driver" data-toggle="modal">
    Kirim Tagihan
</button>
@endsection

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead id="thead">
        <tr>
            <th class="w-50">
            </th>
            <th>
                No. Nota
            </th>
            <th>
                Pembeli
            </th>
            <th class="hidden-sm-down w-200">
                Tanggal Pesan
            </th>
            <th>
                Alamat
            </th>
            <th>
                Aksi
            </th>
        </tr>
    </thead>

</table>
@endsection

@section('modal')
<div class="modal fade modal-fade-in-scale-up" id="modal-select-driver" aria-hidden="true" aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Pilih Driver</h4>
            </div>
            <div class="modal-body">
                <select id="list-driver" class="form-control select2-hidden-accessible" data-plugin="select2" data-select2-id="1" tabindex="-1" aria-hidden="true">
                </select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary btn_simpan">Simpan</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade example-modal-lg modal-3d-sign" id="modalDetail" aria-hidden="true" aria-labelledby="modalDetail" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Detail Tagihan</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Daftar tagihan</h4>
                    <div class="example">
                        <div class="table-responsive">
                            <table class="table" id="detailTagihan">
                                <thead id="thead_detail">
                                    <tr>
                                        <th>No.</th>
                                        <th>No. Nota</th>
                                        <th>Pembeli</th>
                                        <th>Barang</th>
                                        <th>Tanggal Jatuh Tempo</th>
                                        <th>Total Pesanan</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        $headTable = $('#thead')
        $table = $('#exampleAddRow')
        $.ajax({
            url: '{{ url('/data/purchase-order/tagihan') }}',
            type: 'GET',
            success: (response) => {
                $table.DataTable().destroy()
                $table.empty()
                $table.append($headTable)
                let {
                    data
                } = response
                console.log(data);
                var i = 1;
                data.forEach((po, index) => {
                    console.log(po.id)
                    var d = new window.Date(po.created_at);
                    var day = d.getDate();
                    var month = d.getMonth();
                    var year = d.getFullYear();
                    var dd = year + '-' + month + '-' + day;
                    var template = `
                                <tbody class="table-section" data-plugin="tableSection">
                                    <tr style="cursor: pointer">
                                        <td width="">
                                            <div class="checkbox-custom checkbox-warning">
                                                <input type="checkbox" id="check" name="check[]" value="${ index }">
                                                <label for="inputUnchecked"></label>
                                            </div>
                                        </td>
                                        <td class="font-weight-medium">
                                            ${ po.no_nota }
                                        </td>
                                        <td>
                                            <span class="font-weight-medium">${ po.user.name }</span>
                                        </td>
                                        <td class="hidden-sm-down">
                                            <span class="text-muted">${ dd }</span>
                                        </td>
                                        <td width="40%">
                                            ${ po.user.address }
                                        </td>
                                        <td align="center">
                                            <button class="btn btn-sm btn-icon btn-pure btn-default on-default btn-detail" id="${ po.id }"  onclick="detail(${po.id})" type="button" data-original-title="Detail">
                                                <a href="#" data-toggle="tooltip" data-original-title="Detail"><i class="icon md-eye" aria-hidden="true"></i></a>
                                            </button>
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody>`
                    template += '</tbody>'
                    $table.append(template)
                }) // foreach
                $table.DataTable()
            } // on success
        }) // ajax

        $.ajax({
            url: '{{ url('data/drivers') }}',
            type: 'GET',
            success: (response) => {
                console.log(response);
                let {
                    data
                } = response
                data.forEach((driver, index) => {
                    $('#list-driver').append(
                        `<option value="${ driver.id }">${ driver.user.name }</option>`
                    )
                })
            }
        })
    })

    function detail(clicked_id) {
        $("#modalDetail").modal("show");
        $headTable = $('#thead_detail')
        $table = $('#detailTagihan')
        $table.empty()
        // $table.DataTable().destroy()

        $.ajax({
            url: '{{ url('/data/purchase-order/detailTagihan') }}',
            type: 'POST',
            dataType: 'json',
            data: {
                po_id: clicked_id,
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                console.log(clicked_id);
                $table.append($headTable)
                let {
                    data
                } = response
                var i = 1;
                data.forEach((order, index) => {
                    var d = new window.Date(order.po.jatuh_tempo);
                    var day = d.getDate();
                    var month = d.getMonth();
                    var year = d.getFullYear();
                    var dd = year + '-' + month + '-' + day;
                    var jum = order.qty * order.product.harga;
                    var template = `
                                <tbody class="table-section" data-plugin="tableSection">
                                    <tr style="cursor: pointer">
                                        <td class="font-weight-medium">
                                            ${ i++ }
                                        </td>
                                        <td class="font-weight-medium">
                                            ${ order.po.no_nota }
                                        </td>
                                        <td class="font-weight-medium">
                                            ${ order.po.user.name }
                                        </td>
                                        <td class="font-weight-medium">
                                            ${ order.product.nama }
                                        </td>
                                        <td class="font-weight-medium">
                                            ${ dd }
                                        </td>
                                        <td class="font-weight-medium">
                                            ${ jum }
                                        </td>
                                        <td class="font-weight-medium">
                                            ${ order.qty }
                                        </td>

                                    </tr>
                                </tbody>
                                <tbody>`
                    template += '</tbody>'
                    $table.append(template)
                }) // foreach
                $table.DataTable()
            } // on success
        }) // ajax

    }
</script>
@endsection