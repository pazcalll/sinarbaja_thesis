@extends('app')

@section('page')
    <div class="page-content">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-15">
                    <h3>Harga {{$productName}} Per User</h3>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="container">
                    <div class="row row-lg">
                        <div class="col-md-12">
                            <div class="example-wrap">
                                <div class="example">
                                    <table class="table table-bordered table-hover table-striped" id="tableUserHarga">
                                        <thead>
                                            <tr>
                                                <th style="width: 10%;">No</th>
                                                <th style="width: 30%;">User</th>
                                                <th style="width: 30%;">Group User</th>
                                                <th style="width: 30%;">Harga User</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('js')
    <script src="{{ asset('public/themeforest/page-base/examples/js/forms/validation.js') }}"></script>
    <script src="{{ asset('public/themeforest/page-base/examples/js/forms/validation.js') }}"></script>
    <script src="{{ asset('public/themeforest/page-base//examples/js/forms/uploads.js') }}"></script>
    <script>
        $(document).ready(function() {
            loadHargaUserList('{{$productId}}');
        })
        function userPriceUpdater(data) {
            $.ajax({
                url: '{{ route("changeUserPrice") }}',
                type: 'POST',
                data: data,
                success: (res) => {
                    $('#tableUserHarga tbody').empty()
                    console.log(res)
                    loadHargaUserList('{{$productId}}');
                    toastr['info']('Harga User Telah di Ubah')
                }
            })
        }
        function loadHargaUserList($productId){
            $('#tableUserHarga tbody').empty()
            $.ajax({
                url:`{{ url('data/user/harga/byProduk') }}/${$productId}`,
                type: 'GET',
                async: false,
                success: (res) => {
                    let groupPrice = null
                    $.ajax({
                        url: `{{ url('data/user/harga/byProduk/groupPriceSelection') }}/${$productId}`,
                        type: 'GET',
                        // data: $productId,
                        async: false,
                        success: (res) => {
                            // console.log(res)
                            groupPrice = res
                        }
                    })
                    let contentHargaUser = ''
                    console.log(res)
                    res.forEach((item, _index) => {
                        contentHargaUser += `
                        <tr>
                            <td> ${_index + 1} </td>
                            <td> ${item.user.name} </td>
                            <td> ${item.group.group_name} </td>
                            <td>
                                Rp. ${item.harga_user}
                                <form id="form${item.id}" method="GET" action="#">
                                    @csrf
                                    <select class="form-control groupHargaSelect" name="groupHargaSelect">
                                        `
                            groupPrice.forEach(price => {
                                if (item.harga_user == price.harga_group) {
                                    contentHargaUser += `
                                        <option value="${price.id}" selected >${price.harga_group} -> ${price.group.group_name} (Saat Ini)</option>
                                        `
                                }else
                                contentHargaUser += `
                                        <option value="${price.id}">${price.harga_group} -> ${price.group.group_name}</option>
                                        `
                            });
                            contentHargaUser += `
                                    </select>
                                    <input type="hidden" name="id" value="${item.id}">
                                    <input type="hidden" name="idProduct" value="${item.id_product}">
                                    <input type="hidden" name="idUser" value="${item.user.id}">
                                    <button form="form${item.id}" type="submit" class="btn btn-primary">
                                        Setel Ulang
                                    </button>
                                </form>
                            </td>
                        </tr>`
                        $('#tableUserHarga tbody').append(contentHargaUser)
                        contentHargaUser = ''

                        $(`#form${item.id}`).unbind()
                        $(`#form${item.id}`).on('submit', function(e) {
                            e.preventDefault()
                            // console.log($(this).serialize())
                            userPriceUpdater($(this).serialize())
                        })

                    });
                }
            })
        }
    </script>
@endsection
