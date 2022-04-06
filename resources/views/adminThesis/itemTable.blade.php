<div class="container nowrap" style="width: 100%; right: 0px; background-color: white;" id="main-content">
    <h2>Items Master</h2>
    <a href="javascript:void(0)" onclick="$('#modalUploadExcel').modal('show')" class="card-body-title"><button class="btn btn-success"><i class="icon md-upload"></i> Import Excel</button></a>
    <a href="{{route('export_excel_item')}}" class="card-body-title"><button class="btn btn-warning"><i class="icon md-download"></i> Export Excel</button></a>
    <table id="table_item" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="width:5%">No.</th>
                <th style="width:10%">Type</th>
                <th style="width:20%">Real Name</th>
                <th style="width:10%">Code</th>
                <th style="width:20%">Name</th>
                <th style="width:10%">Unit</th>
                <th style="width:5%">Stock</th>
                <th style="width:10%">Prices</th>
                {{-- <th style="width:10%">Action</th> --}}
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@include('adminThesis.modal.modalExcelBarang')
@include('adminThesis.modal.modalHargaBarang')

<script>
    $(document).ready(function(){
        table = $('#table_item').DataTable({
            "processing" : true,
            "ajax" : {
                "url" : "{{ route('data_barang') }}",
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
                    url: '{{ route("import_excel_item") }}',
                    // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: (res) => {
                        $('#modalUploadExcel').modal('hide')
                        $('#table_item').DataTable().destroy()
                        $('#table_item').empty()
                        $('#modalUploadExcel').on('hidden.bs.modal', function () {
                            $('.item-list').click()
                        })
                    },
                    error: (err) => {
                        console.error(err)
                    }
                })
            }
        })
    })
    function showListHarga(id) {
        $('#modal-list-harga-barang').modal('show')
        $('#listHargaTable').DataTable()
        $('#listHargaTable').DataTable().destroy()
        $('#listHargaTable tbody').empty()
        $.ajax({
            url: `{{url("dashboard/barang_list_harga/`+id+`")}}`,
            type: "GET",
            success: (res) => {
                res = JSON.parse(res)
                let tbodyContent = ''
                res.forEach((item, _index) => {
                    console.log("pp",item)
                    tbodyContent += `
                    <tr>
                        <td width="5%">
                            ${_index+1}
                        </td>
                        <td>
                            ${item.barang_nama}
                        </td>
                        <td>
                            ${'Rp '+item.detail_harga_barang_harga_jual.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.")}
                        </td>
                        <td>
                            ${item.group_name}
                        </td>
                    </tr>
                    `
                });
                $('#modal_list_title').text("List Harga")
                $('#listHargaTable tbody').html(tbodyContent)
                $('#listHargaTable').DataTable()
                $('#listHargaTable tbody .odd').css({"background-color": "#f9f9a9"})
                tbodyContent = ''
            },
            error: (err) => {
                console.log(err)
            }
        })
    }
</script>