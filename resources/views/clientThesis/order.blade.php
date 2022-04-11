@php
$id = Auth::id();
$showNavigation = false;
// $bodyType = 'site-menubar-unfold site-menubar-show site-navbar-collapse-show';
@endphp

@extends('app')

@section('css')
<style>
    td.details-control {
        /* background: url('../resources/details_open.png') no-repeat center center; */
        cursor: pointer;
    }

    tr.details td.details-control {
        /* background: url('../resources/details_close.png') no-repeat center center; */
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
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" onclick="btnRefresh()" href="#pesanan_proses" aria-controls="pesanan_proses" role="tab">Pesanan Proses</a></li>
                    <li class="nav-item" role="presentation"><a class="nav-link" data-toggle="tab" onclick="btnRefresh()" href="#pesanan_selesai" aria-controls="pesanan_selesai" role="tab">Pesanan Selesai</a></li>
                </ul>

                <div class="tab-content">

                    @include('clientThesis.table_contents')

                </div>
            </div>
            <!-- End Panel -->
        </div>
    </div>
</div>
@endsection

@section('modal')

@endsection

@section('js')
<script>
    // $.ajax({
    //     url:'{{route("pesananBelumDisetujui")}}',
    //     type:'GET',
    //     success:(res)=>{
    //         console.log(res)
    //     }
    // })
    function format ( d ) {
        console.log(JSON.parse(d.barang.replace(/&quot;/g, '"')))
        let newTbl = JSON.parse(d.barang.replace(/&quot;/g, '"'))
        let openingTbl = `<table style="width:100%">
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
                    <td>${item.harga}</td>
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
        columns: [
            {
                class:"details-control",
                orderable:false,
                data: null,
                defaultContent: "V"
            },
            {
                data: '',
                render: (data, type, row, meta) => {
                    return meta.row + meta.settings._iDisplayStart + 1
                }
            },
            {data: 'no_nota'},
            {data: 'created_at'},
            {data: 'total_harga'},
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
 
            // Remove from the 'open' array
            detailRows.splice( idx, 1 );
        }
        else {
            tr.addClass( 'details' );
            row.child( format( row.data() ) ).show();
 
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
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection
