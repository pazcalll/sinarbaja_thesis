@extends('template.pages.datatable', [
'page' => 'Account User',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Account', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Account User', 'link' => '', 'active' => 'active']
]
])

@section('top-panel')
<div class="row">
    <div class="col-md-6">
        <div class="mb-15">
            <button id="addUser" class="btn btn-primary" type="button">
                <i class="icon md-plus" aria-hidden="true"></i> Add User
            </button>
        </div>
    </div>
</div>
@endsection

@section('css')
<link href="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/css/bootstrap4-toggle.min.css" rel="stylesheet">
@endsection

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Alamat</th>
                <th>No. Telepon</th>
                <th>Email</th>
                <th>Group User</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data['users'] as $i => $item)
                <tr id="user_id{{$item->id}}">
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->address}}</td>
                    <td>{{ $item->no_handphone }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ (App\GroupUser::where('id', $item->id_group)->count() > 0)?App\GroupUser::where('id', $item->id_group)->get('group_name')[0]->group_name:'-'}}</td>
                    <td align="center">
                        <a class="edit" data-original-title="Edit" data-toggle="modal" data-target="#modalEdit" 
                            data-id="<?php echo $item->id; ?>" data-name="<?php echo $item->name; ?>" data-id_profil="<?php echo $item->id_profil; ?>" data-email="<?php echo $item->email; ?>" 
                            data-address="<?php echo $item->address; ?>" data-no_handphone="<?php echo $item->no_handphone; ?>" 
                            data-id_group="<?php echo $item->id_group; ?>" data-id_group_driver="{{($item->id_group == 3)?$data['group'][$item->id]:''}}" style="margin-right: 20pt; cursor: pointer;">
                            <i class="icon md-edit" aria-hidden="true"></i></a>
                        <a href="{{ url('data')}}/delete/user/{{ $item->id }}" class="button removeUserConfirmation">
                            <i class="icon md-delete" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
<!--Modal Add User-->
@section('modal')
<div class="modal fade example-modal-lg modal-3d-sign"  data-toggle="modal" id="modalUser" aria-hidden="true" aria-labelledby="modalUser" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="titleUser" style="color: blue;">Tambah User</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Account User</h4>
                    <div class="example">
                        <div class="modal-body">
                            <form id="formUser" name="formUser" class="form-horizontal" method="post" action="{{route('createUser') }}">
                                @csrf
                                <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                    <div class="content col-6">
                                        <p>Nama</p>
                                        <input type="text" class="form-control" name="name" id="name" value=""/>
                                    </div>
                                    <div class="col-6">
                                        <p>Email</p>
                                        <input type="text" class="form-control" name="email" id="email" value=""/>
                                    </div>
                                </div>
                                <br/>
                                <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                    <div class="content col-6">
                                        <p>Alamat</p>
                                        <input type="text" class="form-control" name="address" id="address" value=""/>
                                    </div>
                                    <div class="col-6">
                                        <p>No Telepon</p>
                                        <input type="number" class="form-control" name="no_handphone" id="no_handphone" value=""/>
                                    </div>
                                </div>
                                <br/>
                                <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                    <div class="content col-6">
                                        <p>Password</p>
                                        <input type="password" class="form-control" name="password" id="password" value=""/>
                                        <input type="checkbox" onclick="myPassword()" style="margin-left: 135pt"> show password
                                    </div>
                                    <div class="col-6">
                                        <p>Group User</p>
                                        <select class="form-control option-bayar" name="group" id="id_group" required="">
                                            <option disabled selected value="">Pilih Group User</option>
                                            @foreach (App\GroupUser::whereNotIn('id', [1])->get() as $i)
                                            <option value="{{ $i['id'] }}">{{ $i['group_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                {{-- <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                    <div class="col-6">
                                        <p>Profil Toko</p>
                                        <select class="form-control option-bayar" name="profil" id="id_profil" required="">
                                            <option disabled selected value="">Pilih Profil Toko</option>
                                            @foreach (App\Profil::all() as $i)
                                            <option value="{{ $i['id'] }}">{{ $i['nama'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> --}}
                                <div class="modal-footer">
                                    <button type="button" from="rereturn" class="btn btn-secondary" data-toggle="modal" id="back1" data-dismiss="modal">Back</button>
                                    <button type="submit" class="btn btn-warning">Save</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal Edit -->
<div class="modal fade example-modal-lg modal-3d-sign"  data-toggle="modal" id="modalEdit" aria-hidden="true" aria-labelledby="modalEdit" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="titleGroup" style="color: blue;">Edit User</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Account User</h4>
                    <div class="example">
                        <div class="modal-body">
                            <form id="formEdit" name="formEdit" class="form-horizontal" novalidate="" method="post" action="#">
                            @csrf
                            <input type="hidden" name="status_group_driver">
                                <div class="modal-body">
                                    <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                        <div class="content col-6">
                                            <p>Nama</p>
                                            <input type="text" class="form-control" name="nameEdit" id="nameEdit" value="<?php echo $item->name; ?>"/>
                                            <input type="hidden" class="form-control" name="idEdit" id="idEdit" value=""/>
                                        </div>
                                        <div class="col-6">
                                            <p>Email</p>
                                            <input type="text" class="form-control" name="emailEdit" id="emailEdit" value=""/>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                        <div class="content col-6">
                                            <p>Alamat</p>
                                            <input type="text" class="form-control" name="addressEdit" id="addressEdit" value=""/>
                                        </div>
                                        <div class="col-6">
                                            <p>No Telepon</p>
                                            <input type="number" class="form-control" name="no_handphoneEdit" id="no_handphoneEdit" value=""/>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                        <div class="content col-6">
                                            <p>Password</p>
                                            <input type="password" class="form-control" name="passwordEdit" id="passwordEdit" value=""/>
                                            <input type="checkbox" onclick="myPassword1()" style="margin-left: 120pt"> show password
                                        </div>
                                        <div class="col-6">
                                            <p>Group User</p>
                                            <select class="form-control option-bayar" id="id_groupEdit" name="id_groupEdit" required="">
                                                <option disabled selected value="">Pilih Group User</option>
                                                @foreach (App\GroupUser::whereNotIn('id', [1])->get() as $i)
                                                <option value="{{ $i['id'] }}">{{ $i['group_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    {{-- <br/> --}}
                                    {{-- <div class="row col-10" id="switch-status" style="margin-left: 50pt; text-align: center;">
                                        <div class="content col-6">
                                            <p>Status</p>
                                            <input name="status" id="status" type="checkbox" data-toggle="toggle" data-on="ACTIVE" data-off="NOT-ACTIVE" 
                                                data-onstyle="success" data-width="120" data-height="30" data-offstyle="danger">
                                        </div>
                                    </div> --}}
                                    {{-- <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                        <div class="col-6">
                                            <p>Profil Toko</p>
                                            <select class="form-control option-bayar" name="id_profilEdit" id="id_profilEdit" required="">
                                                <option disabled selected value="">Pilih Profil Toko</option>
                                                @foreach (App\Profil::all() as $i)
                                                <option value="{{ $i['id'] }}">{{ $i['nama'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" id="back1" data-dismiss="modal">Back</button>
                                    <button form="formEdit" type="submit" class="btn btn-warning" id="editUser">Update</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/gh/gitbrent/bootstrap4-toggle@3.6.1/js/bootstrap4-toggle.min.js"></script>
<script>
    $(document).ready(function() {
        $('#status').on('change', function() {
            if($(this).prop('checked')) {
                $('[name=status_group_driver]').val('ACTIVE');
            }else{
                $('[name=status_group_driver]').val('NON-ACTIVE');
            }
        })
    })
    $('#addUser').click(function() {
        $('#modalUser').modal('show');
    })

    function myPassword() {
        var x = document.getElementById("password");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }
    $('.removeUserConfirmation').on('click', function(e) {
        e.preventDefault();
        const url = $(this).attr('href');
        swal({
            title: 'Hapus User !',
            text: 'Apa anda yakin untuk menghapus user ini !',
            icon: 'warning',
            buttons: ["Cancel", "Delete"],
        }).then(function(value) {
            if(value){
                window.location.href = url;
            }
        })
    })

    $('.edit').on('click', function(){
        $('#nameEdit').val(`${$(this).data('name')}`);
        $('#emailEdit').val(`${$(this).data('email')}`);
        $('#addressEdit').val(`${$(this).data('address')}`);
        $('#no_handphoneEdit').val(`${$(this).data('no_handphone')}`);
        $('#id_groupEdit').val(`${$(this).data('id_group')}`);
        // $('#id_profilEdit').val(`${$(this).data('id_profil')}`);
        var id_group_driver = $(this).data('id_group_driver');
        var x = $("#switch-status");
        if($(this).data('id_group') == 3) {
            x.show();
            const checkbox = $("#status");
            // console.log(id_group_driver)
            checkbox.prop('checked', false).change();
            if(id_group_driver == 'ACTIVE') checkbox.prop('checked',true).change();
        }else {
            // x.style.display == "block";
            x.hide();
        }
        $('#idEdit').val(`${$(this).data('id')}`);
    })

    $('#formEdit').on('submit', function(e) {
        e.preventDefault()
        // let newForm = new FormData(this)
        $.ajax({
            url:'{{ route("editUser") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: (res) => {
                // console.log(newForm)
                toastr['info']('Data user telah diubah')
                window.location.reload();

            },
            error: (err) => {
                console.error(err)
            }
        })
    })

    function myPassword1() {
        var x = document.getElementById("passwordEdit");
        if (x.type === "password") {
            x.type = "text";
        } else {
            x.type = "password";
        }
    }

</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection
