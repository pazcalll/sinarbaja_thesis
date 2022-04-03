<div class="container nowrap" style="width: 100%; right: 0px; background-color: white;" id="main-content">
    <h2>Items Master</h2>
    <a href="javascript:void(0)" onclick="$('#modalUploadExcel').modal('show')" class="card-body-title"><button class="btn btn-success"><i class="icon md-upload"></i> Import Excel</button></a>
    <a href="{{route('export_excel_item')}}" class="card-body-title"><button class="btn btn-warning"><i class="icon md-download"></i> Export Excel</button></a>
    <table id="table_item" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th style="width:5%">No #</th>
                <th style="width:20%">Type</th>
                <th style="width:10%">Real Name</th>
                <th style="width:10%">Code</th>
                <th style="width:20%">Name</th>
                <th style="width:10%">Unit</th>
                <th style="width:5%">Stock</th>
                <th style="width:10%">Prices</th>
                <th style="width:10%">Action</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
</div>
@include('adminThesis.excelModal')

<script>
    $(document).ready(function(){
        table = $('#table_item').DataTable({
            "processing" : true,
            "searching" : false,
            "ajax" : {
                "url" : "{{ route('data_barang') }}",
                "type" : "GET"
            }
        });
    })
</script>