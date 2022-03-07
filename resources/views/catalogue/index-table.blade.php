@php
$showNavigation = false;
$bodyType = 'site-menubar-unfold';
@endphp

@extends('app')

@section('page')
<div class="page-content">
    <div class="py-15">
        <div class="text-center">
            <div class="btn-group" aria-label="Basic example" role="group">
                <a type="button" class="btn btn-icon btn-light waves-effect waves-classic" href="{{ url('/') }}" style="color: #3f51b5"><i class="icon md-collection-image" aria-hidden="true"></i></a>
                <a type="button" class="btn btn-icon btn-primary waves-effect waves-classic" style="color: #fff"><i class="icon md-view-list" aria-hidden="true"></i></a>
            </div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-body">
            <div class="table-responsive">
                <table class="table dataTable">
                    <thead>
                        <tr style="text-align: center">
                            <th style="width: 30%;">Aksi</th>
                            <th style="width: 30%;">Nama Produk</th>
                            <th style="width: 30%;">Kategori</th>
                            <th style="width: 10%;">Harga</th>
                        </tr>
                    </thead>
                    <tbody id="table-body">

                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="py-15">
        <div class="text-right">
            <ul class="pagination" role="navigation" id="pagination">

            </ul>
        </div>
    </div>
</div>
@endsection

@section('js')
<!-- Page -->
<script src="{{ asset('public/themeforest/global/js/Plugin/datatables.js') }}"></script>
<script src="{{ asset('public/themeforest/page-base/examples/js/tables/datatable.js') }}"></script>
<script src="{{ asset('public/themeforest/page-base/examples/js/uikit/icon.js') }}"></script>

<script>
    $(document).ready(function() {
        load()
    });

    function load(url = `{{ url('/data/catalogue/products') }}`) {
        $.getJSON({
            url: url,
            type: 'GET',
            async: true
        }).then((res) => {
            const data = res.data.data

            $('#table-body').empty()
            data.forEach((product, index) => {
                const template =
                    `
                        <tr>
                            <td width="25%">
                                <div class="card-block text-center div_card_beli my-cart-btn" style=" padding-top: 5px;">
                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected" style="width: 180px;">
                                        <input type="text" class="form-control qty" style="text-center" name="touchSpinPrefix"
                                            data-plugin="TouchSpin" data-min="1" data-max="1000000000" data-stepinterval="50" data-maxboostedstep="10000000" value="1" />
                                        <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">
                                            <span class="input-group-text">Stok <b id="data-stock"></b></span>
                                        </span>
                                        @if (Auth::check())
                                        <button type="button" class="btn btn-md btn-round add-to-cart"
                                            style="background: #fb8b34; color: white; margin-left: 10px; font-weight: bold" data-id="${ index }">
                                            <a class="icon md-shopping-cart" aria-hidden="true"></a>
                                        </button>
                                        <!-- <button type="button" class="btn btn-md btn-round buyitem"
                                            style="background: #3f51b5; color: white; margin-left: 10px; font-weight: bold" data-target="#purchaseOrder"
                                            data-toggle="modal" data-id="${ index }">
                                            Beli
                                        </button> -->
                                        @else
                                            <a href="{{ url('/login') }}" class="btn btn-md btn-round"
                                                style="background: #fb8b34; color: white; margin-left: 10px; font-weight: bold">
                                                <i class="icon md-shopping-cart" aria-hidden="true"></i>
                                            </a>
                                            <!-- <a href="{{ url('/login') }}" class="btn btn-md btn-round"
                                                style="background: #3f51b5; color: white; margin-left: 10px; font-weight: bold">
                                                Beli
                                            </a> -->
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>${ product.nama }</td>
                            <td>${ product.category.name }</td>
                            <td>Rp ${ product.harga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") }</td>
                        </tr>
                        `

                $('#table-body').append(template);
            })

            let pagination = ''
            if (res.data.current_page == 1) {
                pagination += `<li class="page-item" aria-current="page"><span class="page-link">‹</span></li>`
            } else {
                pagination += `<li class="page-item"><button class="page-link" onclick="load('${ res.data.prev_page_url }">‹</button></li>`
            }

            for (var i = 1; i <= res.data.last_page; i++) {
                if (i == res.data.current_page) {
                    pagination += `<li class="page-item active" aria-current="page"><span class="page-link">${ i }</span></li>`
                } else {
                    pagination += `<li class="page-item"><button class="page-link" onclick="load('${ res.data.path }?page=${ i }')">${ i }</button></li>`
                }
            }

            if (res.data.current_page == res.data.last_page) {
                pagination += `<li class="page-item" aria-current="page"><span class="page-link">›</span></li>`
            } else {
                pagination += `<li class="page-item"><button class="page-link" onclick="load('${ res.data.next_page_url }')">›</button></li>`
            }


            $('#pagination').empty()
            $('#pagination').append(pagination)

            $('.buyitem').click(function() {
                const $parent = $($(this).parent())
                qty = $($parent.children()[0]).val()

                const product = data[$(this).data('id')]
                var template = `
                    <tr style="text-align: center">
                        <td>1.</td>
                        <td><a class="waves-effect waves-light waves-round" style="color: blue">${ product.nama }</a></td>
                        <td><input type="text" class="form-control qty" style="text-center" id="catatan" value=""/></td>
                        <td>Rp ${ product.harga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") }</td>
                        <td>${ qty }</td>
                        <td>Rp ${ qty * product.harga} </td>
                    </tr>`

                let total = qty * product.harga

                $('.pembelian-content').empty()
                $('.pembelian-content').append(template)
                $('#data-harga').html(`Rp ${ total.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") }`)
                $('#btn-buy-item').data('id', $(this).data('id'))
                $('#btn-buy-item').click(function() {
                    setTimeout(function() {
                        $('#purchaseOrder').modal('hide');
                    }, 2000);
                })
                $('#btn-buy-item').click(function() {
                    var arr = []
                    var index = $(this).data('id')
                    var param = data[index]
                    param.qty = qty

                    arr.push(param)
                    buy(arr, total)
                    window.location.reload(true)
                })
            })

            $('.add-to-cart').click(function() {
                const $parent = $($(this).parent())
                qty = $($parent.children()[0]).val()
                product = data[$(this).data('id')]

                addToChart(product, qty)
            });
        });
    }
</script>
@endsection