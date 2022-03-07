@extends('template.pages.datatable', [
'page' => 'Seller Bonus',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Account', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Seller Bonus', 'link' => '', 'active' => 'active']
]
])


{{-- @section('top-panel')
<div class="row">
    <div class="col-md-6">
        <div class="mb-15">
            <button id="addGroup" class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalGroup">
                <i class="icon md-plus" aria-hidden="true"></i> Add Group
            </button>
        </div>
    </div>
</div>
@endsection --}}

@section('table')
{{-- @php
    var_dump($seller);
@endphp --}}
<label class="title" style="font-size: 14pt; font-weight: bold;">{{$name}}</label>
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead>
        <tr align="center">
            <th width="1%">No.</th>
            <th>Tagihan</th>
            <th>Nominal</th>
            <th>Bonus (10%)</th>
        </tr>
    </thead>
    <tbody>
        @php
            $nominal_total = 0;
            $bonus_total = 0;
        @endphp
        {{-- {{dd($sellerBills)}} --}}
        @foreach ($sellerBills as $item)
        
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td onclick="tagihanDetail('{{$item['id']}}')" style="color: blue; cursor: pointer;">{{ $item['no_tagihan'] }}</td>
            <td>Rp {{ $item['nominal_total'] }}
            @php
                $nominal_total += intval($item['nominal_total']);
                $bonus_total += intval($item['nominal_total'])*10/100;
            @endphp
            </td>
            <td>Rp {{ intval($item['nominal_total'])*10/100 }}</td>
        </tr>
            
        @endforeach
        <tr>
            <td colspan="2" style="text-align: center"><b>TOTAL</b></td>
            <td>Rp {{$nominal_total}}</td>
            <td>Rp {{$bonus_total}}</td>
        </tr>
    </tbody>
</table>


<div class="modal fade" id="modalTagihan" aria-hidden="true" aria-labelledby="examplePositionCenter" role="dialog"
    tabindex="-1">
    <div class="modal-dialog modal-simple modal-center">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Detail <b style="color:blue;">{{ $item['no_tagihan'] }}</b></h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover table-striped" id="tbl_detail">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Barang</th>
                            <th>Jumlah</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    function tagihanDetail(noTagihan) {
        $('#modalTagihan').modal('show')
        $.ajax({
            url: '{{ url("dashboard/bonus/sales_detail") }}/'+noTagihan+'/detail',
            type: 'GET',
            success: (res) => {
                if (res.length == 0) {
                    console.log('empty data')
                    $('#tbl_detail tbody').html(`
                        <tr><td colspan="4">Barang belum disetujui admin</td></tr>
                    `)
                }else{
                    console.log(res)
                    let tblContent = ''
                    res.forEach((item, _index) => {
                        tblContent += `
                            <tr>
                                <td>
                                    ${_index + 1}
                                </td>
                                <td>
                                    ${item.nama_barang}
                                </td>
                                <td>
                                    ${item.qty}
                                </td>
                                <td>
                                    Rp ${item.qty * item.harga_order}
                                </td>
                            </tr>
                        `
                    });
                    $('#tbl_detail tbody').html(tblContent)
                }
            }
        })
    }
</script>
@endsection