@extends('app')

@section('page')

    <div class="page-content">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-15">
                    <h3>Harga {{$data['name']}} Per Group</h3>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="btn-group">
                    <button class="btn btn-primary addHargaGroup" id="btn-add"
                        data-id="{{$data['id']}}" data-product-name="{{$data['name']}}"
                        >
                        + Tambah Harga Per Group
                    </button>
                    {{-- <a class="btn btn-info" href="{{ url("dashboard/produk/prices/user-prices") }}/{{$data['id']}}/{{$data['name']}}" style="color: white;">
                        Setel Harga Per User
                    </a> --}}
                </div>
                <br>
                <form role="form" id="formHargaBarang" style="display: none" enctype="multipart/form-data" action="#">
                    @csrf
                    <div class="row>
                        <div class="form-group">
                            <label for="exampleInputEmail1">Group</label>
                            <select class="form-control" id="groupUserSelector" name="groupUserSelector">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Harga</label>
                            <input name="harga" type="number" class="form-control" id="exampleInputPassword1" placeholder="Harga">
                        </div>
                        <button form="formHargaBarang" type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
                <div class="container">
                    <div class="row row-lg">
                        <div class="col-md-12">
                            <!-- Example Basic Form (Form grid) -->
                            <div class="example-wrap">
                                <div class="example">
                                    <table class="table table-bordered table-hover table-striped" id="tableHarga">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;">No</th>
                                                <th style="width: 25%;">Group User</th>
                                                <th style="width: 40%;">Harga</th>
                                                <th style="width: 25%;">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <!-- End Example Basic Form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--  Modal Tambah Harga Group -->
<div class="modal fade" id="modalEditHarga" aria-hidden="true" aria-labelledby="examplePositionCenter" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable" style="min-width: max-content">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title title-edit-harga-product"></h4>
            </div>

            <div class="modal-body">
                <div class="hargaEdit">
                    
                </div>
            </div>
            <!-- <div class="modal-footer">
                <button type="button" class="btn btn-default btn-pure" data-dismiss="modal">Close</button>
            </div> -->
        </div>
    </div>
</div>
<!-- End Modal -->

<!--  Modal Konfirmasi Delete -->
<div class="modal fade" id="modalDeleteHarga" aria-hidden="true" aria-labelledby="examplePositionCenter" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable" style="min-width: max-content">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Konfirmasi Delete</h4>
            </div>

            <div class="modal-body">
                <div class="textHargaDelete">
                    
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-dismiss="modal">Tidak</button>
                <form id="deleteHargaForm" action="" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="hargaDelete"></div>
                    <button form="deleteHargaForm" type="submit" class="btn btn-success">Iya</button>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- End Modal -->
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            loadHargaList('{{ $data["id"] }}')
            $('#btn-add').click(function() {
                $('#formHargaBarang').toggle(500);
            });
            
            $('#formHargaBarang').on('submit', function(e) {
                e.preventDefault()
                $.ajax({
                    url: `{{ route('storeGroupHarga') }}`,
                    type: 'POST',
                    data: $(this).serialize(),
                    success: (res) => {
                        toastr['info']('Harga untuk group telah ditambahkan')
                        $.ajax({
                            url: `{{ route('storeUserHarga') }}`,
                            type: 'POST',
                            data: $(this).serialize(),
                            success: (res) => {
                                console.log('harga User Update')
                            } 
                        })
                        loadHargaList('{{$data["id"]}}')
                    }
                })
            })
        })

        function loadHargaList($localDataId){
            $('.deleteGroupHarga').unbind()
            $('#tableHarga tbody').empty()
            $.ajax({
                url:`{{ url("dashboard/harga") }}/${$localDataId}`,
                type: 'GET',
                async: false,
                success: (res) => {
                    let contenHargaGroup = ''
                    res.forEach((item, _index) => {
                        contenHargaGroup += `
                            <tr>
                                <td> ${_index + 1} </td>
                                <td> ${item.group.group_name} </td>
                                <td> ${item.harga_group} </td>
                                <td align="center">
                                    <span class="row">
                                        <button aria-hidden="true" class="btn btn-sm btn-icon btn-pure btn-default on-default editGroupHarga"
                                            data-target="#modalEditHarga" data-toggle="modal" data-id=${item.id} data-item-name="${item.group.group_name}"
                                            data-group-name=${item.group.group_name} data-harga="${item.harga_group}" type="button">
                                            <span class="badge badge-warning">
                                                <i class="icon md-edit" aria-hidden="true"></i>
                                                Edit
                                            </span>
                                        </button>
                                        <button form="deleteGroupHarga" aria-hidden="true" class="btn btn-sm btn-icon btn-pure btn-default on-default deleteGroupHarga"
                                        data-target="#modalDeleteHarga" data-toggle="modal" data-id-delete="${item.id}" data-id-product="${item.id_product}" 
                                        data-id-group="${item.id_group}" data-group-name=${item.group.group_name} data-harga="${item.harga_group}" type="submit">
                                            <span class="badge badge-danger">
                                                <i class="icon md-delete" aria-hidden="true"></i>
                                                Delete
                                            </span>
                                        </button>
                                    </span>
                                </td>
                            </tr>
                        `
                    });
                    $('#tableHarga tbody').append(contenHargaGroup)
                    contenHargaGroup = ''

                    let selectContent = null
                    $('.addHargaGroup').on('click', function() {
                        if (selectContent == null) {
                            let dataId = $(this).data('id')
                            let dataProductName = $(this).data('product-name')
                            $('.title-harga-product').html(dataProductName)
                            $.ajax({
                                url: '{{ route("allGroup") }}',
                                data: {id:'{{$data["id"]}}'},
                                type: 'GET',
                                success: (res) => {
                                    selectContent = res
                                    console.log("op", res)
                                    let options = ''
                                    $('#groupUserSelector').empty()
                                    res.forEach(group => {
                                        options += `
                                            <option value="${group.id}">${group.group_name}</option>
                                        `
                                    });
                                    let inp = `<input type="hidden" name="dataId" value="${dataId}">`
                                    $('#groupUserSelector').append(options)
                                    $('#groupUserSelector').append(inp)
                                },
                                error: (err) => {
                                    console.log('err ',err)
                                }
                            })
                        }
                    })
                },
                error: (err) => {
                    console.log(err)
                }
            })
            $('.deleteGroupHarga').on('click', function() {
                // $('#tableHarga tbody').empty()
                $('#deleteHargaForm').unbind()
                $('#modalHarga').modal('hide')
                $('.hargaDelete').html(`
                    <input type="hidden" name="hargaDelete" value="${$(this).data('id-delete')}">
                    <input type="hidden" name="idProduct" value="${$(this).data('id-product')}">
                    <input type="hidden" name="idGroup" value="${$(this).data('id-group')}">
                `)
                $('.textHargaDelete').html(`Apakah anda yakin menghapus harga grup <strong> ${$(this).data('group-name')} </strong> yang bernilai <strong>Rp. ${$(this).data('harga')}</strong>`);
                $('#deleteHargaForm').on('submit', function(e) {
                    e.preventDefault();
                    $.ajax({
                        url: `{{ route('deleteHarga') }}`,
                        type: 'POST',
                        // processData: false,
                        // contentType: false,
                        // data: new FormData(this),
                        data: $(this).serialize(),
                        success: (successRes) => {
                            toastr['info']('Group Harga Barang Telah Dihapus')
                            // console.log(successRes)
                            $.ajax({
                                url: '{{ route("deleteAllUserHarga") }}',
                                type: 'POST',
                                data: $(this).serialize(),
                                success: (res) => {
                                    // console.log(res)
                                }
                            })
                            loadHargaList('{{$data["id"]}}')
                            $('#modalDeleteHarga').modal('hide')
                        },
                        error: (err) => {
                            console.log(err)
                        }
                    })
                })
            })

            $('.editGroupHarga').on('click', function () {
                $('#modalHarga').modal('hide')
                $('.title-edit-harga-product').html(''+$(this).data('item-name'))
                let tmpltForm = ''
                let dataId = $(this).data('id')
                tmpltForm = `
                    <form id="formEditHargaGroup" action='#' >
                        @csrf
                        <div class="form-group">
                            <label for="exampleInputEmail1">Group</label>
                            <select disabled class="form-control" id="groupUserSelector" name="groupUserSelector">
                                <option value="${$(this).data('group-name')}">${$(this).data('group-name')}</option>
                            </select>
                            <input type="hidden" name="groupHarga" value="${$(this).data('group-name')}">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Harga</label>
                            <input name="harga" type="number" value="${$(this).data('harga')}" class="form-control" id="exampleInputPassword1" placeholder="Harga">
                        </div>
                        <button form="formEditHargaGroup" type="submit" class="btn btn-primary">Edit Harga</button>
                    </form>
                `
                $('.hargaEdit').html(tmpltForm)
                $('#formEditHargaGroup').on('submit', function(e) {
                    e.preventDefault()
                    $.ajax({
                        url: `{{ url("dashboard/harga") }}/${dataId}`,
                        type: 'POST',
                        data: $(this).serialize(),
                        success: (res) => {
                            toastr['info']('Harga barang berhasil diubah')
                            $('#modalEditHarga').modal('hide')
                            $('#modalHarga').modal('show')
                            loadHargaList($localDataId)
                        }
                    })
                })
            })
        }
    </script>
@endsection
