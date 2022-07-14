<style>
    .details-control{
        cursor: pointer;
    }
</style>
<div class="container nowrap" style="width: 100%; right: 0px; background-color: white;" id="main-content">
    <h2>Pesanan Selesai</h2>
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
            </tr>
        </thead>
        <tbody>
            
        </tbody>
    </table>
</div>

<script>
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
                        <td>${new Intl.NumberFormat('id-ID', {style: "currency", currency: "IDR", minimumFractionDigits: 0}).format(item.harga)}</td>
                    </tr>
                `
            });
            let closerTbl = openingTbl + `
                </tbody>
            </table>
            `
            return closerTbl;
        }
        let tableSend = $('#tbl_send').DataTable({
            ajax: '{{route("completed_list")}}',
            processing: true,
            serverSide: true,
            searching: false,
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
                {
                    data: 'total_harga',
                    render: (data, type, row, meta) => {
                        return new Intl.NumberFormat('id-ID', {style: "currency", currency: "IDR", minimumFractionDigits: 0}).format(row.total_harga)
                    }
                }
            ]
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