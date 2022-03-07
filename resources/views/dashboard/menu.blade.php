@extends('template.pages.datatable', [
'page' => 'Manajemen Menu',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Setting', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Manajemen Menu', 'link' => '', 'active' => 'active']
]
])

{{-- @section('top-panel')
<div class="row">
    <div class="col-md-6">
        <div class="mb-15">
            <button id="addMenu" class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalMenu">
                <i class="icon md-plus" aria-hidden="true"></i> Add Menu
            </button>
        </div>
    </div>
</div>
@endsection --}}

@section('table')
    <table class="table table-bordered table-hover table-striped" id="exampleAddRow">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Link</th>
                <th>Parent Menu</th>
                <th>Urutan</th>
                <th>Icon</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach (App\Menu::all() as $i => $item)
                    <tr class="gradeA">
                        <td>{{ $item->nama }}</td>
                        <td>{{ $item->link }}</td>
                        <td>{{ $item->parentString->nama ?? '' }}</td>
                        <td>{{ $item->urutan }}</td>
                        <td>{{ $item->icon }}</td>
                        <td align="center">
                        <a class="edit" data-original-title="Edit" data-toggle="modal" data-target="#modalEdit"
                            data-id="<?php echo $item->id; ?>" data-nama="<?php echo $item->nama; ?>" data-urutan="<?php echo $item->urutan; ?>" data-icon="<?php echo $item->icon; ?>" style="margin-right: 20px; cursor: pointer;">
                            <i class="icon md-edit" aria-hidden="true"></i>
                        </a>
                        <a href="{{ url('data') }}/setting/menu/delete/{{ $item->id }}" class="button removeMenuConfirmation">
                            <i class="icon md-delete" aria-hidden="true"></i>
                        </a>
                    </td>
                    </tr>
                
            @endforeach
        </tbody>
    </table>
@endsection

@section('modal')
<!-- Modal Create -->
<div class="modal fade example-modal-lg modal-3d-sign"  data-toggle="modal" id="modalMenu" aria-hidden="true" aria-labelledby="modalMenu" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="titleMenu" style="color: blue;">Tambah Menu</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Menu</h4>
                    <div class="example">
                        <div class="modal-body">
                            <form id="formMenu" name="formMenu" class="form-horizontal" method="post" action="">
                                @csrf
                                <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                    <div class="content col-6">
                                        <p>Nama</p>
                                        <input type="text" class="form-control" name="nama" id="nama" value=""/>
                                    </div>
                                    <div class="col-6">
                                        <p>Link</p>
                                        <input type="text" class="form-control" name="link" id="link" value=""/>
                                    </div>
                                </div>
                                <br/>
                                <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                    <div class="content col-6">
                                        <p>Urutan</p>
                                        <input type="text" class="form-control" name="urutan" id="urutan" value=""/>
                                    </div>
                                    <div class="col-6">
                                        <p>Icon</p>
                                        <input type="text" class="form-control" name="icon" id="icon" value=""/>
                                    </div>
                                </div>
                                <br/>
                                <div class="row col-10" style="margin-left: 50pt; text-align: center;">
                                    <div class="col-6">
                                        <p>Parent Menu</p>
                                        <select class="form-control option-bayar" name="group" id="id_group" required="">
                                            <option value="">Pilih Parent Menu</option>
                                            @foreach (App\Menu::all() as $i)
                                            if($i->link != null){
                                                $i->nama.hidden = true;
                                            }
                                            <option value="{{ $i['id'] }}">{{ $i['nama'] }}</option>
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
    <div class="modal-dialog modal-simple modal-center modal-lg-4">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="titleGroup" style="color: blue;">Edit Menu</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Menu</h4>
                    <div class="example">
                        <div class="modal-body">
                            <form id="formEdit" name="formEdit" class="form-horizontal" novalidate="" method="post" action="#">
                                @csrf
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><b>Nama Menu</b></label>
                                    <input type="text" class="form-control" id="namaEdit" name="namaEdit" value="<?php echo $item->nama ?>">
                                    <input type="hidden" class="form-control" name="idMenu" id="idMenu" value=""/>
                                    <br>
                                    <label class="col-sm-4 control-label"><b>Urutan</b></label>
                                    <input type="text" class="form-control" id="urutanEdit" name="urutanEdit" value="">
                                    <br>
                                    <label class="col-sm-4 control-label"><b>icon</b></label>
                                    <input type="text" class="form-control" id="iconEdit" name="iconEdit" value="">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" id="back1" data-dismiss="modal">Back</button>
                                    <button form="formEdit" type="submit" class="btn btn-warning" id="editGroup">Update</button>
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
<script>
    $('.removeMenuConfirmation').on('click', function(e){
        e.preventDefault();
        const url = $(this).attr('href');
        swal({
            title: 'Hapus Menu !',
            text: 'Apa anda yakin untuk menghapus menu ini !',
            icon: 'warning',
            buttons: ["Cancel", "Delete"],
        }).then(function(value){
            if(value){
                window.location.href = url;
            }
        })
    })
    $('.edit').on('click', function(){
        $('#namaEdit').val(`${$(this).data('nama')}`);
        $('#urutanEdit').val(`${$(this).data('urutan')}`);
        $('#iconEdit').val(`${$(this).data('icon')}`);
        $('#idMenu').val(`${$(this).data('id')}`);
    })
    $('#formEdit').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url:'{{ route("editMenu") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: (res) => {
                toastr['info']('Data menu telah diubah')
                window.location.reload()
            },
            error: (err) => {
                console.error(err)
            }
        })
    })
</script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
@endsection