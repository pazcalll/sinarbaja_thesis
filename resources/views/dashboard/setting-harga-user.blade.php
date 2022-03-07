@extends('app')
@section('css')
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/page-base/examples/css/tables/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/bootstrap-sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/toastr/toastr.css') }}">

    @yield('style')
@endsection
@section('page')

    <div class="page-content">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-15">
                    <h3>Setting Harga User</h3>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="container">
                    <div class="row row-lg">
                        <div class="col-md-12">
                            <div class="example-wrap">
                                <div class="example">
                                    <label class="title" style="font-size: 15pt; color: blue; font-weight: bold;">List User</label>
                                    <table class="table table-bordered table-hover table-striped" id="table_setting_harga">
                                        <thead>
                                            <tr width="100%">
                                                <th style="width: 10%;">No</th>
                                                <th style="width: 30%;">User</th>
                                                <th style="width: 30%;">Group User</th>
                                                <th style="width: 30%;">Aksi</th>
                                                <th style="width: 30%;">Harga View</th>
                                                <th style="width: 30%;">Stok View</th>
                                            </tr>
                                        </thead>
                                        @php $no = 1; @endphp
                                        @foreach ($settingHargaUser as $key => $item )
                                        @php
                                          $item->harga == 'on'?$check_harga = 'checked':$check_harga = null;
                                          $item->stok == 'on'?$check_stok = 'checked':$check_stok = null;
                                        @endphp
                                        <tbody>
                                            <tr>
                                                <td>{{$no++}}</td>
                                                <td>{{$item->name}}</td>
                                                <td>{{$item->group_name}}</td>
                                                <td align="center">
                                                    <button class="btn btn-sm btn-icon btn-pure btn-default on-default" data-toggle="tooltip"
                                                        type="button" data-id="{{$item->user_id}}" data-user-name="{{$item->name}}" data-id-group="{{$item->id_group}}">
                                                        <a href="{{ url('dashboard/setting-harga-user/harga-user') }}/{{$item->user_id}}/{{$item->name}}/{{$item->id_group}}">
                                                            <i class="icon md-money" aria-hidden="true"></i>
                                                        </a>
                                                    </button>
                                                </td>
                                                <td style="text-align: center;vertical-align: middle;">
                                                  <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="check_harga{{$item->user_id}}" onchange="check_harga(this,{{$item->user_id}})" {{$check_harga}} value="on">
                                                  </div>
                                                </td>
                                                <td style="text-align: center;vertical-align: middle;">
                                                  <div class="form-check form-check-inline">
                                                    <input class="form-check-input" type="checkbox" id="check_stok{{$item->user_id}}" onchange="check_stok(this,{{$item->user_id}})" value="{{$item->user_id}}" {{$check_stok}} value="on">
                                                  </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $("#table_setting_harga").DataTable({
                "scrollY":"250px",
                "scrollCollapse": true,
                "paging":false,
                "searching":false
            });
        });
        function check_harga(cb,id){
          Swal.fire({
              title: 'Apakah anda yakin?',
              showDenyButton: true,
              confirmButtonText: 'Ya',
              denyButtonText: `Tidak`,
            }).then((result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                $.ajax({
                    url:"{{route('settingHarga')}}",
                    type:"POST",
                    data:{
                      id: id,
                      kondisi: cb.checked
                    },
                    success:function(response) {
                     Swal.fire('Berhasil merubah pengaturan', '', 'success')
                   },
                   error:function(){
                     Swal.fire('Gagal merubah pengaturan', '', 'error')
                   }

                  });
              } else if (result.isDenied) {
                if (cb.cheked == true) {
                  $('#check_harga'+id).prop( "checked", false );
                }
                else {
                  $('#check_harga'+id).prop( "checked", true );
                }
              }
            })
        }
        function check_stok(cb,id){
          Swal.fire({
              title: 'Apakah anda yakin?',
              showDenyButton: true,
              confirmButtonText: 'Ya',
              denyButtonText: `Tidak`,
            }).then((result) => {
              /* Read more about isConfirmed, isDenied below */
              if (result.isConfirmed) {
                $.ajax({
                    url:"{{route('settingStok')}}",
                    type:"POST",
                    data:{
                      id: id,
                      kondisi: cb.checked
                    },
                    success:function(response) {
                     Swal.fire('Berhasil merubah pengaturan', '', 'success')
                   },
                   error:function(){
                     Swal.fire('Gagal merubah pengaturan', '', 'error')
                   }

                  });
              } else if (result.isDenied) {
                if (cb.cheked == true) {
                  $('#check_stok'+id).prop( "checked", false );
                }
                else {
                  $('#check_stok'+id).prop( "checked", true );
                }
              }
            })
        }
    </script>
@endsection
