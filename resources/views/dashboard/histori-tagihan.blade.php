@extends('template.pages.datatable', [
'page' => 'Histori Tagihan',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => '', 'active' => ''],
['nama' => 'Tagihan', 'link' => '', 'active' => ''],
['nama' => 'Histori Tagihan', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead id="thead">
        <tr>
            <th>No.</th>
            <th>
                No. Nota
            </th>
            <th>
                No. Tagihan
            </th>
            <th>
                Pembeli
            </th>
            <th>
                Status Pembayaran
            </th>
            <th>
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
<div class="modal fade example-modal-lg modal-3d-sign" id="modalDetail" aria-hidden="true" aria-labelledby="modalDetail" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" style="color: blue">Detail Tagihan</h4>
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
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-dismiss="modal">Back</button>
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
                data.forEach((payment, index) => {
                    console.log(payment.id)
                    var d = new window.Date(payment.po.created_at);
                    var day = d.getDate();
                    var month = d.getMonth();
                    var year = d.getFullYear();
                    var dd = day + '-' + month + '-' + year;
                    var template = `
                                <tbody class="table-section" data-plugin="tableSection">
                                    <tr style="cursor: pointer">
                                        <td class="font-weight-medium">
                                            ${ index+1 }
                                        </td>
                                        <td class="font-weight-medium">
                                            ${ payment.po.no_nota }
                                        </td>
                                        <td class="font-weight-medium">
                                            ${ payment.po.tagihans[0].no_tagihan }
                                        </td>
                                        <td>
                                            <span class="font-weight-medium">${ payment.po.user.name }</span>
                                        </td>
                                        <td>
                                        `
                                        
                                        if (payment.po.tagihans[index].status == 'LUNAS') template+=`<span class="badge badge-success font-weight-medium">${ payment.po.tagihans[index].status }</span>`
                                        else if (payment.po.tagihans[index].status == 'DIBAYAR SEBAGIAN') template+=`<span class="badge badge-info font-weight-medium">${ payment.po.tagihans[index].status }</span>`
                                        else if (payment.po.tagihans[index].status == 'BELUM DIBAYAR') template+=`<span class="badge badge-danger font-weight-medium">${ payment.po.tagihans[index].status }</span>`
                                        else template+=`<span class="font-weight-medium">___</span>`
                                        
                                        template+=`
                                        </td>
                                        <td>
                                            <span class="text-muted">${ dd }</span>
                                        </td>
                                        <td>
                                            ${ payment.po.user.address }
                                        </td>
                                        <td align="center">
                                            <button class="btn btn-sm btn-icon btn-pure btn-default on-default btn-detail" id="${ payment.po.id }"  onclick="detail(${payment.po.id})" type="button" data-original-title="Detail">
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