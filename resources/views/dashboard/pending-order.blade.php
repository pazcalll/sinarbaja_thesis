@extends('template.pages.datatable', [
'page' => 'Pesanan Tertunda',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Pesanan', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Pesanan Tertunda', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead id="thead">
        <tr>
            <th>
            </th>
            <th>
                No. Nota
            </th>
            <th>
                Pembeli
            </th>
            <th>
                Tanggal Pesan
            </th>
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
            url: '{{ url("/data/purchase-order/pending") }}',
            type: 'GET',
            success: (response) => {
                $table.empty()
                $table.append($headTable)
                let { data } = response

                data.forEach((po, index) => {
                    template = `
                        <tbody class="table-section" data-plugin="tableSection">
                            <tr style="cursor: pointer; text-align: center;">
                                <td class="text-center"><i class="table-section-arrow"></i></td>
                                <td class="font-weight-medium">
                                    ${ po.no_nota }
                                </td>
                                <td>
                                    <span class="font-weight-medium" style="color: blue">${ po.user.name }</span>
                                </td>
                                <td>
                                    <span class="text-muted">${ moment(po.created_at).format('dddd, DD MMMM YYYY') }</span>
                                </td>
                            </tr>
                        </tbody>
                        <tbody>`

                    // retrive detail
                    po.orders.forEach((order, _index) => {
                        if (order.status === 'PENDING') {
                            template += `
                                <tr>
                                    <td></td>
                                    <td class="font-weight-medium text-success">
                                        ${ order.product.nama }
                                    </td>
                                    <td colspan="2">${ order.qty }</td>
                                </tr>`
                        }
                    }) // foreach

                    template += '</tbody>'
                    $table.append(template)
                }) // foreach
            } // on success
        }) // ajax
    })
</script>
@endsection
