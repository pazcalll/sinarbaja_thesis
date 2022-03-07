@extends('template.pages.datatable', [
'page' => 'Pesanan Masuk',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Pesanan', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Pesanan Masuk', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead id="thead">
        <tr>
            <th class="w-50">
            </th>
            <th>
                No. Nota
            </th>
            <th width="10%">
                Tipe User
            </th>
            <th width="10%">
                Jenis Pembayaran
            </th>
            <th >
                Pembeli
            </th>
            <th class="hidden-sm-down w-200">
                Tanggal Pesan
            </th>
            {{-- <th class="hidden-sm-down w-200">
                Aksi
            </th> --}}
        </tr>
    </thead>
</table>
<form method="POST" name="formPotongan" id="formPotongan">
  @csrf
<div class="modal fade example-modal-lg modal-3d-sign" id="modalsubmit" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-simple modal-center modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">×</span>
          </button>
          <h4 class="modal-title" style="color: blue;">Penambahan Potongan</h4>
      </div>
      <div class="modal-body">
        <input hidden type="text" name="jenis_pembayaran" id="txt_jenis_pembayaran" value="">
        <table class="table table-bordered table-hover table-striped" style="width:100%" id="tabel_potongan">
          <thead>
              <tr>
                  <th class="w-30">
                      No.
                  </th>
                  <th>
                      Nama Barang
                  </th>
                  <th>
                      Qty
                  </th>
                  <th width="15%">
                      Gudang
                  </th>
                  <th>
                      Potongan Harga (Rp)
                  </th>
                  <th>
                      Harga
                  </th>
                  <th></th>
              </tr>
          </thead>
          <tfoot align="right">
        		<tr>
              <th colspan="4">Total</th>
              <th>
                <div class="form-group">
                  <input type="number" name="potongan_nota" min="0" class="form-control" placeholder="Potongan Nota" oninput="PotonganNota(this.value)">
                </div>
              </th>
              <th>
                <span id="total_harga"></span>
                <input hidden type="number"  name="total_harga_potongan"  id="total_harga_input">
              </th>
            </tr>
        	</tfoot>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" form="formPotongan" class="btn btn-primary add_tagihan">Submit</button>
      </div>
    </div>
  </div>
</div>
</form>
{{-- <div class="modal fade example-modal-lg modal-3d-sign" id="modalPotongan" aria-hidden="true" aria-labelledby="modalPotongan" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" style="color: blue;">Penambahan Potongan</h4>
            </div>
            <form method="POST" name="formPotongan" id="formPotongan">
                @csrf
                <div class="modal-body">
                    <div class="example-wrap">
                        <br>
                        <h4 class="example-title text-center">Data Barang</h4>
                        <div class="example">
                            <div class="table-responsive" id="kontenModalPotongan">
                                <table class="table table-bordered table-hover table-striped" id="tablePotongan">
                                    <thead id="thead">
                                        <tr>
                                            <th class="w-30">
                                                No.
                                            </th>
                                            <th>
                                                Nama Barang
                                            </th>
                                            <th width="5%">
                                                Qty
                                            </th>
                                            <th width="15%">
                                                Gudang
                                            </th>
                                            <th>
                                                Potongan Harga (Rp)
                                            </th>
                                            <th>
                                                Harga Asli
                                            </th>
                                            <th>
                                                Harga Akhir
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Back</button>
                    <button type="submit" form="formPotongan" class="btn btn-primary submitPotongan">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div> --}}
@endsection

@section('script')
    <script>
          var id_barang_arr = [];
          var qty_arr = [];
          var harga_all = [];
          var nota;
          var potongan_nota_val = 0;
          var checkbox = [];
          var id_user_send;
        $(document).ready(function() {
            preparingOrderTable()
            $('.btn-info').removeClass( 'disabled' );
        })

        function updateStatusOrder(data) {
            $.ajax({
                url: `{{ url('/dashboard/order/update') }}`,
                type: 'POST',
                data: {
                    data
                },
                success: (response) => {
                    swal({
                        title: 'Tagihan Selesai Dibuat',
                        text: "Bisa dilihat di menu Perintah Kirim",
                        type: "success",
                        confirmButtonClass: "btn-success",
                        confirmButtonText: 'OK',
                        closeOnConfirm: true
                    })
                    // window.location.reload()
                    preparingOrderTable()
                }
            })
        }

        function storeToPending(data) {
            $.ajax({
                url: `{{ url('/dashboard/order/store-to-pending') }}`,
                type: 'POST',
                data: {
                    data
                },
                success: (response) => {
                    toastr["warning"]('Sebagian Produk Dari Pesanan Masuk Ke Pending')
                }
            })
        }
        $(document).on('click','#btn-submit',function(e){
          harga_all = [];
          let jenis_pembayaran_send = $(this).attr('data-jenis');
          $('#txt_jenis_pembayaran').val(jenis_pembayaran_send)
          var formatter = new Intl.NumberFormat('en-ID', {
              style: 'currency',
              currency: 'IDR',
          });
          let value;
          e.preventDefault();
          let poid  = $(this).data('id');
          $('#tabel_potongan').DataTable().destroy();
          $('#tabel_potongan').DataTable( {
            ajax: {
                type: 'POST',
                url: "{{route('loadData_po')}}",
                data: {
                   _token: "{{ csrf_token() }}",
                   id: poid,
                   id_barang : checkbox
                },
                complete: function(){
                  $('.js-example-responsive').select2({
                    placeholder: 'Select an option'
                  });
                  $.ajax({
                        type: 'POST',
                        url: "{{route('loadData_po')}}",
                        data: {
                           _token: "{{ csrf_token() }}",
                           id: poid,
                           id_barang : checkbox,
                           qty : qty_arr
                        },
                      success: function(data) {
                        $.each(data.arr_gudang, function(index,value){
                          if (value.status == 'Stock Tidak Memenuhi') {
                              $('#ref_gudang'+value.id).attr({ multiple:"multiple"});
                              $('#ref_gudang'+value.id).append('<option value="'+value.id+','+value.nama+', '+value.stock+'">'+value.nama+' | '+value.stock+'</option>');
                          }
                          else {
                            $('#ref_gudang'+value.id).append('<option value="'+value.id+','+value.nama+','+value.stock+'">'+value.nama+' | '+value.stock+'</option>');
                          }
                        })
                      }
                   });
                }
            },
            columns: [
            {  data: null,
            render: function (data, type, row, meta) {
                id_barang_arr[data['num']-1] = data['id']
                qty_arr[data['num']-1] = data['qty']
                nota = data['nota']
                id_barang_arr[data['num']] = null
                return meta.row + meta.settings._iDisplayStart + 1;
            }},
            { data: "nama_barang" },
            {
              data: null,
                render: function(data,type,full,meta, harga_total){
                  return '<span>'+data['qty']+'</span>'+
                         '<input hidden type="number" min="0" class="form-control" id="id_qty'+data['num']+'" value="'+data['qty']+'">';
                }
            },
            {
              data: null,
              render: function(data, type, full, meta){
               return '<div class="form-group">'+
                      '<select class="js-example-responsive" multiple="multiple" style="width: 100%" name="gudang['+data['id']+'][]" id="ref_gudang'+data['id']+'" required></select>'
                        // '<select class="js-example-responsive select2-select" style="width: 75%" name="gudang['+data['id']+'][]" id="ref_gudang'+data['id']+'">'+
                        // '<option selected hidden value="">-- pilih gudang --</option>'
                        // '</select>'+
                      '</div>';
             }
           },
           {data: null,
             render: function(data, type, full, meta){
              harga_all[data['num']-1] = data['harga']*data['qty']
              harga_all[data['num']] = null
              return    '<div class="form-group">'+
                        '<input type="number" min="0" oninput="kurang_harga('+data['num']+')" class="form-control" id="potongan_harga'+data['num']+'" name="potongan_harga[]" placeholder="Potongan Harga">'+
                        '<input hidden type="number" min="0" class="form-control harga_hidden" name="harga_awal[]" id="harga_hidden'+data['num']+'" value="'+data['harga']+'" placeholder="Potongan Harga">'+
                        '</div>';
            }
           },
           {data: null,
             render: function(data,type,full,meta, harga_total){
               let harga_qty = formatter.format(data['harga']*data['qty'])
               return '<span id="harga_result'+data['num']+'">'+harga_qty+'</span>'+
                      '<input hidden type="number" min="0" class="form-control" id="res_harga'+data['num']+'" value="'+data['harga']*data['qty']+'">';
             }
           },
           {data: null,
              render: function (data,type,full,meta,) {
                            return data['harga'] * data['qty'];
                        }
          },
         ],
         columnDefs: [
               {
                   "targets": [6],
                   "visible": false,
                   "searchable": false
               },
            ],
         footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };
             var total_harga = api
                      .column(6)
                      .data()
                      .reduce( function (a, b) {
                          return intVal(a) + intVal(b);
                      }, 0 );
                      document.getElementById("total_harga").textContent=formatter.format(total_harga);
                      $('#total_harga_input').val(total_harga)
                      harga_total(harga_all)
              },
          } );
        })
        function harga_total(harga_all){
          var formatter = new Intl.NumberFormat('en-ID', {
              style: 'currency',
              currency: 'IDR',
          });
          let count_harga = 0
          for (let i = 0; i < harga_all.length; i++) {
            count_harga += harga_all[i];
          }
          count_harga = count_harga-potongan_nota_val
          document.getElementById("total_harga").textContent=formatter.format(count_harga);
          $('#total_harga_input').val(count_harga)
        }
        function kurang_harga(id){
          var formatter = new Intl.NumberFormat('en-ID', {
              style: 'currency',
              currency: 'IDR',
          });
          let potongan_harga = parseInt(document.getElementById('potongan_harga'+id).value)
          let harga_hidden = parseInt(document.getElementById('harga_hidden'+id).value)
          let id_qty = parseInt(document.getElementById('id_qty'+id).value)

          if (isNaN(potongan_harga)) {
            potongan_harga = 0;
          }
          let harga_barang = (harga_hidden * id_qty) - potongan_harga
          document.getElementById("harga_result"+id).textContent=formatter.format(harga_barang);
          $('#res_harga'+id).val(harga_barang)
          harga_all[id-1] = harga_barang
          harga_total(harga_all)
        }
        function PotonganNota(value){
          console.log("pp",value)
          var formatter = new Intl.NumberFormat('en-ID', {
              style: 'currency',
              currency: 'IDR',
          });
          if (isNaN(value)) {
            value = 0;
          }
          let count_harga = 0
          for (let i = 0; i < harga_all.length; i++) {
            count_harga += harga_all[i];
          }
          let potongan_nota_value = count_harga - value
          $('#total_harga_input').val(potongan_nota_value)
          document.getElementById("total_harga").textContent=formatter.format(potongan_nota_value);
          potongan_nota_val = value
          harga_total(harga_all)
        }
        function set_check_arr(id, id_po){
          var checkBox = document.getElementById("check"+id);
          if (checkBox.checked == true){
            let length = checkbox.length
            checkbox[length] = id
            let check = checkbox.some(el => el !== null);
          } else {
            let index = checkbox.indexOf(id);
            checkbox[index] = null
            let check = checkbox.some(el => el !== null);
            if (check == false) {
              checkbox = []
              $('#check-master'+id_po).prop("checked", false)
            }
          }
        }




        $(document).on('click','.add_tagihan', function (e) {
            e.preventDefault();
            // console.log($("#formPotongan").serialize())
            $.ajax({
                url: `{{ route('addTagihan') }}`,
                type: 'POST',
                data: $("#formPotongan").serialize()+ '&po_id=' + id_barang_arr + '&qty=' + qty_arr
                + '&nota=' + nota + '&id_user=' + id_user_send,
                success: (response) => swal({
                    title: 'Sukses!',
                    text: "Berhasil membuat tagihan",
                    type: "success"
                }, function(){
                  $("[data-dismiss=modal]").trigger({ type: "click" });
                    $('#exampleAddRow').DataTable().clear().destroy();
                    preparingOrderTable()
                }),
                statusCode:{
                    500:function(responseObject, textStatus, jqXHR){
                      swal({
                          title: 'Gagal!',
                          text: responseObject.responseJSON.message,
                          type: "error"
                      })
                    }
                }
            })
        });
        function preparingOrderTable() {
            $(document).ready(function() {
                $headTable = $('#thead')
                $table = $('#exampleAddRow')

                var formatter = new Intl.NumberFormat('en-ID', {
                    style: 'currency',
                    currency: 'IDR',
                });
                $.ajax({
                    url: '{{ url("/data/purchase-order/new") }}',
                    type: 'GET',
                    success: (response) => {
                        $table.DataTable().destroy()
                        $table.empty()
                        $table.append($headTable)
                        let { data } = response
                        data.forEach((po, index) => {
                            console.log("pp", po)
                          let user = po.user.id
                          id_user_send = po.user.id
                            template = `
                                <tbody class="table-section" data-plugin="tableSection">
                                    <tr style="cursor: pointer">
                                        <td class="text-center"><i class="table-section-arrow"></i></td>
                                        <td class="font-weight-medium">
                                            ${ po.no_nota }
                                        </td>
                                        <td>
                                            <span class="font-weight-medium">${ po.user.group_user.group_name }</span>
                                        </td>
                                        <td>
                                            <span class="font-weight-medium">${ po.jenis_pembayaran }</span>
                                        </td>
                                        <td>
                                            <span class="font-weight-medium">${ po.user.name }</span>
                                        </td>
                                        <td class="hidden-sm-down">
                                            <span class="text-muted">${ moment(po.created_at).format('dddd, DD MMMM YYYY') }</span>
                                        </td>
                                        `
                            // if (po.orders.length > 0) {
                            //     console.log(po.orders)
                            //     template += `<td><a type="button" data-id="${po.id}" class="btn btn-xs btn-success text-white add_tagihan"><i class="icon md-money-box" aria-hidden="true"></i>Buat Tagihan</a> </td> `
                            // } else {
                            //     template += `<td>-</td> `
                            // }
                            template += `
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td><input class="form-check-input check-master${po.id}" id="check-master${po.id}" style="margin-left: 1.571rem;" type="checkbox"></td>
                                        <td class="font-weight-bold" colspan="2">NAMA PRODUK</td>
                                        <td class="font-weight-bold">QTY</td>
                                        <td class="font-weight-bold">TOTAL HARGA</td>
                                        <td class="font-weight-bold">QTY DISETUJUI</td>
                                        {{-- <td class="font-weight-bold"></td> --}}
                                    </tr>`

                            // retrive detail
                            let newOrders = []  // save order from po.orders per loop
                            let toSubmit = []   // saves order item for each checked checkbox
                            let order_id = []   // id of the checked order checkbox
                            let toPending = []  // saves the unchecked item in an order with pending
                            let pending_id = [] // id of the pending orders

                            // table view
                            let newHarga = []
                            po.orders.forEach((order, _index) => {
                                newOrders.push(order)
                                // order.product.harga_group.forEach((item, indexx)=>{
                                //     if (item.id_product == order.product.id && item.id_group) {
                                //         newHarga.push(item)
                                //     }
                                // })
                                template += `
                                    <tr>
                                        <td ><input class="form-check-input check${po.id}" id="check${order.id}" onclick="set_check_arr(${order.id},${po.id})" style="margin-left: 1.571rem;" type="checkbox"></td>
                                        <td colspan="2" class="font-weight-medium text-info">
                                            ${ order.nama_barang }
                                        </td>
                                        <td id="qty${order.id}">${ order.qty }</td>
                                        <td>${ formatter.format(order.qty * order.harga_order) }</td>
                                        <td>
                                            <div class="row">
                                                <input class="form-check" style="margin-left: 1.571rem; width: 60px" type="number" value="${order.qty}" min="0" max="${order.qty}" id="inp${order.id}" readonly="">
                                            <!--
                                                <button class="btn btn-sm btn-icon btn-pure btn-default on-default approveConfirmation" data-toggle="tooltip" data-id="${ order.id }" data-original-title="Approve Sebagian"><i class="icon md-check" style="color: orange" aria-hidden="true"></i></button>
                                                <button class="btn btn-sm btn-icon btn-pure btn-default on-default pendingConfirmation" data-toggle="tooltip" data-id="${ order.id }" data-original-title="Pending Semua"><i class="icon md-minus" style="color: red" aria-hidden="true"></i></button>
                                            -->
                                            </div>
                                        </td>
                                        </tr>
                                        `
                                if(po.orders.length == _index+1){
                                    template+=`
                                    <tr>
                                        <td colspan=6>
                                            <button id="btn-submit" type="button" data-id="${po.id}" data-jenis="${po.jenis_pembayaran}" class="btn btn-info" data-toggle="modal" style="color: white; width: 70%; margin-inline: 15%;" data-target="#modalsubmit">
                                              Setujui Checkbox dan Buat Tagihan
                                            </button>
                                        </td>
                                    </tr>
                                    `
                                    $(`btn${po.id}`).unbind()
                                }
                                newHarga = []
                            }) // foreach

                            template += `</tbody>`
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
                            // CHECKBOXES
                            $(`.check-master${po.id}`).change(function(){
                                if(this.checked){
                                    $(`.check${po.id}`).prop("checked", true)
                                    toPending=[]
                                    newOrders.forEach((order) => {
                                        if ($(`#check${order.id}`).prop("checked")===true) {
                                            set_check_arr(order.id, order.po_id)
                                            if (order_id.filter(function(e) {return e !== order.id})) {
                                                order_id = order_id.filter(function(e) {return e !== order.id})
                                                toSubmit = toSubmit.filter(function(e){return e.id !== order.id})
                                                order_id.push(order.id)
                                                toSubmit.push(order)
                                            }else{
                                                order_id.push(order.id)
                                                toSubmit.push(order)
                                            }
                                        }
                                        else if($(`#check${order.id}`).prop("checked")===false){
                                            order_id.remove(order.id)
                                            toSubmit = toSubmit.filter(function(e){return e.id !== order.id})
                                        }
                                    })
                                }
                            })
                            newOrders.forEach((order) => {
                                pending_id.push(order.id)
                                toPending.push(order)
                                $(`#check${order.id}`).change(function(){
                                    if(this.checked){
                                        order_id.push(order.id)
                                        toSubmit.push(order)
                                        toPending = toPending.filter(function(e){return e.id !== order.id})
                                        pending_id = pending_id.filter(function(e) {
                                            return e !== order.id
                                        })
                                    }else if(!this.checked){
                                        order_id = order_id.filter(function(e) {
                                            return e !== order.id
                                        })
                                        toSubmit = toSubmit.filter(function(e){return e.id !== order.id})
                                        pending_id.push(order.id)
                                        toPending.push(order)
                                    }
                                })
                            })
                            let beforeSubmit = []
                            let sameQty = true
                            var gudang = []

                            $(`#btn${po.id}`).on('click', function(){

                                $('#nomor_nota').val(po.no_nota)
                                tbodyTablePotongan = $('#tablePotongan tbody')
                                tbodyTablePotongan.empty()
                                let rows = ''
                                let firstTotal = 0
                                let items = []
                                toSubmit.forEach((order, _index) => {
                                    gudang = [];
                                    items[_index] = 0
                                    firstTotal += (order.qty * order.harga_order)
                                    $.ajax({
                                            url: '{{ url("/data/purchase-order/select_gudang") }}',
                                            type: 'GET',
                                            async: false,
                                            data: {
                                                id_barang : order.product_id,
                                                qty_barang : $('#inp'+order.id).val()
                                            },
                                            success: (response) => {
                                                response.forEach((g) => {
                                                    console.log(g)
                                                    let tag = `<option value="${g.id}">${g.nama}</option>`
                                                    gudang.push(tag);
                                                })
                                            }
                                    })
                                    rows = `
                                        <tr>
                                            <td>
                                                ${_index + 1}
                                            </td>
                                            <td class="font-weight-medium text-info">
                                                ${ order.nama_barang }
                                            </td>
                                            <td>
                                                ${ $('#inp'+order.id).val() }
                                                <input type="hidden" class="form-control" id="jumlah_barang_${order.id}" name="jumlah_barang_${order.id}" value=" ${ $('#inp'+order.id).val() }">
                                            </td>
                                            <td>
                                                <select style="cursor: pointer" class="form-control" name="gudang_barang_${order.id}" id="gudang_barang_${order.id}">
                                                    ${gudang.join('')}
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control" id="nilai_potongan_${order.id}" name="nilai_potongan_${order.id}" value="0">
                                            </td>
                                            <td>
                                                ${ formatter.format(order.qty * order.harga_order) }
                                            </td>
                                            <td id="hasil_potongan_${order.id}">
                                                ${ formatter.format(order.qty * order.harga_order) }
                                            </td>
                                        </tr>
                                    `
                                    tbodyTablePotongan.append(rows)
                                // foreach
                                tbodyTablePotongan.append(`
                                    <tr>
                                        <td rowspan="3" colspan="5">

                                        </td>
                                        <td>
                                            <b>Sub Total</b>
                                        </td>
                                        <td id="subTotal">
                                            ${ formatter.format(firstTotal) }
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Potongan Nota</b>
                                        </td>
                                        <td id="potonganNota">
                                            <input class="form-control" type="number" name="potonganNota" id="potonganNotaInp" value="0">
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <b>Harga Total</b>
                                        </td>
                                        <td id="hargaTotal">
                                            ${ formatter.format(order.qty * order.harga_order) }
                                        </td>
                                    </tr>
                                `)
                                })
                                let subTotalTmp = 0
                                let subTotal = 0
                                toSubmit.forEach((order, _index) => {
                                    items[_index] = order.qty * order.harga_order
                                    $(`#nilai_potongan_${order.id}`).on('input', function(){
                                        items[_index] = order.qty * order.harga_order - $(this).val()
                                        $(`#hasil_potongan_${order.id}`).html(formatter.format(order.qty * order.harga_order - $(this).val()))

                                        items.forEach((price, _index)=>{
                                            subTotalTmp += price
                                        })
                                        $('#subTotal').html(formatter.format(subTotalTmp))
                                        $('#hargaTotal').html(formatter.format(subTotalTmp - $('#potonganNotaInp').val()))
                                        subTotal = subTotalTmp
                                        subTotalTmp=0
                                    })
                                    $('#potonganNotaInp').on('input',function() {
                                        $('#potonganNotaInp').val();
                                    })
                                })
                                $('#modalPotongan').modal('show');
                                $('#formPotongan').unbind()
                                $('#formPotongan').on('submit', function(e){

                                    e.preventDefault()
                                    toSubmit.forEach((order)=>{
                                        $('.btn-info').addClass( 'disabled' );
                                        if($(`#inp${order.id}`).val() !== order.qty){
                                            order.qty = $(`#inp${order.id}`).val()
                                            beforeSubmit.push(order);
                                        }else{
                                            beforeSubmit.push(order);
                                        }
                                    })
                                    toSubmit = beforeSubmit
                                    for (let i = 0; i < toSubmit.length; i++) {
                                        // to check whether the requested qty same as the max qty or not and then change the value of qty similarity dynamically
                                        if (po.orders.length === toSubmit.length && parseInt(toSubmit[i].qty) !== parseInt($(`#qty${toSubmit[i].id}`).html())) {
                                            sameQty = false
                                            break
                                        }else sameQty = true
                                    }
                                    if (toSubmit.length > 0) {
                                        if (po.orders.length === toSubmit.length && sameQty === true) {
                                            // to submit the order if all checkboxes are checked and the req qty same as the max qty
                                            $.ajax({
                                                url: `{{ route('addTagihan') }}`,
                                                type: 'POST',
                                                data: $(this).serialize()+ '&po_id=' + po.id + '&nota=' + po.no_nota + '&id_user=' + user,
                                                success: (response) => swal({
                                                    title: 'Sukses!',
                                                    text: "Berhasil membuat tagihan",
                                                    type: "success"
                                                }, function(){
                                                    $('#exampleAddRow').DataTable().clear().destroy();
                                                    preparingOrderTable()
                                                }),
                                                error: (response) => {
                                                }
                                            })
                                        }else if(po.orders.length !== toSubmit.length || sameQty !== true){

                                            let products_id = []
                                            // console.log(toSubmit)
                                            updateStatusOrder(toSubmit);
                                            storeToPending(toPending);
                                        }
                                    }else toastr["error"]('Belum ada produk yang dicentang')

                                    beforeSubmit = []
                                    // console.log('qty similarity ', sameQty)
                                    $('#modalPotongan').modal('hide')
                                })
                            })
                        }) // foreach
                        $('#exampleAddRow').DataTable()
                    } // on success
                }).done($('#exampleAddRow').DataTable())
            })
        }
    </script>
@endsection
