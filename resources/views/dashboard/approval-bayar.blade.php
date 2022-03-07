@extends('template.pages.datatable', [
'page' => 'Approval Bayar',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => '', 'active' => ''],
['nama' => 'Tagihan', 'link' => '', 'active' => ''],
['nama' => 'Approval Bayar', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead id="thead">
        <tr text-align="center">
            <th class="w-50">
            </th>
            <th>No.</th>
            <th>
                No. Tagihan
            </th>
            <th >
                Nama Pembeli
            </th>
            <th>
                Tanggal Pesan
            </th>
            <th>
                Jumlah Nominal
            </th>
            <th>
                Aksi
            </th>
        </tr>
    </thead>
</table>
@endsection

@section('modal')
{{-- MODAL VALIDASI TAGIHAN --}}
<div class="modal fade modal-fade-in-scale-up" id="modal-validasi-tagihan" aria-hidden="true"
    aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple">
        <div class="modal-content">
            <div class="modal-header">
                {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button> --}}
                <h2 class="modal-title" style="width: 100%; text-align: center; align-content: center; align-self: center; align-items: center">Validasi Transfer</h2>
            </div>
            <div class="modal-body">
                <p>Apakah anda yakin menyetujui pembayaran tagihan ini?</p>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Batal</button>
                {{-- <form action="#" id="transferValidationForm" method="post">
                    @csrf
                    <div class="inp-wrapper"></div>
                </form> --}}
                <button type="button" onclick="formValidate(false)" class="btn btn-danger btn_invalid" data-dismiss="modal">Tolak</button>
                <button type="button" onclick="formValidate(true)" class="btn btn-primary btn_simpan" data-dismiss="modal">Setuju</button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL TAGIHAN --}}
<div class="modal fade example-modal-lg modal-3d-sign" id="detailTagihan" aria-hidden="true" aria-labelledby="detailTagihan" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" style="color: blue">Riwayat Tagihan</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Daftar Tagihan</h4>
                    <div class="example">
                        <div class="table-responsive">
                            <table class="table" id="detailTagihan">
                                <thead id="thead_detail">
                                    <tr>
                                        <th>No.</th>
                                        <th>No. Nota</th>
                                        <th>Pembeli</th>
                                        <th>Barang</th>
                                        <th>Tanggal Jatuh Tempo</th>
                                        <th>Total Pesanan</th>
                                        <th>Qty</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-warning" id="btn-cetak">Cetak</button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        let transferValidationStatus = null;
        let transferValidationId = 0;
        $(document).ready(function() {
            tagihanTable()
            $('.btn-info').removeClass( 'disabled' );
        })
        $('#transferValidationForm').on('submit', function() {
            console.log(transferValidationStatus);
        })

        function tagihanTable() {
            $(document).ready(function() {
                $headTable = $('#thead')
                $table = $('#exampleAddRow')

                var formatter = new Intl.NumberFormat('en-ID', {
                    style: 'currency',
                    currency: 'IDR',
                });
                console.log('formatter ',formatter)

                $.ajax({
                    url: '{{ url("/data/purchase-order/approval-bayar") }}',
                    type: 'GET',
                    success: (response) => {
                        console.log(response)
                        $table.DataTable().destroy()
                        $table.empty()
                        $table.append($headTable)
                        let approveTemplate = `<tbody class="table-section" data-plugin="tableSection">`
                        let tbIndex = 0

                        if (response.length > 0) {
                            // template = `
                            //     <tbody class="table-section" data-plugin="tableSection">
                            //         <tr style="cursor: pointer">
                            //             <td class="text-center"><i class="table-section-arrow"></i></td>
                            //             <td class="font-weight-medium">
                            //                 ${ po.no_nota }
                            //             </td>
                            //             <td>
                            //                 <span class="font-weight-medium">${ po.user.group_id }</span>
                            //             </td>
                            //             <td>
                            //                 <span class="font-weight-medium">${ po.user.name }</span>
                            //             </td>
                            //             <td class="hidden-sm-down">
                            //                 <span class="text-muted">${ moment(po.created_at).format('dddd, DD MMMM YYYY') }</span>
                            //             </td>
                            //             <td>`
                            // if (po.orders.length > 0) {
                            //     console.log(po.orders)
                            //     template += `<a type="button" data-id="${po.id}" class="btn btn-xs btn-success text-white add_tagihan"><i class="icon md-money-box" aria-hidden="true"></i>Buat Tagihan</a>`
                            // } else {
                            //     template += `-`
                            // }
                            // template += `</td>                                    
                            //         </tr>
                            //     </tbody>
                            //     <tbody>
                            //         <tr>
                            //             <td><input class="form-check-input check-master${po.id}" style="margin-left: 1.571rem;" type="checkbox"></td>
                            //             <td class="font-weight-bold">NAMA PRODUK</td>
                            //             <td class="font-weight-bold">QTY</td>
                            //             <td class="font-weight-bold">TOTAL HARGA</td>
                            //             <td class="font-weight-bold">QTY DISETUJUI</td>
                            //             <td class="font-weight-bold"></td>
                            //         </tr>`

                            // // retrive detail
                            // let newOrders = []  // save order from po.orders per loop
                            // let toSubmit = []   // saves order item for each checked checkbox 
                            // let order_id = []   // id of the checked order checkbox
                            // let toPending = []  // saves the unchecked item in an order with pending
                            // let pending_id = [] // id of the pending orders
                            // po.orders.forEach((order, _index) => {
                            //     console.log('order.id', po.orders.length)
                            //     newOrders.push(order)
                            //     template += `
                            //         <tr>
                            //             <td><input class="form-check-input check${po.id}" id="check${order.id}" style="margin-left: 1.571rem;" type="checkbox"></td>
                            //             <td class="font-weight-medium text-success">
                            //                 ${ order.product.nama }
                            //             </td>
                            //             <td id="qty${order.id}">${ order.qty }</td>
                            //             <td>${ formatter.format(order.qty * order.product.harga) }</td>
                            //             <td>
                            //                 <div class="row">
                            //                     <input class="form-check" style="margin-left: 1.571rem; width: 60px" type="number" value="${order.qty}" min="0" max="${order.qty}" id="inp${order.id}">
                            //                 <!--    
                            //                     <button class="btn btn-sm btn-icon btn-pure btn-default on-default approveConfirmation" data-toggle="tooltip" data-id="${ order.id }" data-original-title="Approve Sebagian"><i class="icon md-check" style="color: orange" aria-hidden="true"></i></button>
                            //                     <button class="btn btn-sm btn-icon btn-pure btn-default on-default pendingConfirmation" data-toggle="tooltip" data-id="${ order.id }" data-original-title="Pending Semua"><i class="icon md-minus" style="color: red" aria-hidden="true"></i></button> 
                            //                 -->
                            //                 </div>
                            //             </td>
                            //             </tr>
                            //             `
                            //     if(po.orders.length == _index+1){
                            //         template+=`
                            //         <tr>
                            //             <td colspan=6><a id="btn${po.id}" class="btn btn-info" style="color: white; width: 70%; margin-inline: 15%;">Setujui Checkbox dan Buat Tagihan</a></td>
                            //         </tr>
                            //         `
                            //     }
                            //     // $(`#check${order.id}`).change(function(){
                            //     //     if(this.checked){
                            //     //         $(`.check${po.id}`).prop("checked", true) 
                            //     //     }
                            //     // })
                            // }) // foreach

                            // template += `</tbody>`
                            // $table.append(template)
                            response.forEach((payment, _index) => {
                                if (payment.valid == 9) {
                                    approveTemplate += `
                                    <tr style="cursor: pointer">
                                        <td class="text-center"><i class="table-section-arrow"></i></td>
                                        <td>${tbIndex +1}</td>
                                        <td>${payment.tagihan.no_tagihan}</td>
                                        <td>${payment.po.user.name}</td>
                                        <td>${payment.tagihan.created_at}</td>
                                        <td>${payment.tagihan.nominal_total}</td>
                                        <td>
                                            <a href="{{asset('storage/app/${payment.bukti_tf}')}}" target="_blank" type="button" name="" id="" class="btn btn-xs btn-primary"><i class="icon md-money-box" aria-hidden="true"></i>Lihat Bukti Transfer</a>
                                            <a href="#" type="button" data-toggle="modal" onClick="swalModal(${payment.id})" name="" id="" class="btn btn-xs btn-warning"><i class="icon md-money-box" aria-hidden="true"></i>Validasi Transfer</a>
                                        </td>
                                    </tr>
                                    `
                                    // approveTemplate += `
                                    // <tbody>
                                    //     <tr>
                                    //         <td></td>
                                    //     </tr>
                                    // </tbody>
                                    // `

                                    approveTemplate += `
                                    <div class="modal fade modal-fade-in-scale-up" id="modal-validasi-tagihan${payment.id}" aria-hidden="true"
                                        aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
                                        <div class="modal-dialog modal-simple">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    {{-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                        <span aria-hidden="true">×</span>
                                                    </button> --}}
                                                    <h2 class="modal-title" style="width: 100%; text-align: center; align-content: center; align-self: center; align-items: center">Validasi Transfer</h2>
                                                </div>
                                                <div class="modal-body">
                                                    <p>Apakah anda yakin menyetujui pembayaran ini?</p>
                                                </div>

                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Batal</button>
                                                    <button type="button" class="btn btn-danger btn_invalid" data-dismiss="modal">Tidak</button>
                                                    <button type="button" class="btn btn-primary btn_simpan" data-dismiss="modal">Iya</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    `
                                    approveTemplate += `</tbody>`
                                    approveTemplate += `
                                        <tbody>
                                            <tr>
                                                <td></td>
                                                <td class="font-weight-bold">No.</td>
                                                <td class="font-weight-bold">Nama Produk</td>
                                                <td class="font-weight-bold">Qty</td>
                                                <td class="font-weight-bold" colspan=3>Total Harga Barang</td>
                                            </tr>
                                            `
                                    
                                    payment.tagihan.orders.forEach((order, _index)=>{
                                        approveTemplate += `
                                            <tr>
                                                <td></td>
                                                <td>${_index + 1}</td>
                                                <td>${order.nama_barang}</td>
                                                <td>${order.qty}</td>
                                                <td colspan=3>${order.qty * order.harga_order}</td>
                                            </tr>
                                        `
                                    })
                                    approveTemplate += `
                                        </tbody>
                                    `
                                    approveTemplate += `<tbody class="table-section" data-plugin="tableSection">`
                                    tbIndex =+ 1
                                }
                            });
                        }
                        $table.append(approveTemplate)
                        $('#exampleAddRow').DataTable()
                    } // on success
                })
            })
        }
        function swalModal(payment_id){
            $(`#modal-validasi-tagihan`).modal('show')
            // $('.inp-wrapper').empty()
            // $('.inp-wrapper').append(
            //     `
            //         <input type="hidden" name="decline" id="decline${payment_id}" value="">
            //         <input type="hidden" name="accept" id="accept${payment_id}" value="">
            //     `
            // )
            transferValidationId = payment_id;
            // $('#modal-validasi-tagihan .btn_invalid').data('id') = 'dead'
            // $('#modal-validasi-tagihan .btn_submit')
            // swal({
            //     title: 'Validasi Bukti Transfer',
            //     text: "Apakah anda menyetujui bukti transfer yang diberikan?",
            //     showCancelButton: true,
            //     confirmButtonClass: "btn-warning",
            //     confirmButtonText: 'Ya',
            //     closeOnConfirm: false,
            //     cancelButtonText: 'Batal'
            // }, function () {
            //     $('.sweet-alert .cancel').click(()=>{
            //         console.log('gak setuju')
            //     })
            // });
        }
        function formValidate(state){
            transferValidationStatus = state
            $.ajax({
                url: '{{ route("validateTransfer") }}',
                type: 'POST',
                data: {transferValidationId, transferValidationStatus},
                success: (response) => {
                    tagihanTable()
                    if (transferValidationStatus == true) toastr["success"]("Validasi sukses, data dianggap valid")
                    else toastr["success"]("Validasi sukses, data dianggap <span class='badge badge-danger'>tidak valid</span>")
                    console.log(response)
                },
                error: (err) => {
                    console.log(err)
                }
            })
        }
    </script>
@endsection
