@extends('template.pages.datatable', [
'page' => 'Histori Return',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => '', 'active' => ''],
['nama' => 'Return', 'link' => '', 'active' => ''],
['nama' => 'Histori Return', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead id="thead">
        <tr>
            <th>No.</th>
            <th>Pembeli</th>
            <th>Tgl Return</th>
            <th>Nama Produk</th>
            <th>Alasan</th>
            <th>QTY</th>
        </tr>
    </thead>
</table>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $headTable = $('#thead')
            $table = $('#exampleAddRow')
            $.ajax({
                url: '{{ url('/data/order/histori-return') }}',
                type: 'GET',
                success: (response) => {
                    $table.DataTable().destroy()
                    $table.empty()
                    $table.append($headTable)
                    let { 
                        data
                    } = response
                    // console.log(data);
                    data.forEach((order, index) => {
                        // console.log(order.id)
                        var d = new window.Date(order.created_at);
                        var day = d.getDate();
                        var month = d.getMonth();
                        var year = d.getFullYear();
                        var dd = day + '-' + month + '-' + year;

                        var template = `
                            <tbody class="table-section" data-plugin="tableSection">
                                <tr>
                                    <td class="font-weight-medium">
                                        ${ index+1 }
                                    </td>
                                    <td class="font-weight-medium">
                                        ${ order.po.user.name }
                                    </td>
                                    <td class="font-weight-medium">
                                        ${ dd }
                                    </td>
                                    <td class="font-weight-medium">
                                        ${ order.product.nama }
                                    </td>
                                    <td class="font-weight-medium">
                                        ${ index+1 }
                                    </td>
                                    <td class="font-weight-medium">
                                        ${ order.qty }
                                    </td>
                                </tr>
                            </tbody>`
                        $table.append(template)
                    })
                    $table.DataTable()
                }
            })
        })
    </script>
@endsection