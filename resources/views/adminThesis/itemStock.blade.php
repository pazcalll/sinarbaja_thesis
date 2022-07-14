<div class="container nowrap" style="width: 100%; right: 0px; background-color: white;" id="main-content">
    <h2>Stok Barang</h2>
    <a href="javascript:void(0)" onclick="$('#modalUploadExcel').modal('show')" class="card-body-title"><button class="btn btn-success"><i class="icon md-upload"></i> Unggah Stok Barang</button></a>
    <a href="{{route('export_stock')}}" class="card-body-title"><button class="btn btn-warning"><i class="icon md-download"></i> Unduh Stok Barang</button></a>
    <a href="javascript:void(0)" onclick="$('#modalTruncateStock').modal('show')" class="card-body-title"><button class="btn btn-danger"><i class="icon md-delete"></i> Kosongkan Stok Barang</button></a>
    <table id="table_stock" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="width:5%">No.</th>
                <th style="width:20%">Kode Barang</th>
                <th style="width:40%">Nama Barang</th>
                <th style="width:15%">Stok</th>
                <th style="width:20%">Satuan</th>
                {{-- <th style="width:10%">Action</th> --}}
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@include('adminThesis.modal.modalExcelBarang')
@include('adminThesis.modal.modalEmptyStock')

<script>
    $(document).ready(function(){
        table = $('#table_stock').DataTable({
            "processing" : true,
            "ajax" : {
                "url" : "{{ route('all_item_stock') }}",
                "type" : "GET"
            }
        });
        $('#uploadExcel').on('submit', function(e) {
            e.preventDefault()
            let fd = new FormData(this);
            let myfile = $('#file_excel')[0].files;
            $('#modalUploadExcel .modal-dialog .modal-content .modal-body').html('Loading, Please Wait...')
            $('#modalUploadExcel .modal-dialog .modal-content .modal-footer').html('')

            if (myfile.length > 0){
                $.ajax({
                    url: '{{ route("import_stock") }}',
                    // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: (res) => {
                        $('#modalUploadExcel').modal('hide')
                        $('#modalUploadExcel').on('hidden.bs.modal', function () {
                            $('.item-stock').click()
                        })
                    },
                    error: (err) => {
                        console.error(err)
                    }
                })
            }
        })
        $('#truncateStock').on('submit', function(e) {
            e.preventDefault()
            $('#modalTruncateStock .modal-dialog .modal-content .modal-body').html('Loading, Please Wait...')
            $('#modalTruncateStock .modal-dialog .modal-content .modal-footer').html('')
            $.ajax({
                url: '{{ route("truncate_stock") }}',
                type: 'POST',
                success: (res) => {
                    $('#modalTruncateStock').modal('hide')
                    $('#modalTruncateStock').on('hidden.bs.modal', function () {
                        $('.item-stock').click()
                    })
                },
                error: (err) => {
                    console.error(err)
                }
            })
        })
    })
</script>