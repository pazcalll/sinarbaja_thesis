@extends('template.pages.datatable', [
'page' => 'Approval Return',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Return', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Approval Return', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead id="thead">
        <tr>
            <th></th>
            <th>No.</th>
            <th>Pembeli</th>
            <th class="hidden-sm-down w-200">Tgl Return</th>
            <th>Nama Produk</th>
            <th>QTY</th>
            <th class="hidden-sm-down w-200">Alasan</th>
        </tr>
    </thead>
</table>
@endsection

@section('script')
<script>
    $(document).ready(function() {
        preparingOrderTable()
        $('.btn-info').removeClass( 'disabled' );
    })
    // function sentConfirmation($this, warning, status) {
    //     let id = $this->data('id');

    // }
    function preparingOrderTable() {
        $(document).ready(function(){
            $headTable = $('#thead')
            $table = $('#exampleAddRow')
        });
        $.ajax({
            url: '{{ url("/data/return/approval-return") }}',
            type: 'GET',
            success: (response) => {
                console.log(response.data)
                $table.DataTable().destroy()
                $table.empty()
                $table.append($headTable)
                let { data } = response

                data.forEach((order, index) => {
                    template = `
                        <tbody class="table-section" data-plugin="tableSection">
                            <tr style="current:pointer">
                                <td class="font-weight-medium">
                                
                                </td>
                                <td class="font-weight-medium">
                                
                                </td>
                                <td class="font-weight-medium">
                                
                                </td>
                                <td class="font-weight-medium">
                                
                                </td>
                                <td>
                                
                        <tbody>`
                })
            }
        })
    }
@endsection