@php
$showNavigation = false;
$bodyType = 'site-menubar-unfold';
@endphp

@extends('app')

@section('css')
<style>
    @media only screen and (max-width: 1280px){
        #product-wrapper {
            /* column-count: 2; */
            margin: 0 auto;
            width: max-content;
        }
        #search-res {
            /* column-count: 2; */
            margin: 0 auto;
            width: max-content;
        }
        .card:nth-child(2n+1){
            clear: left;
        }
        .card{
            float: left;
            margin: 5px;
            margin-bottom: 10px;
            width: 350px;
        }
    }

    @media only screen and (min-width: 1281px) {
        #product-wrapper {
            margin: 0 auto;
            width: max-content;
        }
        #search-res {
            margin: 0 auto;
            width: max-content;
        }
        .card:nth-child(4n+1){
            clear: left;
        }
        .card{
            float: left;
            margin: 5px;
            margin-bottom: 10px;
            width: 280px;
            max-width: 401px;
        }
    }
    
    @media (min-width: 480px) {
        .card-columns{
            -webkit-column-gap: 1.429rem;
            column-gap: 1.429rem;
            orphans: 1;
            widows: 1;
        }
    }

    @media (max-width: 500px) {
        
        .card{
            float: left;
            margin: 5px;
            margin-bottom: 10px;
            width: 150px;
            max-width: 300px;
        }
        img{
            height: 130px;
        }
    }

    .loader-grill::before {
        background: #3f51b5
    }

    .loader-grill {
        background: #3f51b5
    }

    .loader-grill::after {
        background: #3f51b5
    }
</style>
@endsection

@section('page')
<div class="row">
    <div class="col-12">
        <div class="example-wrap">
            <div class="row">
                <div style="display: none; width: 80%; margin: 0 auto;" id="search-res">
                    <div class="panel" style="margin: 0 auto;">
                        <div class="panel-body">
                            <div id="loading-notification"></div>
                            <span class="btn-group">
                                <button type="button" class="btn btn-primary" onclick="search($('#input_search').val(), `sql`)">SQL</button>
                                <button type="button" class="btn btn-primary" onclick="search($('#input_search').val(), `rabin`)">Rabin</button>
                            </span>
                            <button class="btn btn-info" onclick="analyticsPage()">Analytics</button>
                            
                            <table id="search-table" style="margin: 0 auto; width: 100%;">
                                <thead style="border-bottom: 1px solid gray;">
                                    <tr style="height: 70px;">
                                        <th width="10%">No.</th>
                                        <th width="30%">Nama Barang</th>
                                        <th width="20%">Kategori Barang</th>
                                        <th width="10%">Stok</th>
                                        <th width="20%">Harga</th>
                                        <th width="10%"></th>
                                    </tr>
                                </thead>
                                <tbody style="height: 20px">
                                    
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="" id="product-wrapper">
                </div>
            </div>
            <div class="row" style="margin: 0 auto; width: fit-content; margin-top: 20px;">
                <div id="loader" class="text-center" style="margin-top: 25px; margin-bottom: 25px">
                    <div class="loader vertical-align-middle loader-grill"></div>
                </div>
            </div>
            <div class="row" style="margin: 0 auto; width: fit-content;">
                <div class="text-center">
                    <div class="btn-group" aria-label="Basic example" role="group">
                        <button type="button" id="load-more" class="btn btn-icon btn-primary waves-effect waves-classic" style="color: #fff">
                            <i class="icon md-refresh" aria-hidden="true"></i>
                            Load More
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('js')
<script>
    var path = ''
    var currentPage = 1
    let hargaPerGroup = null
    let currentAuth = null
    var newHargaProduk = []
    let img = [
        'pelengkap-aluminium.jpg',
        'alumunium.jpg',
        'plat-strip-aluminium.jpg',
        'pipa-bulat-aluminium.jpg',
        'hollow-aluminium.jpg',
        'talang-roll-aluminium.jpg',
        'plat-aluminium.jpg',
        'mesh-aluminium.jpg',
        'rolling-door-aluminium.jpg',
        'pipa-stainless-sch.jpg',
        'pipa-bulat-stainless.jpg'
    ]
    let img_i = 0

    $(document).ready(function() {
        toastr.options = {
            positionClass: 'toast-bottom-right',
        }

        // define requests
        var json = $.getJSON({
            url: `{{ url('/data/catalogue/products') }}`,
            type: 'GET',
            async: true
        }).then((res) => {
            const {data} = res
            if (carts == null) {
                carts = []
            }

            $('#product-wrapper').empty()
            bindView(data)
        });

        $("#search_form").submit(function(event) {
            event.preventDefault();
            var input = $("#input_search").val();
            search(input)
        });
    }) // end of jquery

    function search(input, string = 'strings') {
        let url = ''
        let type = 'GET'
        if(string == 'sql') {
            url = `{{ url('analytics/speed/sql') }}`
            type = 'GET'    
        }
        else url = `{{ url('data/rabin') }}/${4}/${input}`
        $('#loading-notification').html('Loading, please wait...')
        $.ajax({
            type: type,
            url: url,
            data: {
                search: input,
                string: input
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                $('#product-wrapper').hide()

                if ($.fn.dataTable.isDataTable('#search-table')) $('#search-table').DataTable().destroy()
                
                $('#search-table tbody').empty()
                let insertTemplate = ''
                response.data.forEach((item, index) => {
                    if (index % 2 == 0) {
                        insertTemplate += `
                            <tr style="height: 50px; background-color: #e6e6e6;">
                                <td>${index + 1}</td>
                                <td>${item.nama}</td>
                                <td>${item.kategori}</td>
                            `
                    }else{
                        insertTemplate += `
                            <tr style="height: 50px;">
                                <td>${index + 1}</td>
                                <td>${item.nama}</td>
                                <td>${item.kategori}</td>
                            `
                    }
                    if ('{{Auth::user()}}' != '') {
                        insertTemplate += `
                            <td>${item.stok}</td>
                            <td>${item.harga}</td>
                            <td>
                                <span style="display: flex">
                                    <input onInput="unitInput(event, ${item.id}, 1, ${item.stok.split('  ')[0]})" type="number" class="form-control" style="text-align: center; width: 100px;" id="${item.id}" min="1" max="${item.stok.split('  ')[0]}" data-max="${item.stok.split('  ')[0]}" value="1"/>
                                    <span class="input-group-addon bootstrap-touchspin-prefix input-group-prepend">
                                        <span class="input-group-text">Unit/Jumlah</span>
                                    </span>
                                    <button value="${item.id}" class="btn btn-md btn-round add-to-cart" style="background: #fb8b34; color: white; margin-left: 10px; font-weight: bold">
                                        <li class="icon md-shopping-cart"></li>
                                    </button>
                                </span>
                            </td>
                        `
                    }else{
                        insertTemplate += `
                            <td></td>
                            <td></td>
                            <td>Login untuk aksi lebih lanjut</td>
                        `
                    }
                    insertTemplate += `</tr>`
                });
                $('#search-table tbody').append(insertTemplate)
                $('#search-res').show()
                $('#search-table').DataTable({
                    searching: false,
                    info: false,
                    serverside:true,
                    processing: true,
                    columnDefs:[{
                        targets: [0,1,2,3,4],
                        orderable: false
                    }],
                    dom: '<"top"i>rt<"bottom"flp><"row view-filter"<"col-sm-12"<"pull-right"f><"clearfix">>>',
                    })
                $('#search-table_length label').css('display', 'flex')
                $('.bottom').css({'display': 'flex', 'justify-content': 'space-between'})
                $('.add-to-cart').prop('click', null)
                $('.add-to-cart').on('click', function(){
                    var btn=$(this).val();
                    var user = `
                        @if (isset(Auth::user()->id))
                            {{Auth::user()->id}}
                        @else
                            {{0}}
                        @endif
                    `
                    var stk = $("#"+btn).val();
                    $.ajax({
                        url:"{{route('addCart')}}",
                        method:"POST", //First change type to method here
                        data:{
                            "_token": "{{ csrf_token() }}",
                            id_barang: btn,
                            id_user: user,
                            jumlah: stk
                        },
                        success:function(response) {
                            toastr['success']('Berhasil ditambahkan')
                            cart_count()

                            table_cart.ajax.reload();
                        },
                        error:function(){
                            alert("error");
                        }

                    });
                });
                $('#loading-notification').html('')
            },
            error: (err) => {
                console.log(err)
            }
        })
    }

    function unitInput(event, id, min, max) {
        if (event.target.value > parseInt(max)) {
            $('#'+id).val(max)
        }else if(event.target.value == ''){
            $('#'+id).val(1)
        }
    }

    function hargaGroup(){
        $.ajax({
            url: "{{ route('hargaGroup') }}",
            type: 'GET',
            async: false,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                $.ajax({
                    url: "{{ route('authGetter') }}",
                    type: 'GET',
                    async: false,
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: (res2) => {
                        currentAuth = res2
                    },
                    error: (err2) => {
                        console.log(err2)
                    }
                })
                hargaPerGroup=response
                console.log(hargaPerGroup)
            },
            error: (err) =>{
                console.log(err)
            }
        });
    }

    let newdata = []
    let iterationCart = 0
    let iterationBuy = 0
    function bindView(data) {
        // currentPage = data.current_page

        $('#loader').hide()
        if (data != null && data.length != 0) {
            $('#result-not-found').hide()
            console.log(data)
            data.forEach((product, index) => {
                newdata.push(product);
                // if(currentPage != 1){
                //     index = index + (4 * (currentPage-1))
                // }
                if (img_i == img.length) {
                    img_i = 0
                }
                let alias_img = img[img_i]
                img_i = img_i + 1

                console.log(product)
                // src="{{ asset('storage/app/public/photo/steel_hollow.jpg') }}"
                let template = `
                            <div class="card card-shadow">
                                <figure class="card-img-top overlay-hover overlay">
                                    <img class="overlay-figure"
                                        src="{{ asset('storage/app/public/photo') }}/${alias_img}"
                                        style="max-width: 300px; max-height: 200px">
                                </figure>
                                <div class="card-block table-responsive">
                                    <h4 class="card-title text-center" style="font-size: 1rem; dont-weight: bold; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; text-transform:uppercase;">` + product + `</h4>

									`
                                    template+=`
										<p class="text-center" style="color: #fb8b34; font-weight: bold"><a class="btn btn-round btn-primary" href="{{ url('detail/product') }}/` + product.replace(' ', '__') + `"><b>Detail</b></a></p>
										`
                                template+=`
                            </div>
                        </div>`;
                $('#product-wrapper').append(template);
            })
        } else {
            let template = `
                <div class="panel text-center" id="result-not-found" style="text-align: center">
                    <div class="panel-body" style="background-color: #f1f4f5;">
                        <div class="vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out" style="background-color: #f1f4f5;">
                            <div class="vertical-align-middle">
                                <header>
                                    <h1 class="animation-slide-top" style="font-size: 7rem;"> &#129488 </h1>
                                    <br>
                                    <p style="font-size: 1.5rem;">Data yang anda cari tidak ada !</p>
                                </header>
                            </div>
                        </div>
                    </div>
                </div>`;
            $('#product-wrapper').before(template);
        }

        if (data.current_page == data.last_page) {
            $('#load-more').hide()
        } else {
            $('#load-more').show()
        }

        $('#catatan').text(data.data[0].catatan)

    }

    function analyticsPage() {
        window.open('{{url("analytics")}}/'+$('#input_search').val()).focus()
    }
</script>
@endsection
