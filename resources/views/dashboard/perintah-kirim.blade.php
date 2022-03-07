@extends('template.pages.datatable', [
'page' => 'Perlu Dikirim',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Pesanan', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Perlu Dikirim', 'link' => '', 'active' => 'active']
]
])

@section('bottom-panel')
    <button type="button" class="btn btn-primary waves-effect waves-classic btn-kirim" >
        Kirim Pesanan
    </button>
@endsection

{{-- @section('js')
    <link rel="stylesheet" href="{{ asset('public/select/css/mobiscroll.javascript.min.css') }}">
@endsection --}}

@section('table')
  <form class="input_gudang_form">
    <table class="table table-bordered table-hover table-striped" id="exampleAddRow">
        <thead id="thead">
            <tr>
                <th class="w-50">
                </th>
                <th colspan="2">
                    No. Nota
                </th>
                <th>
                    Pembeli
                </th>
                <th>
                    Status Bayar
                </th>
                <th width="15%">
                    Tanggal Pesan
                </th>
                <th width="20%">
                    Alamat
                </th>
            </tr>
        </thead>
    </table>
  </form>
@endsection

@section('modal')
    <div class="modal fade modal-fade-in-scale-up" id="modal-select-driver" aria-hidden="true"
        aria-labelledby="exampleModalTitle" role="dialog" tabindex="-1">
        <div class="modal-dialog modal-simple">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                    <h5 class="modal-title">Pilih Driver</h4>
                </div>
                <div class="modal-body">
                    <select id="list-driver" class="form-control select2-hidden-accessible" data-plugin="select2"
                        data-select2-id="1" tabindex="-1" aria-hidden="true">
                    </select>
                    <h5 class="modal-title" style="margin-bottom: 10px">Jatuh Tempo</h4>
                    <input type="text" class="form-control datepicker" id="jatuh_tempo" placeholder="{{date("Y-m-d")}}" name="jatuh_tempo" value="{{ now() }}" />
                    <h5 class="modal-title">Memo</h5>
                    <textarea class="form-control rounded-0" style="display:none;" name="memo" id="memo" rows="4" value=""></textarea>
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="show-hide" id="show-hide">
                        <label class="form-check-label" for="memo"><b id="checkbox">Hide</b></label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary btn_simpan">Simpan</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
{{-- <script src="{{ asset('public/select/js/mobiscroll.javascript.min.js') }}"></script> --}}
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.3.0/js/bootstrap-datepicker.js"></script>
    <script>
        let dataChecked = []
        var formData = []
        var datas
        var qty_arrays;
        function qty_input(value,id,gudang,order_id){
          let index_dataChecked = null;
          for (var i=0; i<dataChecked.length; i++) {
              if ( dataChecked[i].Id == id ) {
                  index_dataChecked = i;
                  break;
              }
          }
          formData= $(".input_gudang_form").serializeControls(), null, 2;
        }
        $.fn.serializeControls = function() {
          var data = {};

          function buildInputObject(arr, val) {
            if (arr.length < 1)
              return val;
            var objkey = arr[0];
            if (objkey.slice(-1) == "]") {
              objkey = objkey.slice(0,-1);
            }
            var result = {};
            if (arr.length == 1){
              result[objkey] = val;
            } else {
              arr.shift();
              var nestedVal = buildInputObject(arr,val);
              result[objkey] = nestedVal;
            }
            return result;
          }

          $.each(this.serializeArray(), function() {
            var val = this.value;
            var c = this.name.split("[");
            var a = buildInputObject(c, val);
            $.extend(true, data, a);
          });

          return data;
        }
        function loadPage() {
            var formatter = new Intl.NumberFormat('en-ID', {
                style: 'currency',
                currency: 'IDR',
            });

            $headTable = $('#thead')
            $table = $('#exampleAddRow')
            var array_tagihans = []
            var idx_array_tagihans = 0
            var user = [];
            $.ajax({
                url: '{{ url('/data/purchase-order/perintah-kirim') }}',
                type: 'GET',
                success: (response) => {
                    let gudang = null
                    let awak = null
                    $table.empty()
                    $table.append($headTable)
                    let {
                        data
                    } = response
                    data.forEach((po, index) => {

                        var d = new window.Date(po.created_at);
                        var day = d.getDate();
                        var month = d.getMonth();
                        var year = d.getFullYear();
                        var dd = day + '-' + month + '-' + year;
                        var template = `
                                <tbody class="table-section" data-plugin="tableSection">
                                    <tr>
                                        <td class="text-center">${index+1}</td>

                                        <td class="font-weight-medium" colspan="2">
                                            ${ po.no_nota }
                                        </td>
                                        <td>
                                            <span class="font-weight-medium">${ po.user.name }</span>
                                        </td>
                                        `
                                        if(po.tagihans[0].metode_bayar == 'Transfer') {
                                            if(po.tagihans[0].status == 'BELUM DIBAYAR') {
                                                template += `<td><span class="badge badge-danger">${ po.tagihans[0].status }</span></td>`
                                            }else if(po.tagihans[0].status == 'DIBAYAR SEBAGIAN') {
                                                template += `<td><span class="badge badge-warning">${ po.tagihans[0].status }</span></td>`
                                            }else if(po.tagihans[0].status == 'LUNAS') {
                                                template += `<td><span class="badge badge-success">${ po.tagihans[0].status }</span></td>`
                                            }
                                        }else if(po.tagihans[0].metode_bayar == 'COD') {
                                            template += `<td><span class="badge badge-success">${ po.tagihans[0].metode_bayar }</span></td>`
                                        }

                                        template +=`
                                        <td>
                                            <span class="text-muted">${ dd }</span>
                                        </td>
                                        <td>
                                            ${ po.user.address }
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody>
                                    <tr>
                                        <td colspan="7" class="p-0">`


                        // retrive detail
                        var data_tagihan = []

                        po.tagihans.forEach((tagihan, _index) => {
                            var total = tagihan.nominal_total != null ? 'Rp. '+tagihan.nominal_total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") : '-'
                            template += `
                                <tbody class="table-section" data-plugin="tableSection">
                                    <tr style="cursor: pointer">
                                        <td>
                                            <div class="checkbox-custom checkbox-warning">
                                                <input type="checkbox" class="check" name="check[]" value="${ idx_array_tagihans }">
                                                <label for="inputUnchecked"></label>
                                            </div>
                                        </td>
                                        <td class="text-center"><i class="table-section-arrow"></i></td>
                                        <td width="">
                                            Detail Pesanan
                                            <input type="hidden" id="id_tagihan" name="id_tagihan" value="${tagihan.id}">
                                        </td>
                                        <td class="font-weight-medium text-success" colspan="2">
                                            ${moment(tagihan.created_at).format('dddd, DD MMMM YYYY')}
                                        </td>
                                        <td class="font-weight-medium text-success" colspan="3">
                                            ${ total }
                                        </td>
                                    </tr>
                                </tbody>
                                <tbody>`

                            array_tagihans.push({
                                    "Id": tagihan.id,
                                    "tagihans": po.tagihans[0],
                                    "target": po.user,
                                    "po_id": po.id,
                                    "tagihan_id": tagihan.id,
                                    "orders": tagihan.orders,
                                })
                            idx_array_tagihans++

                            template += `<tr>

                                            <td></td>
                                            <td class="font-weight-bold" colspan="2">NAMA PRODUK</td>
                                            <td class="font-weight-bold">QTY</td>
                                            <td class="font-weight-bold">RINCIAN HARGA</td>
                                            <td class="font-weight-bold">INFO GUDANG</td>
                                        </tr>`
                            tagihan.orders.forEach((order, _index2) => {
                                template += `
                                    <tr>

                                        <td></td>
                                        <td class="font-weight-medium text-info" colspan="2">
                                            ${ order.nama_barang }
                                        </td>
                                        <td>${ order.qty }</td>
                                        <td>${  'Rp. '+(order.harga_order).toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")  }</td>
                                        <td colspan="2">
                                            `
                                            $.ajax({
                                                url : `{{ url('/data/purchase-order/info-gudang') }}/${order.id}/${tagihan.id}`,
                                                type: 'GET',
                                                async: false,
                                                success: (res) => {
                                                    gudang = res.data
                                                    template += `
                                                        `
                                                        gudang.forEach(pilGud => {
                                                            template += `
                                                            <div class="form-group">
                                                              <label for="exampleInputEmail1">${pilGud.Gudang}</label>
                                                              <input type="number" id="${pilGud.id}_${order.id}" name="qty_gudang[${tagihan.id}][${order.id}][${pilGud.id}]" max="${order.qty}" min="0" oninput="qty_input(this.value,${order.tagihan_id},`+pilGud.id+`,${order.id})" class="form-control qty_gudang${order.tagihan_id}" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Jumlah Unit Dikirim" disabled>
                                                            </div>
                                                            `
                                                    });
                                                    template +=`
                                                `
                                                }
                                            })
                                            template += `
                                        </td>
                                    </tr>`
                            }) // foreach

                            template += `</tbody>`

                        }) // foreach
                        // orders.push({"target" : po.user.group_id});

                        template += `</td></tr></tbody>`
                        $table.append(template)

                    }) // foreach

                }
                 // on success
            }) // ajax

            $.ajax({
                url: '{{ url('data/drivers') }}',
                type: 'GET',
                success: (response) => {
                  console.log(response)
                    let {
                        data
                    } = response
                    data.forEach((driver, index) => {
                        $('#list-driver').append(
                            `<option value="${ driver.id }">${ driver.name }</option>`
                        )
                    })
                }
            })
            $(document).on('change', '.check', function() {
                if ($(this).is(':checked')) {
                    var data = array_tagihans[$(this).val()];
                     $('.qty_gudang'+data.Id).prop("disabled", false);
                    dataChecked.push(data);
                } else {
                    var data = array_tagihans[$(this).val()];
                    var x = dataChecked.indexOf($(this).val());
                    $('.qty_gudang'+data.Id).prop("disabled", true);
                    dataChecked.splice(x, 1);
                }
                console.log("Data Checked ",dataChecked);
            });
        }
        $(document).ready(function() {
            loadPage()
        })

        $("#show-hide").on("change",function(e) {
            if($(this).prop("checked")) {
                $("#memo").show();
                $("#checkbox").text("Show");
            } else{
                $("#memo").hide();
                $("#checkbox").text("Hide");
            }
        });

        $(document).on('click', '.btn_simpan', function() {
            var memo = document.getElementById('memo').value;
            var id_driver = $("#list-driver").val();
            var data = dataChecked;
            var gudang_detail_unit = formData;
            var jatuh_tempo = $("#jatuh_tempo").val();
            // $(".btn_simpan").attr('disabled', true);
            $.ajax({
                url: "{{ url('data/purchase-order/kirim') }}",
                type: 'POST',
                dataType: 'json',
                data: {
                    memo: memo,
                    id_driver: id_driver,
                    data: data,
                    jatuh_tempo : jatuh_tempo,
                    gudang_detail_unit: gudang_detail_unit,
                },
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: (response) => swal({
                    title: 'Yeaaay!',
                    text: response.message,
                    type: "success"
                }, function() {
                    // window.location.href = "{{url('dashboard/order/tagihan')}}"
                    $(".btn_simpan").attr('disabled', true);
                    $('.close').click()
                    loadPage()
                    $(".btn_simpan").attr('disabled', false);
                }),
                error: (response) => swal({
                    title: 'Hufffttt!',
                    text: response.responseJSON.message,
                    type: "error"
                })
            }).done(function() {
                dataChecked = [];
                $(".modal-dialog").modal("hide");
            });
        });

        $(".btn-kirim").click(function() {
            for(let i = 0; i < dataChecked.length; i++) {
                if(dataChecked.length > 0) {
                    if(dataChecked[i].tagihans.metode_bayar == "Transfer") {
                        if(dataChecked[i].tagihans.status == "LUNAS") {
                            $("#modal-select-driver").modal("show");
                            $('#modal-select-driver').on('shown.bs.modal', function(e) {
                                $('.datepicker').datepicker({
                                    format: "yyyy-mm-dd",
                                    todayBtn: "linked",
                                    autoclose: true,
                                    todayHighlight: true
                                });
                            });
                        }else if(dataChecked[i].tagihans.status == "DIBAYAR SEBAGIAN") {
                            $("#modal-select-driver").modal("show");
                            $('#modal-select-driver').on('shown.bs.modal', function(e) {
                                $('.datepicker').datepicker({
                                    format: "yyyy-mm-dd",
                                    todayBtn: "linked",
                                    autoclose: true,
                                    todayHighlight: true
                                });
                            });
                        }else if(dataChecked[i].tagihans.status == "BELUM DIBAYAR") {
                            swal({
                                title: 'Tagihan belum dibayar !',
                                text: 'Mohon menunggu tagihan dibayar',
                                type: "error"
                            });
                        }
                    }else if(dataChecked[i].tagihans.metode_bayar == "COD") {
                        $("#modal-select-driver").modal("show");
                        $('#modal-select-driver').on('shown.bs.modal', function(e) {
                            $('.datepicker').datepicker({
                                format: "yyyy-mm-dd",
                                todayBtn: "linked",
                                autoclose: true,
                                todayHighlight: true
                            });
                        });
                    }
                } else {
                    swal({
                        title: 'Tidak ada pesanan yang dipilih!',
                        text: 'Mohon memilih pesanan setidaknya 1 pesanan',
                        type: "error"
                    })
                }
            }
        });
        $(".btn-kirim").click(function() {
            if(dataChecked.length == 0) {
                swal({
                    title: 'Tidak ada pesanan yang dipilih!',
                    text: 'Mohon memilih pesanan setidaknya 1 pesanan',
                    type: "error"
                })
            }
        });
    </script>
@endsection
