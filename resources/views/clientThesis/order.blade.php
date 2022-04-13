@php
$id = Auth::id();
$showNavigation = false;
// $bodyType = 'site-menubar-unfold site-menubar-show site-navbar-collapse-show';
@endphp

@extends('app')

@section('css')
<style>
    td.details-control {
        cursor: pointer;
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
                    <li class="nav-item" role="presentation"><a class="active nav-link" data-toggle="tab" href="#proses" aria-controls="proses" role="tab">Pesanan Masuk</a></li>
                    <li class="nav-item pesanan_proses" role="presentation"><a class="nav-link" data-toggle="tab" href="#pesanan_proses" aria-controls="pesanan_proses" role="tab">Pesanan Proses</a></li>
                    <li class="nav-item pesanan_selesai" role="presentation"><a class="nav-link" data-toggle="tab" href="#pesanan_selesai" aria-controls="pesanan_selesai" role="tab">Pesanan Selesai</a></li>
                </ul>

                <div class="tab-content">

                    @include('clientThesis.table_contents')

                </div>
            </div>
            <!-- End Panel -->
        </div>
    </div>
</div>
@include('clientThesis.modal.modal_pembayaran')
@include('clientThesis.modal.modal_confirm_pesanan')
@endsection

@section('js')
<script>
    function formatRupiah(x) {
        let number_string = ''
        if (Number.isInteger(x)) number_string = x.toString()
        else number_string = x

        split   		= number_string.split(','),
        sisa     		= split[0].length % 3,
        rupiah     		= split[0].substr(0, sisa),
        ribuan     		= split[0].substr(sisa).match(/\d{3}/gi);

        // tambahkan titik jika yang di input sudah menjadi angka ribuan
        if(ribuan){
            separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }	
        return "Rp. "+rupiah
    }

    $(document).ready(function() {
        function format ( d ) {
            console.log(JSON.parse(d.barang.replace(/&quot;/g, '"')))
            let newTbl = JSON.parse(d.barang.replace(/&quot;/g, '"'))
            let openingTbl = `<table style="width:100%" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>qty</th>
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
                        <td>${formatRupiah(item.harga)}</td>
                    </tr>
                `
            });
            let closerTbl = openingTbl + `
                </tbody>
            </table>`
            return closerTbl;
        }
        let dt = $('#table-unaccepted').DataTable({
            ajax: '{{route("pesananBelumDisetujui")}}',
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
                {data: 'created_at'},
                {
                    data: 'total_harga',
                    render: (data, type, row, meta) => {
                        return formatRupiah(row.total_harga)
                    }
                },
            ]
        })

        let detailRows = [];
    
        $('#table-unaccepted tbody').on( 'click', 'tr td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = dt.row( tr );
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

        dt.on( 'draw', function () {
            $.each( detailRows, function ( i, id ) {
                $('#'+id+' td.details-control').trigger( 'click' );
            } );
        } );
    })

    $('.pesanan_proses').on('click', function() {
        if ($.fn.DataTable.isDataTable('#table-unpaid')) return

        function format ( d ) {
            console.log(JSON.parse(d.barang.replace(/&quot;/g, '"')))
            let newTbl = JSON.parse(d.barang.replace(/&quot;/g, '"'))
            let openingTbl = `<table style="width:100%" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>qty</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    `
            newTbl.forEach((item, _index) => {
                openingTbl += `
                    <tr>
                        <td>${item.nama_barang}</td>
                        <td>${item.qty}</td>
                        <td>${formatRupiah(item.harga)}</td>
                    </tr>
                `
            });
            let closerTbl = openingTbl + `
                </tbody>
            </table>`
            return closerTbl;
        }
        let dt = $('#table-unpaid').DataTable({
            ajax: '{{route("pesananBelumDibayar")}}',
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
                {data: 'created_at'},
                {
                    data: '',
                    render: (data, type, row, meta) => {
                        return formatRupiah(row.total_harga)
                    }
                },
                {data: 'status_pembayaran'},
                {
                    data: '',
                    render: (data, type, row, meta) => {
                        let buttons = `
                            <button data-toggle="modal" data-po_id="${row.id}" data-nota="${row.no_nota}" data-limit="${row.total_harga}" type="button" class="btn btn-warning btn-xs btn-pay"><li class="icon md-money"></li>Bayar</button>
                        `
                        if (row.status_pembayaran != 'BELUM DIBAYAR') {
                            buttons = '_'
                        }
                        return buttons
                    }
                },
            ],
            drawCallback: ()=>{
                $('.btn-pay').unbind()
                $('.btn-pay').on('click', function() {
                    $('#noNotaText').html($(this).data('nota'))
                    $('#accumulation').html(formatRupiah($(this).data('limit')))
                    // $('#jumlahBayarInput').data('limit', $(this).data('limit'))
                    $('#jumlahBayarInput').val($(this).data('limit'))
                    $('#po_id_pembayaran').val($(this).data('po_id'))
                    $('#modalPembayaran').modal('show')
                })
            }
        })

        let detailRows = [];
    
        $('#table-unpaid tbody').on( 'click', 'tr td.details-control', function () {
            console.log('asdf')
            var tr = $(this).closest('tr');
            var row = dt.row( tr );
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

        dt.on( 'draw', function () {
            $.each( detailRows, function ( i, id ) {
                $('#'+id+' td.details-control').trigger( 'click' );
            } );
        } );
    })

    $('.tab-paid').on('click', function() {
        if ($.fn.DataTable.isDataTable('#table-paid')) return

        function format ( d ) {
            console.log(JSON.parse(d.barang.replace(/&quot;/g, '"')))
            let newTbl = JSON.parse(d.barang.replace(/&quot;/g, '"'))
            let openingTbl = `<table style="width:100%" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>qty</th>
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
                        <td>${formatRupiah(item.harga)}</td>
                    </tr>
                `
            });
            let closerTbl = openingTbl + `
                </tbody>
            </table>`
            return closerTbl;
        }
        let dt = $('#table-paid').DataTable({
            ajax: '{{route("pesananLunas")}}',
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
                {data: 'created_at'},
                {
                    data: 'total_harga',
                    render: (data, type, row, meta) => {
                        return formatRupiah(row.total_harga)
                    }
                },
                {data: 'status_pembayaran'},
                {
                    data: 'kirim',
                    render: (data, type, row, meta) => {
                        let color = ''
                        
                        if(row.kirim == "BELUM") color = 'danger'
                        else if(row.kirim == "PERJALANAN") color = 'info'

                        return `
                            <span class="badge badge-${color}">${row.kirim}</span>
                        `
                    }
                },
                {
                    data: '',
                    render: (data, type, row, meta) => {
                        let buttons = `
                            <button data-nota="${row.id}" type="button" class="btn btn-primary btn-xs btn-confirm"><li class="icon md-check"></li>Pesanan Diterima</button>
                        `
                        if (row.kirim == "BELUM") return '_'
                        else return buttons
                    }
                },
            ],
            drawCallback: () => {
                $('.btn-confirm').unbind()
                $('.btn-confirm').on('click', function() {
                    $('#po_id_confirm').val($(this).data('nota'))
                    $('#confirmPesanan').modal('show')
                })
            }
        })

        let detailRows = [];
    
        $('#table-paid tbody').on( 'click', 'tr td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = dt.row( tr );
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

        dt.on( 'draw', function () {
            $.each( detailRows, function ( i, id ) {
                $('#'+id+' td.details-control').trigger( 'click' );
            } );
        } );
    })

    $('#form-bayar').on('submit', function(e) {
        e.preventDefault()
        let fd = new FormData(this);
        let myfile = $('#inputBukti')[0].files;
        $.ajax({
            url: '{{route("uploadTransfer")}}',
            type: 'POST',
            processData: false,
            contentType: false,
            data: fd,
            success: (res) => {
                $('#modalPembayaran').modal('hide')
                window.location.reload()
            },
            error: (err) => {
                console.error(err)
            }
        })
    })

    $('.pesanan_selesai').on('click', function() {
        if ($.fn.DataTable.isDataTable('#table-pesanan-selesai')) return

        function format ( d ) {
            let newTbl = JSON.parse(d.barang.replace(/&quot;/g, '"'))
            console.log(d)
            let openingTbl = `<table style="width:100%" class="table table-bordered table-hover table-striped">
                <thead>
                    <tr>
                        <th>Nama Barang</th>
                        <th>qty</th>
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
                        <td>${formatRupiah(item.harga)}</td>
                    </tr>
                `
            });
            let closerTbl = openingTbl + `
                </tbody>
            </table>
            `
            return closerTbl;
        }
        let tableSend = $('#table-pesanan-selesai').DataTable({
            ajax: '{{route("orderCompleted")}}',
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
                        return formatRupiah(row.total_harga)
                    }
                }
            ]
        })

        let detailRows = [];
    
        $('#table-pesanan-selesai tbody').on( 'click', 'tr td.details-control', function () {
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

    $('#form-confirm').on('submit', function(e) {
        e.preventDefault()
        $('#confirmPesanan').modal('hide')
        $.ajax({
            url: '{{route("confirmOrder")}}',
            type: 'POST',
            data: $(this).serialize(),
            success: (res) => {
                window.location.reload()
            },
            error: (err) => {
                console.error(err)
            }
        })
    })

    // $('#jumlahBayarInput').on('input', function() {
    //     let x = document.getElementById("jumlahBayarInput").value;
    //     let limit = parseInt($("#jumlahBayarInput").data('limit'))
    //     if (x < 0) {
    //         document.getElementById("jumlahBayarInput").value = 0
    //         x = 0
    //     }else if(x >limit){
    //         document.getElementById("jumlahBayarInput").value = limit
    //         x = limit
    //     }
    //     document.getElementById("moneyFormat").innerHTML = "Rp. "+formatRupiah(x)
    // })
</script>
@endsection
