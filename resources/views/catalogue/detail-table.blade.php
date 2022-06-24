@php
$showNavigation = false;
$bodyType = 'site-menubar-unfold';
@endphp

@extends('app')
@section('css')

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
				{	
					data: "id",
					render: function (data, type, row, meta) {
						return meta.row + meta.settings._iDisplayStart + 1;
					}
				},
				{ data: 'nama' },
				{ data: 'deskripsi' },
				{ 
					data: null,
					render: function(data, type,full,meta) {
						console.log(data)
						return data['stok'] + ' ' + data['satuan']
					}
				},
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
									+'<input type="number" class="form-control" style="text-align: center;" id="'+data['id']+'" max="'+data['stok']+'" value="1" />'
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

						table_cart.ajax.reload();
					},
					error:function(){
						alert("error");
					}
				});
			});
		@endauth
	});
</script>
@endsection
