@extends('app')
@section('css')
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-bs4/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-fixedheader-bs4/dataTables.fixedheader.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-fixedcolumns-bs4/dataTables.fixedcolumns.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-rowgroup-bs4/dataTables.rowgroup.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-scroller-bs4/dataTables.scroller.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-select-bs4/dataTables.select.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-responsive-bs4/dataTables.responsive.bootstrap4.css') }}">
    <link rel="stylesheet"
        href="{{ asset('public/themeforest/global/vendor/datatables.net-buttons-bs4/dataTables.buttons.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/page-base/examples/css/tables/datatable.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/bootstrap-sweetalert/sweetalert.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/toastr/toastr.css') }}">
    <link rel="stylesheet" href="{{ asset('public/themeforest/global/vendor/select2/select2.css') }}">

    @yield('style')
@endsection
@section('page')
    <div class="page-content">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-15">
                    <h3>Harga Produk Untuk User {{$name}}</h3>
                </div>
            </div>
        </div>
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="container">
                    <div class="row row-lg">
                        <div class="col-md-12">
                            <div class="example-wrap">
                              <button class="btn" style="background-color:transparent" type="button" data-toggle="collapse" data-target="#multiCollapseExample2" aria-expanded="false" aria-controls="multiCollapseExample2">
                                <b><ion-icon name="filter-outline"></ion-icon> Filter</b>
                              </button>
                                <div class="collapse multi-collapse" id="multiCollapseExample2">
                                  <div class="card card-body" style="background:#f7fdfd">
                                    <div class="form-group form-check">
                                     <input type="checkbox" class="form-check-input" id="checkbox_harga">
                                     <label class="form-check-label" for="checkbox_harga">Harga Kosong</label>
                                   </div>
                                  </div>
                                </div>
                                <div class="example">
                                    <table class="table table-bordered table-hover table-striped" id="table-harga-user">
                                        <thead>
                                            <tr width="100%">
                                                {{-- <th width="5%"><input type="checkbox" name="checkAll" id="checkAll"></th> --}}
                                                <th width="5%">No</th>
                                                <th width="20%">Nama Produk</th>
                                                <th width="15%">Kode Produk</th>
                                                <th width="10%"><input type="checkbox" class="saverAll1" name="checkAll" id="checkAll"> Level 1</th>
                                                <th width="10%"><input type="checkbox" class="saverAll2" name="checkAll1" id="checkAll1"> Level 2</th>
                                                <th width="10%"><input type="checkbox" class="saverAll3" name="checkAll2" id="checkAll2"> Level 3</th>
                                                <th width="10%"><input type="checkbox" class="saverAll4" name="checkAll3" id="checkAll3"> Level 4</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                    <form id="hiddenForm">
                                        <div id="hiddenInputs"></div>
                                    </form>
                                    <button class="btn btn-primary" type="submit" name="subVal" id="subVal">Save All</button>
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
        var check_harga_filter = ''
        var asw = []
        let contentSetHarga = ''
        let saveAllArr = []
        $(document).ready(function(){
            setHargaUser('{{$userId}}', '{{$groupId}}');

            $('input[type=checkbox]').on('click', function() {
                $('#subVal').prop('disabled', false)
            })
            $('#subVal').on('click',function () {
                saveAll(saveAllArr)
            })
        })
        function hargaUserSet(data) {
            $.ajax({
                url: '{{ route("setHargaUser") }}',
                type: 'POST',
                data: data,
                success: (res) => {
                    $('#table-harga-user tbody').empty()
                    setHargaUser('{{$userId}}');
                    toastr['success']('Harga user telah di ubah !')
                }
            })
        }
        $('#checkbox_harga').click(function() {
            if ($('#checkbox_harga').is(':checked')) {
              check_harga_filter = true
            }
            else {
              check_harga_filter = ''
            }
            // $('#table-harga-user').DataTable().ajax.url(`{{url('data/setting_harga/user/hargaUser')}}/{{$userId}}/{{$groupId}}?filter_check=${check_harga_filter}`).load();
            setHargaUser();
          });
        function setHargaUser($userId, $groupId) {
            // console.log($groupId)
            $('#table-harga-user tbody').empty();
            $('#table-harga-user').DataTable().destroy();
            $.ajax({
                url: `{{ url('data/setting_harga/user/hargaUser') }}/{{$userId}}/{{$groupId}}?filter_check=${check_harga_filter}`,
                type: 'GET',
                // async: false,
                beforeSend: function ( xhr ) {
                   //Add your image loader here
                },
                success: (res) => {
                    $('#table-harga-user tbody').empty();
                    let setHarga = null
                    $.ajax({
                        url: `{{ url('data/setting_harga/user/pilihHarga')}}/{{$userId}}/{{$groupId}}`,
                        type: 'GET',
                        async: false,
                        success: (res) => {
                            setHarga = res
                            console.log(setHarga)
                            // console.log("rr",setHarga)
                        }
                    })
                    var arr= []

                    var kwe = []
                    arr = (res)
                    res.forEach((item, _index) => {

                        contentSetHarga += `
                        <tr>
                            <input type="hidden" name="id" id="id${item.id}" value="${item.id}">
                            <input type="hidden" name="idGroup" value="${item.id_group}">
                            <input type="hidden" name="idUser" id="idUser${item.id_user}" value="${item.id_user}">
                            <td>${_index + 1}</td>
                            <td>${item.nama}</td>
                            <td>${item.barang_kode}</td>
                            <td style="text-align: center"><input type="checkbox" class="checkbox" name="check" id="check${item.id}a"/><br>
                                <label style="font-size: 12px; font-style: italic;">Rp. <span id="nominal${item.id}a">${item.level1}</span></label></td>
                            <td style="text-align: center"><input type="checkbox" class="checkbox" name="check1" id="check1${item.id}b"><br>
                                <label style="font-size: 12px; font-style: italic;">Rp. <span id="nominal${item.id}b">${item.level2}</span></label></td>
                            <td style="text-align: center"><input type="checkbox" class="checkbox" name="check2" id="check2${item.id}c"><br>
                                <label style="font-size: 12px; font-style: italic;">Rp. <span id="nominal${item.id}c">${item.level3}</span></label></td>
                            <td style="text-align: center"><input type="checkbox" class="checkbox" name="check3" id="check3${item.id}d"><br>
                                <label style="font-size: 12px; font-style: italic;">Rp. <span id="nominal${item.id}d">${item.level4}</span></label></td>
                            <td style="text-align: center"><input type="hidden" name="harga${item.id}" id="harga${item.id}" value=""><button type="submit" name="submit" id="submit${item.id}" class="btn btn-primary">Save</button></td>
                        </tr>
                        `

                        $('#table-harga-user tbody').append(contentSetHarga)
                        contentSetHarga = ''
                        // console.log(arr)
                        $(`#submit${item.id}`).on('click', function(){
                            let arr = {
                                lev1 : $(`#harga${item.id}`).val(),
                                id: $(`#id${item.id}`).val(),
                                idUser: $(`#idUser${item.id_user}`).val()
                            }
                            $.ajax({
                                url: '{{ route("setHargaUser") }}',
                                type: 'POST',
                                data: arr,
                                success: (res) => {
                                    // $('#table-harga-user tbody').empty()
                                    console.log(res)
                                    // setHargaUser('{{$userId}}');
                                }
                            })
                            toastr['success']('Harga user telah di ubah !')
                        })

                        $(`#form${item.id}`).unbind()
                        $(`#form${item.id}`).on('submit', function(e){
                            e.preventDefault()
                            hargaUserSet($(this).serialize())
                        })
                    })
                    // console.log(arr)

                    arr.forEach((items, _index) => {
                        saveAllArr[`${items.id}`] = {
                            id: items.id,
                            id_user: items.id_user,
                            harga: 0,
                            checkboxId: ''
                        }
                        $(`#check${items.id}a`).change(function() {
                            if(this.checked) {
                              // $(`#harga${items.id}`).val(`${items.level1}`)
                                $(`#harga${items.id}`).val(2)
                                $(`#checkAll3, #checkAll1, #checkAll2, #check1${items.id}b, #check2${items.id}c, #check3${items.id}d`).prop("checked", false)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 2,
                                    checkboxId: `#check${items.id}a`
                                }
                                //
                            }else if(!this.checked) {
                              $(`#harga${items.id}`).val(0)
                                $(`#checkAll3, #checkAll1, #checkAll2, #check1${items.id}b, #check2${items.id}c, #check3${items.id}d`).prop("checked", false)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 0,
                                    checkboxId: ''
                                }
                                //
                            }
                        });

                        $(`#check1${items.id}b`).change(function() {
                            if(this.checked) {
                                // $(`#harga${items.id}`).val(`${items.level2}`)
                                $(`#harga${items.id}`).val(3)

                                $(`#checkAll3, #checkAll, #checkAll2,#check${items.id}a, #check2${items.id}c, #check3${items.id}d`).prop("checked", false)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 3,
                                    checkboxId: `#check1${items.id}b`
                                }
                                //
                            }else if(!this.checked) {
                                $(`#checkAll3, #checkAll, #checkAll2,#check${items.id}a, #check2${items.id}c, #check3${items.id}d`).prop("checked", false)
                                $(`#harga${items.id}`).val(0)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 0,
                                    checkboxId: ''
                                }
                                //
                            }
                        });

                        $(`#check2${items.id}c`).change(function() {
                            if(this.checked) {
                                // $(`#harga${items.id}`).val(`${items.level3}`)
                                $(`#harga${items.id}`).val(4)

                                $(`#checkAll3, #checkAll1, #checkAll, #check1${items.id}b, #check${items.id}a, #check3${items.id}d`).prop("checked", false)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 4,
                                    checkboxId: `#check2${items.id}c`
                                }
                                //
                            }else if(!this.checked) {
                                $(`#checkAll3, #checkAll1, #checkAll, #check1${items.id}b, #check${items.id}a, #check3${items.id}d`).prop("checked", false)
                                $(`#harga${items.id}`).val(0)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 0,
                                    checkboxId: ''
                                }
                                //
                            }
                        });

                        $(`#check3${items.id}d`).change(function() {
                            if(this.checked) {
                                // $(`#harga${items.id}`).val(`${items.level4}`)
                                $(`#harga${items.id}`).val(5)

                                $(`#checkAll, #checkAll1, #checkAll2, #check1${items.id}b, #check2${items.id}c, #check${items.id}a`).prop("checked", false)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 5,
                                    checkboxId: `#check3${items.id}d`
                                }
                                //
                            }else if(!this.checked) {
                                $(`#checkAll, #checkAll1, #checkAll2, #check1${items.id}b, #check2${items.id}c, #check${items.id}a`).prop("checked", false)
                                $(`#harga${items.id}`).val(0)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 0,
                                    checkboxId: ''
                                }
                            }
                        });
                    })

                    // arr.forEach((items, _index) => {
                    $('#subVal').prop("disabled",true);

                    $('#checkAll').change(function() {
                        if(this.checked) {
                            // $('#table-harga-user').DataTable().destroy()
                            arr.forEach((items, _index) => {
                              $(`#harga${items.id}`).val(`${items.level1}`)
                                $(`#harga${items.id}`).val(2)
                                // console.log($(`#harga${items.id}`).val())
                                $(`#check${items.id}a`).prop('checked', true);
                                $('#subVal').prop("disabled", false);
                                $(`#checkAll3, #checkAll1, #checkAll2, #check1${items.id}b, #check2${items.id}c, #check3${items.id}d`).prop("checked", false)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 2,
                                    checkboxId: `#check${items.id}a`
                                }
                            })
                            // dataTable()
                            // asw.push(items)

                        }else if(!this.checked) {
                            $(`#check${items.id}a`).prop('checked', false);
                            $('#subVal').prop("disabled", true);
                            $(`#checkAll3, #checkAll1, #checkAll2, #check1${items.id}b, #check2${items.id}c, #check3${items.id}d`).prop("checked", false)
                            asw.forEach((item) => {
                                if($(`#check${items.id}a`).prop("checked")===true) {
                                    kwe.push(item)
                                }else{

                                }
                            })
                            asw = kwe
                            kwe = []
                            // console.log(asw)
                        }
                    })

                    $('#checkAll1').change(function() {
                        if(this.checked) {
                            // $('#table-harga-user').DataTable().destroy()
                            arr.forEach((items, _index) => {
                                $(`#check1${items.id}b`).prop('checked', true);
                                // $(`#harga${items.id}`).val(`${items.level2}`)
                                $(`#harga${items.id}`).val(3)
                                $('#subVal').prop("disabled", false);
                                $(`#checkAll, #checkAll3, #checkAll2, #check${items.id}a, #check2${items.id}c, #check3${items.id}d`).prop("checked", false)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 3,
                                    checkboxId: `#check1${items.id}b`
                                }
                            })

                        }else if(!this.checked) {
                            $(`#check1${items.id}b`).prop('checked', false);
                            $('#subVal').prop("disabled", true);
                            $(`#checkAll, #checkAll3, #checkAll2, #check${items.id}a, #check2${items.id}c, #check3${items.id}d`).prop("checked", false)
                            asw.forEach((item) => {
                                if($(`#check1${items.id}b`).prop("checked")===true) {
                                    kwe.push(item)
                                }else{

                                }
                            })
                            asw = kwe
                            kwe = []
                            // console.log(asw)
                        }
                    })

                    $('#checkAll2').change(function() {
                        if(this.checked) {
                            arr.forEach((items, _index) => {
                                $(`#check2${items.id}c`).prop('checked', true);
                                // $(`#harga${items.id}`).val(`${items.level3}`)
                                $(`#harga${items.id}`).val(4)
                                $('#subVal').prop("disabled", false);
                                $(`#checkAll, #checkAll1, #checkAll3, #check1${items.id}b, #check${items.id}a, #check3${items.id}d`).prop("checked", false)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 4,
                                    checkboxId: `#check2${items.id}c`
                                }
                            })

                        }else if(!this.checked) {
                            $(`#check2${items.id}c`).prop('checked', false);
                            $('#subVal').prop("disabled", true);
                            $(`#checkAll, #checkAll1, #checkAll3, #check1${items.id}b, #check${items.id}a, #check3${items.id}d`).prop("checked", false)
                            asw.forEach((item) => {
                                if($(`#check2${items.id}c`).prop("checked")===true) {
                                    kwe.push(item)
                                }else{

                                }
                            })
                            asw = kwe
                            kwe = []
                        }
                    })

                    $('#checkAll3').change(function() {
                        if(this.checked) {
                            arr.forEach((items, _index) => {
                                $(`#check3${items.id}d`).prop('checked', true);
                                // $(`#harga${items.id}`).val(`${items.level4}`)
                                $(`#harga${items.id}`).val(5)
                                $('#subVal').prop("disabled", false)
                                $(`#checkAll, #checkAll1, #checkAll2, #check1${items.id}b, #check2${items.id}c, #check${items.id}a`).prop("checked", false)
                                saveAllArr[`${items.id}`] = {
                                    id: items.id,
                                    id_user: items.id_user,
                                    harga: 5,
                                    checkboxId: `#check3${items.id}d`
                                }
                            })

                        }else if(!this.checked) {
                            $(`#check3${items.id}d`).prop('checked', false);
                            $('#subVal').prop("disabled", true)
                            $(`#checkAll, #checkAll1, #checkAll2, #check1${items.id}b, #check2${items.id}c, #check${items.id}a`).prop("checked", false)
                            asw.forEach((item) => {
                                if($(`#check3${items.id}d`).prop("checked")===true) {
                                    kwe.push(item)
                                }else{

                                }
                            })
                            asw = kwe
                            kwe = []
                            // console.log(asw)
                        }
                    })
                    // })

                    setHarga.forEach(function(el){
                        var h = el.harga_user
                        var p = el.id_product
                        saveAllArr[el.id_product].harga = el.harga_user
                        if (h == $(`#nominal${p}a`).html()) {
                            $(`#check${p}a`).click()
                        }else if(h == $(`#nominal${p}b`).html()){
                            $(`#check1${p}b`).click()
                        }else if(h == $(`#nominal${p}c`).html()){
                            $(`#check2${p}c`).click()
                        }else if(h == $(`#nominal${p}d`).html()){
                            $(`#check3${p}d`).click()
                        }
                    })}
            })
            dataTable()
            $('#table-harga-user_paginate').on('click', function() {

                console.log(parseInt($('.odd .sorting_1').html()))
                console.log(parseInt($(`.custom-select-sm`).val()))
                let numberStart = parseInt($('.odd .sorting_1').html())
                let numberLength = parseInt($(`.custom-select-sm`).val()) + numberStart
                for (let i = numberStart; i < numberLength; i++) {
                    if (i > $('#table-harga-user').DataTable().data().length) break
                    console.log(saveAllArr[i], $('#table-harga-user').DataTable().data().length)
                    $(`#check${saveAllArr[i].id}a, #check1${saveAllArr[i].id}b, #check2${saveAllArr[i].id}c, #check3${saveAllArr[i].id}d`).prop('checked', false)
                    $(saveAllArr[i].checkboxId).prop('checked', true)
                }
            })
        }

        function dataTable() {
            $('#table-harga-user').DataTable({
                // "processing" : false,
                // "order": [],
                "searchable":false,
                "stateSave": true,
                "pageLength": 10,
                "columnDefs": [{
                    "targets"  : [0,1,2,3,4,5,6,7],
                    "orderable": false
                }]
            })
        }

        function saveAll(hargaLv) {
            let newHargaLv = []
            let iteration = 0
            hargaLv.forEach((item) => {
                newHargaLv.push({
                    id: item.id,
                    id_user: item.id_user,
                    harga: item.harga
                })
            });
            console.log(newHargaLv)
            $.ajax({
                url: `{{ route('saveAllHargaUser') }}`,
                type: `POST`,
                data: {data: newHargaLv},
                success: (res) => {
                    toastr['success']('Semua harga user telah di ubah !')
                },
                error: (err) => {
                    toastr['error']('Aksi gagal!')
                    console.error(err)
                }
            })
        }
    </script>
@endsection
