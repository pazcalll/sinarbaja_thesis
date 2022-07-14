<div class="container nowrap" style="width: 100%; right: 0px; background-color: white;" id="main-content">
    <h2>Daftar Pesanan Masuk</h2>
    {{-- <a href="javascript:void(0)" onclick="$('#modalUploadExcel').modal('show')" class="card-body-title"><button class="btn btn-success"><i class="icon md-upload"></i> Upload Stock</button></a>
    <a href="{{route('export_stock')}}" class="card-body-title"><button class="btn btn-warning"><i class="icon md-download"></i> Download Stock</button></a>
    <a href="javascript:void(0)" onclick="$('#modalTruncateStock').modal('show')" class="card-body-title"><button class="btn btn-danger"><i class="icon md-delete"></i> Empty Stock</button></a> --}}
    <table class="table table-bordered table-hover table-striped" id="tbl_po">
        <thead id="thead">
            <tr>
                <th width="5%">No.</th>
                <th>No. Nota</th>
                <th width="10%">Tipe Pengguna</th>
                <th >Pembeli</th>
                <th>Tanggal Pesan</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order as $item)
                <tr style="cursor: pointer;" 
                    class="po-row" 
                    data-nota="{{$item->no_nota}}" 
                    data-url="{{url('dashboard/incoming_order')}}/{{str_replace('=','%20',$item->no_nota)}}"
                    data-cust="{{$item->name}}"
                >
                    <td>{{$loop->iteration}}</td>
                    <td>{{$item->no_nota}}</td>
                    <td>{{$item->group_name}}</td>
                    <td>{{$item->name}}</td>
                    <td>{{$item->created_at}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@include('adminThesis.modal.modalPo')

<script>
    $(document).ready(function() {
        $('.po-row').on('click', function() {
            $('#modal-po').modal('show')
            console.log($(this).data('url'))

            $('#listHargaTable').DataTable().destroy()
            $('#listHargaTable tbody').empty()
            $('#listHargaTable').DataTable({
                'ajax' : {
                    'processing' : true,
                    'serverSide' : true,
                    'cache' : true,
                    'type' : 'GET',
                    'url' : `${$(this).data('url')}`
                }
            })
            $('#cust-name').html($(this).data('cust'))
            $('#no-nota').html($(this).data('nota'))
            $("#accOrder").data("prop", $(this).data('nota'))
        })
        $('#tbl_po').DataTable();
    })
    $("#accOrder").on('click', function() {
        $.ajax({
            url: '{{route("acc_order")}}',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            type: 'POST',
            data: {
                prop: $(this).data('prop')
            },
            success: (res) => {
                $('#modal-po').modal('hide')
                $('#modal-po').on('hidden.bs.modal', function () {
                    $('.order-list').click()
                })
            }
        })
    })
</script>