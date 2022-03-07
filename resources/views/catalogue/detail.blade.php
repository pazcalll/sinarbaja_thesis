@php
$showNavigation = false;
$bodyType = 'site-menubar-unfold';
@endphp

@extends('app')

@section('page')
<div class="panel">
    <div class="card card-shadow">
        <div class="card-block">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="example-wrap m-lg-0">
                            <div class="example">
                                <input type="hidden" name="id_product" id="id_product" value="{{ $id }}">
                                <div class="form-group">
                                    <div class="carousel slide" id="exampleCarouselDefault" data-ride="carousel">
                                        <ol class="carousel-indicators carousel-indicators-fall">
                                            <li class="active" data-slide-to="0" data-target="#exampleCarouselDefault">
                                            </li>
                                            <li data-slide-to="1" data-target="#exampleCarouselDefault"></li>
                                            <li data-slide-to="2" data-target="#exampleCarouselDefault"></li>
                                        </ol>
                                        <div class="carousel-inner" role="listbox">

                                        </div>
                                        <a class="carousel-control-prev" href="#exampleCarouselDefault" role="button" data-slide="prev">
                                            <span class="carousel-control-prev-icon md-chevron-left" aria-hidden="true"></span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="carousel-control-next" href="#exampleCarouselDefault" role="button" data-slide="next">
                                            <span class="carousel-control-next-icon md-chevron-right" aria-hidden="true"></span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="example-wrap">
                            <div class="example">
                                <div class="col-lg-12">
                                    <h3><b id="data-nama"></b></h3>
                                </div>
                                <div class="col-lg-12">
                                    <h4 style="color: #fb8b34;">Rp <b id="data-harga-atas"></b></h4>
                                </div>
                                <br>
                                <div class="col-lg-4">
                                    <div class="class-stok mb-2">
                                        <a class="title" aria-expanded="true">
                                            Stok
                                        </a>
                                    </div>
                                    <div class="input-group bootstrap-touchspin bootstrap-touchspin-injected">
                                        <input style="text-align: center;" type="text" class="form-control" id="qty" data-plugin="TouchSpin" data-min="1" data-max="1000000000" data-stepinterval="50" data-maxboostedstep="10000000" value="1" />
                                    </div>
                                </div>
                                <div class="col-lg-12">
                                    <div class="panel-group panel-group-simple panel-group-continuous" id="accordion2" aria-multiselectable="true" role="tablist">
                                        <!-- Question 1 -->
                                        <div class="panel">
                                            <div class="panel-heading" id="question-1" role="tab">
                                                <a class="panel-title" aria-controls="answer-1" aria-expanded="true" data-toggle="collapse" href="#answer-1" data-parent="#accordion2">
                                                    Tambah Catatan
                                                </a>
                                            </div>
                                            <div class="panel-collapse collapse" id="answer-1" aria-labelledby="question-1" role="tabpanel">
                                                <div class="panel-body">
                                                    <input type="text" class="form-control" id="catatan" placeholder="Contoh : Warna Putih">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <div class="total-panel">
                                        <h4><b>Subtotal</b></h4>
                                        <div class="total-harga">
                                            <a class="total">
                                                <h4 style="color: #fb8b34;">Rp <b id="data-harga">0</b></h4>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <br><br>
                                <!-- <div class="col-lg-12">
                                    <div class="form-group form-material d-grid gap-2 d-md-flex justify-content-md-end">
                                        @if (Auth::check())
                                        <button id="add-to-cart" type="button" class="btn btn-lg" style="background: #fb8b34; color: #fff; margin-right: 20px">
                                            Masukkan Keranjang
                                        </button>
                                        <button id="buy-item" type="button" class="btn btn-lg btn-primary" data-target="#purchaseOrder" data-toggle="modal">
                                            Beli Produk
                                        </button>
                                        @else
                                        <a href="{{ url('/login') }}" class="btn btn-lg" style="background: #fb8b34; color: #fff">
                                            Login
                                        </a>
                                        @endif
                                    </div>
                                </div> -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="title-deskripsi">
                            <h4><b>Deskripsi</b></h4>
                        </div>
                        <div class="content" style="text-align: justify">
                            <span class="content-deskripsi"><b id="diskripsi"></b></span>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="form-group form-material d-grid gap-2 d-md-flex justify-content-md-end">
                            @if (Auth::check())
                            <button id="add-to-cart" type="button" class="btn btn-md btn-round" style="background: #fb8b34; color: white; margin-left: 10px; margin-right: 20px">
                                Masukkan Keranjang
                            </button>
                            <button id="buy-item" type="button" class="btn btn-md btn-round btn-primary" data-target="#purchaseOrder" data-toggle="modal">
                                Beli Produk
                            </button>
                            @else
                            <a href="{{ url('/login') }}" class="btn btn-lg" style="background: #fb8b34; color: #fff">
                                Login
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>

    toastr.options = {
        positionClass: 'toast-bottom-right'
    }

    var id = $("#id_product").val();
    var json = $.getJSON({
        'url': "{{ url('detail') }}",
        'data': {
            id: id
        },
        'headers': {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        'async': false,
        'type': 'POST',
    })

    json = JSON.parse(json.responseText);
    let {
        data
    } = json
    $('#data-nama').append(data.data[0].nama)
    $('#qty').val(parseInt(data.data[0].qty))
    $('#catatan').text(data.data[0].catatan)

    let qtydefault = data.data[0].qty
    var catatan = $('#catatan').val()
    var param = data.data[0]
    param.catatan = catatan

    if (data.data[0].images.length > 0) {
        data.data[0].images.forEach((element, index) => {
            const gambar =
                `<div class="carousel-item ${ index == 0 ? 'active' : ''}" style="width:500px">
                        <img class="w-full" src="{{ asset('public/storage/photo') }}/${ element.path }"/>
                    </div>`;

            $('.carousel-inner').append(gambar);
        });
    }

    var qty = $('#qty').val()
    var newHargaProduk = []
    var param = data.data[0]
    param.qty = qty

    $('#diskripsi').text(data.data[0].deskripsi)
    $('#data-harga-atas').text(data.data[0].harga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."))
    $('#catatan').text(data.data[0].catatan)

    let totalBuyItem = 0
    $('#buy-item').click(function() {
        param.qty = $('#qty').val()
        param.catatan = $('#catatan').val()
        var template = `
                <tr style="text-align: center";>
                    <td>1.</td>
                    <td><a class="waves-effect waves-light waves-round" style="color: blue">${ param.nama }</a></td>
                    <td>${ $('#catatan').val() }</td>
                    <td>Rp ${ param.harga.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") }</td>
                    <td>${ $('#qty').val() }</td>
                    <td>Rp ${ $('#qty').val() * param.harga } </td>
                </tr>`

        totalBuyItem = $('#qty').val() * param.harga

        $('.pembelian-content').empty()
        $('.pembelian-content').append(template)
        $('#data-harga').html(`Rp ${ totalBuyItem.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1.") }`)
        $('#btn-buy-item').data('id', $(this).data('id'))
        // $('#btn-buy-item').click(function() {
        //     let arr = []
        //     arr.push(param)
        //     $('#buy-item').css('pointer-events', 'none')
        //     singleBuy(arr, totalBuyItem)
        //     $('#buy-item').css('pointer-events', 'auto')
        //     // window.location.reload(true)
        // })
    })
    $(document).on('click', '#btn-buy-item', function() {
        let arr = []
        arr.push(param)
        $('#buy-item').attr('disabled', 'disabled')
        setTimeout(() => {
            singleBuyDetail(arr, totalBuyItem, $('#buy-item'))
        }, 1000);
    })

    $(document).on('click', '#btn-buy', function() {
        let total = 0
        carts.forEach((product, _index) => total += parseInt(product.harga) * parseInt(product.qty))
        buy(carts, total)
        carts = []
    })

    let valueqty = $("input[id='qty']").TouchSpin({
        min: 0,
        max: 1000000000,
        stepinterval: 50,
        initval: qtydefault,
        maxboostedstep: 10000000,
    });

    valueqty.on('touchspin.on.startupspin', function(e) {
        qtydefault = $(this).val()
        updateharga(qtydefault)
    })

    valueqty.on('touchspin.on.startdownspin', function(e) {
        qtydefault = $(this).val()

        updateharga(qtydefault)
    })
    let defaultharga = data.data[0].harga

    function updateharga(jumlah) {
        let value_kali = (defaultharga) * (jumlah)
        $('b[id="data-harga"]').text(value_kali.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1."))
    }
    $('#add-to-cart').on('click', function() {
        addToChart(param, qtydefault)
    })
</script>
@endsection