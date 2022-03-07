@extends('template.pages.datatable', [
'page' => 'Manajemen Produk',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Produk', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Manajemen produk', 'link' => '', 'active' => 'active']
]
])

@section('table')
    <table class="table table-bordered table-hover table-striped table-product">
        <thead>
            <tr>
                <th style="width: 25%;">Nama</th>
                <th style="width: 25%;">Kategori</th>
                <th style="width: 12%;">Stok Saat ini</th>
                <th style="width: 9%;">Aksi</th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
@endsection

@section('modal')
<div class="modal fade" id="dataInStock" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" id="modal-title"></h4>
            </div>
            <div class="modal-body">
                <table class="table table-bordered table-hover table-striped table-in-out">
                    <thead>
                        <tr>
                            <th width="50%">Tanggal Update</th>
                            <th width="50%">Catatan Update</th>
                            <th width="50%">Stock Sebelum Di Update</th>
                            <th id="stock">Stok Masuk</th>
                            <th id="stock">Stok Saat Ini</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="updateStock" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title">Update Stock</h4>
            </div>
            <div class="modal-body">

                    <h4 class="example-title">Nama Produk</h4>
                    <input type="text" name="name_product" class="form-control" id="inputDisabled" value="NAMA PRODUCT" disabled="">

                    <h4 class="example-title">Catatan Update</h4>
                    <input type="text" name="noted" class="form-control" id="inputPlaceholder">

                    <h4 class="example-title">Jumlah Stok Saat Ini</h4>
                    <input type="text" name="current_stock" class="form-control" id="inputDisabled" value="NAMA PRODUCT" disabled>

                    <h4 class="example-title">Update Stock</h4>
                    <input type="number" name="stock_product" class="form-control" id="inputPlaceholder">

                    <h4 class="example-title">Total Stock</h4>
                    <input type="text" name="total_stock" class="form-control" id="inputDisabled" value="NAMA PRODUCT" disabled>

                    <button type="button" id="btn_update_stock" class="btn btn-primary btn-block btn-round waves-effect waves-classic" style="margin-top: 10px">
                        Simpan
                    </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.table-product').DataTable({
                processing: true,
                serverSide: true,
                bInfo: false,
                ajax: "{{ URL::current() }}/datatables",
                columns: [
                    {
                        data: 'nama'
                    },
                    {
                        data: 'category.name'
                    },
                    {
                        data: 'stock.stock'
                    },
                    {
                        data: 'id',
                        render: (data, type, row, meta) => {
                            return `
                                <button class="btn btn-sm btn-icon btn-pure btn-default on-default" type="button"
                                    data-toggle="modal" data-target="#updateStock" onclick="editStock('${ row.nama }', ${row.stock.id}, ${ row.stock.stock })">
                                    <i class="icon md-flare" aria-hidden="true"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-pure btn-default on-default" type="button"
                                    data-toggle="modal" data-target="#dataInStock" onclick="loadIn(${ data })">
                                    <i class="icon md-arrow-left-bottom" aria-hidden="true"></i>
                                </button>
                                <button class="btn btn-sm btn-icon btn-pure btn-default on-default" type="button"
                                    data-toggle="modal" data-target="#dataInStock" onclick="loadOut(${ data })">
                                    <i class="icon md-arrow-right-top" aria-hidden="true"></i>
                                </button>`
                        }
                    }
                ]
            }) // datatable product

            $('input[name=stock_product]').on('change', function() {
                console.log('oke')
                const current = $('input[name=current_stock]').val()
                $('input[name=total_stock]').val(parseInt($(this).val()) + parseInt(current))
            })
        }) // jquery

        function editStock(nama, stock_id, stock) {
            $('input[name=name_product]').val(nama)
            $('input[name=stock_product]').attr('placeholder', stock)
            $('input[name=current_stock]').val(stock)

            $('#btn_update_stock').on('click', function() {
                $.ajax({
                    url: `{{ URL::current() }}`,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        stock_id: stock_id,
                        type: 'IN',
                        note: $('input[name=noted]').val(),
                        before: stock,
                        current: $('input[name=total_stock]').val(),
                    },
                    success: (response) => {
                        toastr["success"](response.message)
                        window.location.reload()
                    }
                });
            })
        }

        function loadIn(id) {
            bind(id, 'in', 'Data Stok Tercatat Masuk ke Gudang')
        }

        function loadOut(id) {
            bind(id, 'out', 'Data Stok Tercatat Keluar dari Gudang')
        }

        function bind(id, type, text) {
            $('#modal-title').text(text)
            $('#stock').text(type == 'in' ? ' Masuk' : ' Keluar')

            if ($('.table-in-out') != null) {
                $('.table-in-out').DataTable().destroy()
            }

            $('.table-in-out').DataTable({
                processing: true,
                serverSide: true,
                bInfo: false,
                ajax: `{{ URL::current() }}/${ type }/${ id }/datatables`,
                columns: [
                    {
                        data: 'created_at'
                    },
                    {
                        data: 'note',
                    },
                    {
                        data: 'before'
                    },
                    {
                        data: 'current'
                    },
                    {
                        data: 'current',
                        render: (data, _type, row, meta) => {
                            return type == 'out' ? (row.current - row.before) * -1 : row.current - row.before;
                        }
                    },
                ]
            })
        }
    </script>
@endsection
