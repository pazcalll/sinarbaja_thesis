<div class="container nowrap" style="width: 100%; right: 0px; background-color: white;" id="main-content">
    <h2>Stock Master</h2>
    <a href="javascript:void(0)" onclick="$('#modalUploadExcel').modal('show')" class="card-body-title"><button class="btn btn-success"><i class="icon md-upload"></i> Upload Stock</button></a>
    <a href="javascript:void(0)" class="card-body-title"><button class="btn btn-warning"><i class="icon md-download"></i> Download Stock</button></a>
    <table id="table_stock" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="width:5%">No.</th>
                <th style="width:20%">Code</th>
                <th style="width:40%">Name</th>
                <th style="width:15%">Stock</th>
                <th style="width:20%">Unit</th>
                {{-- <th style="width:10%">Action</th> --}}
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@include('adminThesis.modal.modalExcelBarang')

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
            if (myfile.length > 0){
                $.ajax({
                    url: '{{ route("import_stock") }}',
                    // headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: (res) => {
                        $('#table_item').DataTable().destroy()
                        $('#table_item').empty()
                        $('#table_item').DataTable({
                            "processing" : true,
                            "ajax" : {
                                "url" : "{{ route('all_item_stock') }}",
                                "type" : "GET"
                            }
                        });
                    },
                    error: (err) => {
                        console.error(err)
                    }
                })
            }
        })
    })
</script>