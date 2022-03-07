@extends('template.pages.datatable', [
'page' => 'Group User',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Account', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Group User', 'link' => '', 'active' => 'active']
]
])


@section('top-panel')
<div class="row">
    <div class="col-md-6">
        <div class="mb-15">
            <button id="addGroup" class="btn btn-primary" type="button" data-toggle="modal" data-target="#modalGroup">
                <i class="icon md-plus" aria-hidden="true"></i> Add Group
            </button>
        </div>
    </div>
</div>
@endsection

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
        <thead>
            <tr align="center">
                <th width="1%">No.</th>
                <th>Nama Group</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach (App\GroupUser::all()->whereNotIn('id', 1) as $i => $item)
                <tr id="group_id{{$item->id}}">
                    <td>{{$no++}}</td>
                    <td>{{$item->group_name}}</td>
                    <td align="center">
                        <a class="edit" data-original-title="Edit" data-id="<?php echo $item->id; ?>" data-group="<?php echo $item->group_name; ?>" style="margin-right: 20px; cursor: pointer;" data-toggle="modal" data-target="#modalEdit">
                            <i class="icon md-edit" aria-hidden="true"></i>
                        </a>
                        <a href="{{ url('data')}}/delete/group/{{ $item->id }}" class="button removeGroupConfirmation">
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
<div class="modal fade example-modal-lg modal-3d-sign"  data-toggle="modal" id="modalGroup" aria-hidden="true" aria-labelledby="modalGroup" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-lg-4">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="titleGroup" style="color: blue;">Tambah Group</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Group User</h4>
                    <div class="example">
                        <div class="modal-body">
                            <form id="formGroup" name="formGroup" class="form-horizontal" method="post" novalidate="" action="{{route('createGroup') }}">
                                @csrf
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><b>Nama Group</b></label>
                                    <button class="add_field_button" style="margin-left: 210pt">Add</button>
                                    <input type="text" class="form-control" id="group_name" name="group_name[]" placeholder="Masukan Group User Baru" value="">
                                    <br>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-toggle="modal" id="back1" data-dismiss="modal">Back</button>
                                    <button type="submit" class="btn btn-warning">Submit</button>
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
                <h4 class="modal-title" id="titleGroup" style="color: blue;">Edit Group</h4>
            </div>
            <div class="modal-body">
                <div class="example-wrap">
                    <br>
                    <h4 class="example-title text-center">Group User</h4>
                    <div class="example">
                        <div class="modal-body">
                            <form id="formEdit" name="formEdit" class="form-horizontal" novalidate="" method="post" action="#">
                                @csrf
                                <div class="form-group">
                                    <label class="col-sm-4 control-label"><b>Nama Group</b></label>
                                    <input type="text" class="form-control" id="nameEdit" name="nameEdit" placeholder="Masukan Group User Baru" value="<?php echo $item->group_name ?>">
                                    <input type="hidden" class="form-control" name="idGroup" id="idGroup" value=""/>
                                    <br>
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
    $(document).ready(function() {
        var max_fields = 5;
        var wrapper = $(".form-group");
        var add_button = $(".add_field_button");

        var x = 1;
        $(add_button).click(function(e) {
            e.preventDefault();
                if(x < max_fields) {
                    x++;
                    $(wrapper).append('<div class="form-group"><input type="text" class="form-control" id="group_name" name="group_name[]" placeholder="Masukan Group User Baru" value=""><div class="input-group-append"><a class="button remove_field"><i class="icon md-delete aria-hidden="true"></div></div>');
                }
        });
        $(wrapper).on("click", ".remove_field", function(e){
            e.preventDefault();
            $(this).parent('div').parent('div').remove(); 
            x--;
        })
    });

    $('.removeGroupConfirmation').on('click', function(e){
        e.preventDefault();
        const url = $(this).attr('href');

        swal({
            title: 'Hapus Group !',
            text: 'Apa anda yakin untuk menghapus group ini !',
            icon: 'warning',
            buttons: ["Cancel", "Delete"],
        }).then(function(value){
            if(value){
                window.location.href = url;
            }
        })
    })
    $('.edit').on('click', function() {
        $('#nameEdit').val(`${$(this).data('group')}`);
        $('#idGroup').val(`${$(this).data('id')}`);
    })
    
    $('#formEdit').on('submit', function(e){
        e.preventDefault();

        $.ajax({
            url:'{{ route("editGroup") }}',
            type: 'POST',
            data: $(this).serialize(),
            success: (res) => {
                toastr['info']('Data group telah diubah')
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
