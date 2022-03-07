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
            @foreach (App\User::all() as $i => $item)
                <tr id="user_id{{$item->id}}">
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->address}}</td>
                    <td>{{ $item->no_handphone }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->group_id ?? '' }}</td>
                    <td align="center">
                        <button class="btn btn-sm btn-icon btn-pure btn-default on-default" data-toggle="tooltip"
                            type="button" data-original-title="Edit Data Produk">
                            <a href=""
                                data-original-title="Edit"><i class="icon md-edit" aria-hidden="true"></i></a>
                        </button>

                        <button class="btn btn-sm btn-icon btn-pure btn-default on-default removeUserConfirmation"
                            type="button" data-id="{{ $item->id }}">
                            <i class="icon md-delete" aria-hidden="true"></i>
                        </button>
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
                            <form id="formUser" name="formUser" class="form-horizontal" method="post" action="">
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
                                        <input type="text" class="form-control" name="no_handphone" id="no_handphone" value=""/>
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
                                        <select class="form-control option-bayar" id="id_group" required="">
                                            <option value="">Pilih Group User</option>
                                            @foreach (App\GroupUser::all() as $i)
                                            <option value="{{ $i['group_name'] }}">{{ $i['group_name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
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
                            <form id="formEdit" name="formEdit" class="form-horizontal" novalidate="" method="post" action="{{ route('') }}">
                                <div class="modal-body">
                                    <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                        <div class="content col-6">
                                            <p>Nama</p>
                                            <input type="text" class="form-control" name="nama" id="nama" value=""/>
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
                                            <input type="text" class="form-control" name="alamat" id="alamat" value=""/>
                                        </div>
                                        <div class="col-6">
                                            <p>No Telepon</p>
                                            <input type="text" class="form-control" name="no_handphone" id="no_handphone" value=""/>
                                        </div>
                                    </div>
                                    <br/>
                                    <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                        <div class="content col-6">
                                            <p>Password</p>
                                            <input type="password" class="form-control" name="password1" id="password1" value=""/>
                                            <input type="checkbox" onclick="myPassword()" style="margin-left: 120pt"> show password
                                        </div>
                                        <div class="col-6">
                                            <p>Group User</p>
                                            <input type="hidden" value="" id="users" name="users"> 

                                            <select class="form-control option-bayar" id="groupUser" name="groupUser" required="">
                                                <option value="">Pilih Group User</option>
                                                @foreach (App\GroupUser::all() as $i)
                                                <option value="{{ $i['group_name'] }}">{{ $i['group_name'] }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-warning" id="editsUser">Update</button>
                <button type="button" class="btn btn-secondary" data-toggle="modal" id="back1" data-dismiss="modal">Back</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
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
    showSwalAlert({
        element: $('.removeUserConfirmation'),
        title: 'Peringatan!',
        text: 'Apa anda yakin untuk menghapus user ini!',
        type: 'warning',
        callback: () => {
            swal.close()

            $.ajax({
                url:`{{ url('deleteUser') }}/` + $('.removeUserConfirmation').data('id'),
                type: 'DELETE',
                dataType: 'JSON',
                processData: false,
                contentType: false,
                complete: (res) => {
                    res = res.responseJSON;

                    toastr.options.onShown = () => window.location.reload(true)
                    toastr[res.status](res.message)
                }
            })
        }
    })
</script>
@endsection
