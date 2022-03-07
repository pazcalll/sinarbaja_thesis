@extends('template.pages.datatable', [
'page' => 'Pesanan Selesai',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Pesanan', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Pesanan Selesai', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover" id="exampleAddRow">
    <thead id="thead">
        <tr>
            <th width="1%">
            </th>
            <th width="5%">No</th>
            <th width="20%">
                No. Nota
            </th>
            <th width="10%">
                Tipe User
            </th>
            <th width="20%">
                Pembeli
            </th>
            <th>
                Status Order
            </th>
            <th class="hidden-sm-down w-200">
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

            var formatter = new Intl.NumberFormat('en-ID', {
                style: 'currency',
                currency: 'IDR',
            });

            $.ajax({
                url: '{{ url("/data/purchase-order/order/pesanan-selesai") }}',
                type: 'GET',
                success: (response) => {
                    $table.empty()
                    $table.append($headTable)
                    let { data } = response
                    
                    data.forEach((po, index) => {
                        console.log("op",po)
                        template = `
                            <tbody class="table-section" data-plugin="tableSection">
                                <tr style="cursor: pointer" id="">
                                    <td class="text-center"><i class="table-section-arrow"></i></td>
                                    <td>${index+1}</td>
                                    <td class="font-weight-medium">
                                        ${ po.no_nota }
                                    </td>
                                    <td>
                                        <span class="font-weight-medium">${ po.user.group_user.group_name }</span>
                                    </td>
                                    <td >
                                        <span class="font-weight-medium">${ po.user.name }</span>
                                    </td>
                                    <td>
                                        `
                                        if(po.orders[0].status == 'DISETUJUI SEMUA')
                                            template += `<span class="badge badge-success">DISETUJUI SEMUA</span>`
                                        else if (po.orders[0].status == 'DISETUJUI SEBAGIAN')
                                            template += `<span class="badge badge-warning">DISETUJUI SEBAGIAN</span>`
                                        template +=`  
                                    </td>
                                    
                                    <td class="hidden-sm-down">
                                        <span class="text-muted">${ moment(po.created_at).format('dddd, DD MMMM YYYY') }</span>
                                    </td>
                                </tr>
                            </tbody>
                            <tbody>
                                <tr>
                                    <td></td>
                                    <td class="font-weight-bold" colspan="2">NAMA PRODUK</td>
                                    <td class="font-weight-bold">QTY</td>
                                    <td  class="font-weight-bold">TOTAL HARGA</td>
                                    <td class="font-weight-bold">STATUS TAGIHAN</td>
                                    <td class="font-weight-bold">STATUS PENGIRIMAN</td>
                                    {{-- <td></td> --}}
                                </tr>`

                        // retrive detail
                        po.orders.forEach((order, _index) => {
                            console.log("qq",order)
                            let color = (order.status === 'NEW' || order.status === 'APPROVE') ? 'text-success' : (order.status === 'PENDING') ? 'text-danger' : (order.status === 'SENT') ? 'text-primary' : (order.status === 'RETURN') ? 'text-warning': '';
                            template += `
                                <tr>
                                    <td></td>
                                    <td class="font-weight-medium" colspan="2" style="color: blue;">
                                        ${ order.nama_barang }
                                    </td>
                                    <td>${ order.qty }</td>
                                    <td >${ formatter.format(order.qty * order.harga_order) }</td>
                                    <td class="font-weight-medium ${ color }">
                                        `
                                        if(order.tagihan.status == 'LUNAS')
                                            template += `<span class="badge badge-success">LUNAS</span>`
                                        else if (order.tagihan.status == 'BELUM DIBAYAR')
                                            template += `<span class="badge badge-danger">BELUM DIBAYAR</span>`
                                        else if (order.tagihan.status == 'DIBAYAR SEBAGIAN')
                                            template += `<span class="badge badge-warning">DIBAYAR SEBAGIAN</span>`
                                        template +=`    
                                    </td>
                                    <td class="font-weight-medium ${ color }">
                                        `
                                        if(order.tagihan.tracking_newest[0].status == 'ARRIVED')
                                            template += `<span class="badge badge-success">ARRIVED</span>`
                                        else if (order.tagihan.tracking_newest[0].status == 'ARRIVED WITH RETURN')
                                            template += `<span class="badge badge-warning">ARRIVED WITH RETURN</span>`
                                        template +=`    
                                    </td>
                                </tr>`
                        }) // foreach

                        template += '</tbody>'
                        $table.append(template)
                        $('.approveAllConfirmation').on("click", function () {
                            setConfirmation($(this), "Produk ini akan diterima semua dan merubah status menjadi siap dikirim", 'APPROVE')
                        })

                        $('.pendingConfirmation').on("click", function () {
                            setConfirmation($(this), 'Proses order pada bulan selanjutnya.', 'PENDING')
                        })

                        $('.approveConfirmation').on('click', function() {
                            approveConfirmation($(this))
                        })
                    }) // foreach
                } // on success
            }) // ajax
        })

        function setConfirmation($this, warning, status) {
            let id = $this.data('id')

            swal({
                title: 'Peringatan!',
                text: warning,
                type: "warning",
                showCancelButton: true,
                confirmButtonClass: "btn-warning",
                confirmButtonText: 'Ya',
                closeOnConfirm: false, //closeOnCancel: false
                cancelButtonText: 'Batal'
            }, function () {
                updateStatusOrder({
                    id: id,
                    data: {
                        status: status
                    }
                })
            }) // swal end
        }

        function approveConfirmation($this) {
            const id = $this.data('id')

            $('.approveConfirmation').on("click", function () {
                swal({
                    title: 'Peringatan!',
                    text: "Berapa yang anda berikan pada order ini ?",
                    type: "input",
                    showCancelButton: true,
                    confirmButtonClass: "btn-warning",
                    confirmButtonText: 'Ya',
                    closeOnConfirm: false,
                    cancelButtonText: 'Batal'
                }, function (input) {
                    updateStatusOrder({
                        id: id,
                        data: {
                            qty: input
                        }
                    })
                });
            });
        }

        function updateStatusOrder(data) {
            $.ajax({
                url: `{{ url('/dashboard/order/update') }}/${ data.id }`,
                type: 'POST',
                data: data,
                success: (response) => swal({
                    title: 'Yeaaay!',
                    text: "Berhasil mengupdate data.",
                    type: "success"
                }, function() {
                    window.location.reload()
                })
            })
        }
    </script>
@endsection
