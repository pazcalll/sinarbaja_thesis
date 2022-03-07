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
                        <li class="nav-item" role="presentation"><a class="active nav-link" data-toggle="tab" href="#proses" aria-controls="proses" role="tab">Pesanan Barang</a></li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active animation-slide-left" id="proses" role="tabpanel">
                            <div class="example-wrap">
                                <div class="table-responsive">
                                    <table class="table table-bordered table-hover table-striped" id="table-pickup">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>No.</th>
                                                <th>Toko</th>
                                                <th>Alamat</th>
                                                <th>Tanggal Pesan</th>
                                                <th>Tanggal Kirim</th>
                                                <th>Status</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Panel -->
            </div>
        </div>
    </div>
@endsection

@section('modal')
    <div class="modal fade example-modal-lg modal-3d-sign" id="modalReturn" aria-hidden="true" aria-labelledby="modalReturn" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-simple modal-center modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title">Return Pesanan</h4>
                </div>
                <div class="modal-body">
                    <div class="example-wrap">
                        <br>
                        <h4 class="example-title text-center">Daftar produk yang akan di return</h4>
                        <div class="example">
                            <div class="table-responsive">
                                <form id="form-list-return">
                                    @csrf
                                    <input type="hidden" name="id">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th></th>
                                                <th>Nama Produk</th>
                                                <th>Qty</th>
                                            </tr>
                                        </thead>
                                        <tbody class="tbody-return">
                                        </tbody>
                                    </table>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group form-material col-xl-12">
                    <button type="button" class="btn btn-default" id="resetBtn">Reset
                    </button>
                    <button type="submit" class="btn btn-primary btn-return" id="validateButton1">Return
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade example-modal-lg modal-3d-sign" id="modalPickup" aria-hidden="true" aria-labelledby="modalPickup" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-simple modal-center modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h4 class="modal-title modal-title-pickup">Detail Pengiriman Barang</h4>
                </div>
                <div class="modal-body">
                    <div class="example-wrap">
                        <br>
                        <h4 class="example-title text-center title-pickup">Daftar barang yang akan dikirim</h4>
                        <div class="example">
                            <div class="table-responsive">
                                <form id="form-list-pickup">
                                    @csrf
                                    <input type="hidden" name="id">
                                    <input hidden type="text" name="target">
                                    <input hidden type="text" name="po_id">

                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Nama Produk</th>
                                                <th>Qty Kirim</th>
                                                <th class="col-qty-datang" >Qty Diterima Agen</th>
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
                    <button type="submit" class="btn btn-primary btn-pickup">Pickup</button>
                    <button type="submit" class="btn btn-primary btn-arrive" hidden>Selesai Pengiriman</button>
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
@endsection

@section('js')
    <script>
        var table2;
        var user = `{{Auth::user()->group_id}}`;
        var table;

        $(document).ready(function() {
            // console.log(user);
                table = $('#table-pickup').DataTable({
                    processing: true,
                    serverSide: true,
                    bInfo: false,
                    ajax: `{{ url("data/order/$id/prosses") }}`,
                    columns: [
                        {
                            "class":          "details-control",
                            "orderable":      false,
                            "data":           null,
                            "defaultContent": "",
                            render: (data, type, row, meta) => {
                                return '<i class="table-section-arrow">'
                            }                            
                        },                          
                        {
                            data: null,
                            render: (data, type, row, meta) => {
                                return meta.row+1
                            }
                        },
                        {
                            data: 'po.user.name'               
                        },
                        {
                            data: 'po.user.address'               
                        },
                        {
                            data: 'po.created_at',
                            render: (data, type, row, meta) => moment(data).format('dddd, DD MMMM YYYY')
                        },
                        {
                            data: 'tgl_dikirim',
                            render: (data, type, row, meta) => {
                                // console.log(row.trackings);
                                if (row.trackings.length > 1 ) {
                                    return moment(row.trackings[1].created_at).format('dddd, DD MMMM YYYY')
                                }else{
                                    return '-';
                                }
                                
                            }
                        },
                        {
                            data: 'tracking_newest.0.status',
                        },
                        {
                            data: 'id',
                            render: (data, type, row, meta) => {
                                const status = row.tracking_newest[0].status
                                    if (status == 'WAITING TO PICKUP') {
                                        return `<button data-id="${ meta.row }" class="btn btn-sm btn-icon btn-pure btn-default on-default go_pickup" data-target="#modalPickup" type="button">
                                                    <a href="#" data-toggle="tooltip" data-original-title="Return"><i class="icon md-truck" aria-hidden="true"></i></a>
                                                </button>`
                                    } else if(status == 'SENDING'){
                                        return `<button data-id="${ meta.row }" class="btn btn-sm btn-icon btn-pure btn-default on-default packet_arrived" data-target="#modalArrive" type="button">
                                                    <a href="#" data-toggle="tooltip" data-original-title="Return"><i class="icon md-pin-drop" aria-hidden="true"></i></a>
                                                </button>`   
                                    } else if(status == 'ARRIVED' || 'ARRIVED WITH RETURN'){
                                        return `-`   
                                    }
                            }
                        }
                    ]
                });

            function format ( d ) {
                console.log(d);
                var template = ''

                template += `
                <table class="table">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Produk</th>
                            <th>Jumlah yang dikirim</th>
                        </tr>
                    </thead>
                    <tbody>`
                $.each(d.orders, function (i, v) { 
                    template += `
                            <tr>
                                <td scope="row">${i+1}</td>
                                <td>${v.product.nama}</td>
                                <td>${v.qty}</td>
                            </tr>` 
                });
                template += `</tbody>
                </table>`

                return template
            }

            // Array to track the ids of the details displayed rows
            var detailRows = [];
        
            $('#table-pickup tbody').on( 'click', 'tr td.details-control', function () {
                var tr = $(this).closest('tr');
                var row = table.row( tr );
                var idx = $.inArray( tr.attr('id'), detailRows );
        
                if ( row.child.isShown() ) {
                    tr.removeClass( 'details' );
                    tr.find(".table-section-arrow").attr('style', 'transform: rotate(0deg)')
                    row.child.hide();
        
                    // Remove from the 'open' array
                    detailRows.splice( idx, 1 );
                }
                else {
                    tr.addClass( 'details' );
                    tr.find(".table-section-arrow").attr('style', 'transform: rotate(-180deg)')

                    row.child( format( row.data() ) ).show();
        
                    // Add to the 'open' array
                    if ( idx === -1 ) {
                        detailRows.push( tr.attr('id') );
                    }
                }
            } );
        
            // On each draw, loop over the `detailRows` array and show any child rows
            table.on( 'draw', function () {
                $.each( detailRows, function ( i, id ) {
                    $('#'+id+' td.details-control').trigger( 'click' );
                } );
            } );

            function renderDetailOrder(data) {
                // console.log(data);
                data.orders.forEach((element,index) => {
                        //    <div class="checkbox-custom checkbox-warning">
                        //         <input type="checkbox" id="inputUnchecked" name="id_order[]" value="${ element.id }">
                        //         <label for="inputChecked"></label>
                        //     </div>                    
                    const view = `
                    <tr>
                        <td>
                            ${++index}
                            <input hidden type="text" name="id_order[]" value="${ element.id }">
                        </td>
                        <td class="font-weight-medium">
                            ${ element.product.nama }
                        </td>
                        <td width="15%">
                            <div class="form-group form-material">
                                <input type="text" disabled class="form-control-plaintext qty" data-min="1" data-max="1000000000" data-stepinterval="50" data-maxboostedstep="10000000" value="${ element.qty }" name="${ element.id }" />
                            </div>
                        </td>
                        <td width="15%" class="col-qty-datang">
                            <input type="text" class="form-control qty" data-min="1" data-max="1000000000" data-stepinterval="50" data-maxboostedstep="10000000" name="${ element.id }" />
                        </td>
                    </tr>
                    `

                    $el.append(view)
                })                
            }

            $('#table-pickup').on('click', 'td .go_pickup', function() {
                $el = $('.tbody-pickup')
                $el.empty()

                const id = $(this).data('id')
                const data = table.rows().data()[id]

                $('input[name=id]').val(data.id)
                $('input[name=target]').val(data.po.user.group_id)
                $('input[name=po_id]').val(data.po.id)
                renderDetailOrder(data)
                $('.col-qty-datang').attr('hidden', true);

                $('.modal-title-pickup').html('Detail Pengiriman Barang');
                $('.title-pickup').html('Daftar barang yang akan dikirim');

                $('.btn-pickup').attr('hidden',false);
                $('.btn-arrive').attr('hidden',true);
                $('#modalPickup').modal({
                    backdrop: 'static',
                    keyboard: false, // to prevent closing with Esc button (if you want this too)
                    show: true
                })                 
            })

            $('#table-pickup').on('click', 'td .packet_arrived', function() {
                $el = $('.tbody-pickup')
                $el.empty()

                const id = $(this).data('id')
                const data = table.rows().data()[id]

                $('input[name=id]').val(data.id)
                $('input[name=target]').val(data.po.user.group_id)
                $('input[name=po_id]').val(data.po.id)                
                renderDetailOrder(data)
                $('.col-qty-datang').attr('hidden', false);

                $('.modal-title-pickup').html('Detail Pengiriman Barang');
                $('.title-pickup').html('Daftar barang yang tiba ditujuan');

                $('.btn-pickup').attr('hidden',true);
                $('.btn-arrive').attr('hidden',false);                
                $('#modalPickup').modal({
                    backdrop: 'static',
                    keyboard: false, // to prevent closing with Esc button (if you want this too)
                    show: true
                })                
            })


            $('.btn-return').click(function() {
                toastr.options = {
                    positionClass: 'toast-bottom-right',
                }

                const data = $('#form-list-return').serialize()
                $.ajax({
                    url: `{{ url("data/order/return") }}`,
                    type: 'POST',
                    data: data,
                    success: (res) => {
                        toastr.options.onShown = () => window.location.reload(true)
                        toastr["success"]("Berhasil mengubah data")
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


            var pending_column = ["id", "nama_produk", "no_nota", "tgl_pesan", "total_pesanan", "qty"];
            var return_column = ["id", "tgl_return", "nama_produk", "no_nota", "qty", "alasan", "status"];
            dataTable("#tabel_pending", pending_column, 'pending');
            dataTable("#tabel_return", return_column, 'return');
            $("#tabel_riwayat").DataTable({ dom:'' });
        })

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
@endsection
