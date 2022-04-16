@php
$showNavigation = false;
$bodyType = 'site-menubar-unfold';
@endphp

@extends('app')
@section('css')
  <style media="screen">
      .dataTables_filter {
       width: 50%;
       float: right;
       text-align: left;
      }
  </style>
@endsection
@section('page')
<div class="page-content">
    <div class="panel">
        <div class="panel-body">
            <div class="table-responsive">
                <div class="title">
                    <span class="title-catalogue" style="margin-bottom: 5px; color: blue"><b>{{str_replace('__', ' ',$barang_alias)}}</b>
                    </span>
                    <table class="table dataTable" id="table-katalog">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th width="25%">Nama Produk</th>
                                <th width="25%">Deskripsi</th>
                                <th width="15%">Stok</th>
                                <th width="15%">Harga</th>
                                <th></th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="py-15">
        <div class="text-right">
            <ul class="pagination" role="navigation" id="pagination">
            </ul>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Page -->
<script src="{{ asset('public/themeforest/global/js/Plugin/datatables.js') }}"></script>
<script src="{{ asset('public/themeforest/page-base/examples/js/tables/datatable.js') }}"></script>
<script src="{{ asset('public/themeforest/page-base/examples/js/uikit/icon.js') }}"></script>

<script>
    $(document).ready(function() {
        // load()


        $('#table-katalog').dataTable({
           processing: true,
            serverSide: true,
            searching: true,
            ajax: {
              url: '{{route('get_detailBarang')}}',
              type: "POST",
              data: function (d) {
                  d._token = "{{ csrf_token() }}";
                  d.alias = "{{$barang_alias}}";
                  console.log("pp", d)
              }

            },
            columns: [
              {  data: "id",
                render: function (data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }},
              { data: 'nama' },
              { data: 'deskripsi' },
              { data: 'stok' },
              {
                data: null,
                render: function(data, type,full,meta){
                  if (data['harga'] != null) {
                    return data['harga'];
                  }
                  else {
                    return '0';
                  }
                }
               },
              {
                  data: null,
                  render: function(data, type, full, meta){
                    @auth
                    let en ='';
                    if (data['harga'] == null) {
                      en = 'disabled'
                    }
                   return   '<div class="card-block text-center div_card_beli my-cart-btn" style=" padding-top: 5px;">'
                            +'<div class="input-group bootstrap-touchspin bootstrap-touchspin-injected" style="width: 250px;">'
                            +'<input type="number" class="form-control" style="text-align: center;" id="'+data['id']+'" max="'+data['stok']+'" name="touchSpinPrefix"'
                            +'data-plugin="TouchSpin" data-min="1" data-max="1000" data-stepinterval="50" data-maxboostedstep="1000" value="1" />'
                            +'<span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">'
                            +'<span class="input-group-text">Unit/Jumlah<b id="data-stock"></b></span>'
                            +'</span>'
                            +'<button value="'+data['id']+'"  class="btn btn-md btn-round add-to-cart"'
                            +'style="background: #fb8b34; color: white; margin-left: 10px; font-weight: bold" '+en+'>'
                            +'<i class="icon md-shopping-cart" aria-hidden="true"></i>'
                            +'</button>'
                            +'</div>'
                            +'</div>';
                    @endauth
                    @guest
                      return ''
                    @endguest
                 }
               },
             ],
        });
        @auth
        $(document).on('click', '.add-to-cart', function(){
          var btn=$(this).val();
          var user = {{Auth::user()->id}}
          var stk = $("#"+btn).val();
          $.ajax({
             url:"{{route('addCart')}}",
             method:"POST", //First change type to method here
             data:{
               "_token": "{{ csrf_token() }}",
               id_barang: btn,
               id_user: user,
               jumlah: stk
             },
             success:function(response) {
              toastr['success']('Berhasil ditambahkan')
              cart_count()

              table_cart_ndess.ajax.reload();
              },
              error:function(){
               alert("error");
              }

           });
        });
        @endauth
        // $('.buyitem').click(function() {
        //       const $parent = $($(this).parent())
        //       qty = $($parent.children()[0]).val()
        //
        //       const product = data[$(this).data('id')]
        //       var template = `
        //           <tr style="text-align: center">
        //               <td>1.</td>
        //               <td><a class="waves-effect waves-light waves-round" style="color: blue">${ product.nama }</a></td>
        //               <td><input type="text" class="form-control qty" style="text-center" id="catatan" value=""/></td>
        //               <td>Rp ${ product.harga_user[0].harga_user.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") }</td>
        //               <td>${ qty }</td>
        //               <td>Rp ${ qty * product.harga} </td>
        //           </tr>`
        //
        //       let total = qty * product.harga
        //
        //       $('.pembelian-content').empty()
        //       $('.pembelian-content').append(template)
        //       $('#data-harga').html(`Rp ${ total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") }`)
        //       $('#btn-buy-item').data('id', $(this).data('id'))
        //       $('#btn-buy-item').click(function() {
        //           setTimeout(function() {
        //               $('#purchaseOrder').modal('hide');
        //           }, 2000);
        //       })
        //       $('#btn-buy-item').click(function() {
        //           var arr = []
        //           var index = $(this).data('id')
        //           var param = data[index]
        //           param.qty = qty
        //
        //           arr.push(param)
        //           buy(arr, total)
        //           window.location.reload(true)
        //       })
        //   })
        //
        //   $('.add-to-cart').click(function() {
        //       const $parent = $($(this).parent())
        //       qty = $($parent.children()[0]).val()
        //       product = data[$(this).data('id')]
        //
        //       addToChart(product, qty)
        //   });
        //   $(document).on('click', '.add-to-cart', function() {
        //       const $parent = $($(this).parent())
        //       console.log(res.data)
        //       qty = $($parent.children()[0]).val()
        //       product = res.data[$(this).data('id')]
        //       dataUndefined = $(this).data('undefined')
        //       if (dataUndefined == false) {
        //           id = product.id
        //           console.log($(this).data('id'))
        //
        //           addToChart(product, qty, id)
        //       } else {
        //           toastr['error']('Aksi gagal, hubungi admin untuk informasi lebih lanjut')
        //           console.log($(this).data('undefined'))
        //       }
        //   });
    //
    //     $('#btn-buy').click(function() {
    //         let total = 0
    //         carts.forEach((product, _index) => total += parseInt(product.harga_user[0].harga_user) * parseInt(product.qty))
    //         buy(carts, total)
    //         carts = []
    //     })
    //
    //     //load katalog
    //     // $.ajaxSetup({
    //     //     headers: {
    //     //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     //     }
    //     // });
    //
    //     // table = $('#table-katalog').DataTable({
    //     //     processing: true,
    //     //     serverSide: true,
    //     //     bInfo: false,
    //     //     ajax: `{{ url('data/catalogue/products/detail') }}/{{$barang_alias}}`,
    //     //     columns: [
    //     //         {data: 'id'},
    //     //         {data: 'nama'},
    //     //         {data: 'deskripsi'},
    //     //         {data: 'unit_masuk'},
    //     //         {data: 'harga_user'}
    //     //     ]
    //     // });
    // });
    // let newHargaProduk = []
    // function load(url = `{{ url('data/catalogue/products/detail') }}/{{$barang_alias}}`) {
    //     $.getJSON({
    //         url: url,
    //         type: 'GET',
    //         async: true
    //     }).then((res) => {
    //         console.log("kk",res)
    //         $('#table-body').empty()
    //         res.data.forEach((product, index) => {
    //             console.log(product)
    //             let template =
    //                 `
    //                     <tr>
    //                         <td width="25%">
    //                             <div class="card-block text-center div_card_beli my-cart-btn" style=" padding-top: 5px;">
    //                                 <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected" style="width: 180px;">
    //                                     <input type="text" class="form-control" style="text-align: center;" name="touchSpinPrefix"
    //                                         data-plugin="TouchSpin" data-min="1" data-max="1000" data-stepinterval="50" data-maxboostedstep="1000" value="1" />
    //                                     <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">
    //                                         <span class="input-group-text">Stok<b id="data-stock"></b></span>
    //                                     </span>
    //                                     @if (Auth::check())
    //                                     <button type="button" class="btn btn-md btn-round add-to-cart"
    //                                         style="background: #fb8b34; color: white; margin-left: 10px; font-weight: bold" data-id="${ index }" data-undefined="${typeof product.harga_user[0]=='undefined'}">
    //                                         <a class="icon md-shopping-cart" aria-hidden="true"></a>
    //                                     </button>
    //
    //                                     @else
    //                                         <a href="{{ url('/login') }}" class="btn btn-md btn-round"
    //                                             style="background: #fb8b34; color: white; margin-left: 10px; font-weight: bold">
    //                                             <i class="icon md-shopping-cart" aria-hidden="true"></i>
    //                                         </a>
    //                                     @endif
    //                                 </div>
    //                             </div>
    //                         </td>
    //                         <td>${ product.nama }</td>
    //                         {{-- <td>${ product.category.name }</td> --}}
    //                         <td>${ product.deskripsi }</td>
    //                         <td>${(product.unit_masuk - product.unit_keluar)}</td>
    //                         `
    //                         if (product.harga_user.length>0) {
    //                             template+=`
    //                             <td>Rp. ${ product.harga_user[0].harga_user.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") }</td>`
    //                         }
    //                         else{
    //                             template+=`
    //                             <td>Rp. 0</td>`
    //                         }
    //                         template+=`
    //                     </tr>
    //                     `
    //
    //             $('#table-body').append(template);
    //         })
    //
    //         let pagination = ''
    //         if (res.data.current_page == 1) {
    //             pagination += `<li class="page-item" aria-current="page"><span class="page-link">‹</span></li>`
    //         } else {
    //             pagination += `<li class="page-item"><button class="page-link" onclick="load('${ res.data.prev_page_url }">‹</button></li>`
    //         }
    //
    //         for (var i = 1; i <= res.data.last_page; i++) {
    //             if (i == res.data.current_page) {
    //                 pagination += `<li class="page-item active" aria-current="page"><span class="page-link">${ i }</span></li>`
    //             } else {
    //                 pagination += `<li class="page-item"><button class="page-link" onclick="load('${ res.data.path }?page=${ i }')">${ i }</button></li>`
    //             }
    //         }
    //
    //         if (res.data.current_page == res.data.last_page) {
    //             pagination += `<li class="page-item" aria-current="page"><span class="page-link">›</span></li>`
    //         } else {
    //             pagination += `<li class="page-item"><button class="page-link" onclick="load('${ res.data.next_page_url }')">›</button></li>`
    //         }
    //
    //
    //         $('#pagination').empty()
    //         $('#pagination').append(pagination)
    //
    //         // $('.buyitem').click(function() {
    //         //     const $parent = $($(this).parent())
    //         //     qty = $($parent.children()[0]).val()
    //
    //         //     const product = data[$(this).data('id')]
    //         //     var template = `
    //         //         <tr style="text-align: center">
    //         //             <td>1.</td>
    //         //             <td><a class="waves-effect waves-light waves-round" style="color: blue">${ product.nama }</a></td>
    //         //             <td><input type="text" class="form-control qty" style="text-center" id="catatan" value=""/></td>
    //         //             <td>Rp ${ product.harga_user[0].harga_user.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") }</td>
    //         //             <td>${ qty }</td>
    //         //             <td>Rp ${ qty * product.harga} </td>
    //         //         </tr>`
    //
    //         //     let total = qty * product.harga
    //
    //         //     $('.pembelian-content').empty()
    //         //     $('.pembelian-content').append(template)
    //         //     $('#data-harga').html(`Rp ${ total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") }`)
    //         //     $('#btn-buy-item').data('id', $(this).data('id'))
    //         //     $('#btn-buy-item').click(function() {
    //         //         setTimeout(function() {
    //         //             $('#purchaseOrder').modal('hide');
    //         //         }, 2000);
    //         //     })
    //         //     $('#btn-buy-item').click(function() {
    //         //         var arr = []
    //         //         var index = $(this).data('id')
    //         //         var param = data[index]
    //         //         param.qty = qty
    //
    //         //         arr.push(param)
    //         //         buy(arr, total)
    //         //         window.location.reload(true)
    //         //     })
    //         // })
    //
    //         // $('.add-to-cart').click(function() {
    //         //     const $parent = $($(this).parent())
    //         //     qty = $($parent.children()[0]).val()
    //         //     product = data[$(this).data('id')]
    //
    //         //     addToChart(product, qty)
    //         // });
    //         $(document).on('click', '.add-to-cart', function() {
    //             const $parent = $($(this).parent())
    //             console.log(res.data)
    //             qty = $($parent.children()[0]).val()
    //             product = res.data[$(this).data('id')]
    //             dataUndefined = $(this).data('undefined')
    //             if (dataUndefined == false) {
    //                 id = product.id
    //                 console.log($(this).data('id'))
    //
    //                 addToChart(product, qty, id)
    //             } else {
    //                 toastr['error']('Aksi gagal, hubungi admin untuk informasi lebih lanjut')
    //                 console.log($(this).data('undefined'))
    //             }
    //         });
        });
</script>
@endsection
