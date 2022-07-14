<style>
    .details-control{
        cursor: pointer;
    }
</style>
<div class="container nowrap" style="width: 100%; right: 0px; background-color: white;" id="main-content">
    <h2>Kirim Pesanan</h2>
    {{-- <a href="javascript:void(0)" onclick="$('#modalUploadExcel').modal('show')" class="card-body-title"><button class="btn btn-success"><i class="icon md-upload"></i> Upload Stock</button></a>
    <a href="{{route('export_stock')}}" class="card-body-title"><button class="btn btn-warning"><i class="icon md-download"></i> Download Stock</button></a>
    <a href="javascript:void(0)" onclick="$('#modalTruncateStock').modal('show')" class="card-body-title"><button class="btn btn-danger"><i class="icon md-delete"></i> Empty Stock</button></a> --}}
    <table class="table table-bordered table-hover table-striped" id="tbl_send">
        <thead id="thead">
            <tr>
                <th width="5%"></th>
                <th width="5%">No.</th>
                <th>No. Nota</th>
                <th width="10%">Tipe Pengguna</th>
                <th>Pembeli</th>
                <th>Tanggal Pesan</th>
                <th>Total Harga</th>
                <th>Status Pembayaran</th>
                <th>Bukti Transfer</th>
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>

@include('adminThesis.modal.modalPersetujuanTf')

<script>
    function sendOrder(status_pembayaran, id) {
        if (status_pembayaran == "BELUM DIBAYAR") toastr["error"]('Customer belum bayar')
        else if(status_pembayaran == "LUNAS"){
            $.ajax({
                url: "{{route('send_order')}}",
                type: "POST",
                data: {
                    status_pembayaran: status_pembayaran,
                    po_id: id
                },
                success: (res) => {
                    toastr['success']('Pesanan dalam proses pengiriman')
                    $('.to-send').click()
                },
                error: (err) => {
                    toastr['error'](`${err.statusText}`)
                }
            })
        }
    }
    function bill_acceptance(po_id, acceptance) {
        $.ajax({
            url: '{{route("bill_acceptance")}}',
            type: 'POST',
            data: {
                po_id: po_id,
                acceptance: acceptance
            },
            success: (res) => {
                $('#modal-persetujuan-tf').modal('hide')
                $('#modal-persetujuan-tf').on('hidden.bs.modal', function () {
                    $('.to-send').click()
                })
            }
        })
    }
    $(document).ready(function() {
        function format ( d ) {
            let newTbl = JSON.parse(d.barang.replace(/&quot;/g, '"'))
            console.log(d)
            let openingTbl = `<table style="width:100%" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>Jumlah Barang</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    `
            newTbl.forEach((item, _index) => {
                // str += 'Full name: '+item.nama_barang+' '+item.qty+'<br>'+
                //     'Salary: '+item.harga+'<br>';
                openingTbl += `
                    <tr>
                        <td>${item.nama_barang}</td>
                        <td>${item.qty}</td>
                        <td>${item.harga}</td>
                    </tr>
                `
            });
            let closerTbl = openingTbl + `
                </tbody>
            </table>
            `
            if (d.status_pembayaran == "LUNAS") 
                closerTbl += `
                    <button type="button" onclick="sendOrder('${d.status_pembayaran}', ${d.id})" class="btn btn-primary" style="width:100%">KIRIM</button>
                `
            return closerTbl;
        }
        let tableSend = $('#tbl_send').DataTable({
            ajax: '{{route("send_list")}}',
            processing: true,
            serverSide: true,
            searching: false,
            stateSave: true,
            columns: [
                {
                    class:"details-control",
                    orderable:false,
                    data: null,
                    defaultContent: "+"
                },
                {
                    data: '',
                    render: (data, type, row, meta) => {
                        return meta.row + meta.settings._iDisplayStart + 1
                    }
                },
                {data: 'no_nota'},
                {data: 'group_name'},
                {data: 'user_name'},
                {data: 'created_at'},
                {data: 'total_harga'},
                {data: 'status_pembayaran'},
                {
                    data: '',
                    render: (data, type, row, meta) => {
                        let button = `
                            <button data-po_id="${row.id}" type="button" class="btn btn-warning btn-persetujuan">Modal Persetujuan</button>
                        `
                        if (row.status_pembayaran != "BELUM DIPROSES ADMIN") {
                            button = '_'
                        }
                        return button
                    }
                }
            ],
            drawCallback: () => {
                $('.btn-persetujuan').unbind()
                $('.btn-persetujuan').on('click', function() {
                    let bukti_tf = ''
                    $.ajax({
                        url: '{{route("approval_url")}}',
                        type: 'POST',
                        data: {
                            po_id: $(this).data('po_id')
                        },
                        success: (res) => {
                            bukti_tf = res
                            $('.img').html(`
                                <a href="{{asset('storage/app/${bukti_tf}')}}" 
                                    target="_blank" 
                                    type="button"
                                    class="btn btn-xs btn-primary">
                                    <i class="icon md-money-box" aria-hidden="true"></i>Lihat Bukti Transfer
                                </a>
                            `)
                            $('#modal-persetujuan-tf .modal-footer').html(`
                                <button onclick="$('#modal-persetujuan-tf .modal-footer').html('') $('#modal-persetujuan-tf').modal('hide')" data-dismiss="modal" class="btn btn-secondary">Tutup</button>
                                <button onclick="bill_acceptance(${$(this).data('po_id')}, false)" class="btn btn-danger">Tolak</button>
                                <button onclick="bill_acceptance(${$(this).data('po_id')}, true)" class="btn btn-primary">Terima</button>
                            `)
                            $('#modal-persetujuan-tf').modal('show')
                        }
                    })
                })
            }
        })

        let detailRows = [];
    
        $('#tbl_send tbody').on( 'click', 'tr td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = tableSend.row( tr );
            var idx = $.inArray( tr.attr('id'), detailRows );
    
            if ( row.child.isShown() ) {
                tr.removeClass( 'details' );
                row.child.hide();
                $(this).html('+')

                // Remove from the 'open' array
                detailRows.splice( idx, 1 );
            }
            else {
                tr.addClass( 'details' );
                row.child( format( row.data() ) ).show();
                $(this).html('-')
    
                // Add to the 'open' array
                if ( idx === -1 ) {
                    detailRows.push( tr.attr('id') );
                }
            }
        } );

        tableSend.on( 'draw', function () {
            $.each( detailRows, function ( i, id ) {
                $('#'+id+' td.details-control').trigger( 'click' );
            } );
        } );
    })
</script>