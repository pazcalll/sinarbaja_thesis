@extends('app')

@section('page')
    <div class="page-content">
        <div class="panel">
            <div class="panel-body container-fluid">
                <div class="row row-lg">
                    <div class="col-md-12">
                        <!-- Example Basic Form (Form grid) -->
                        <div class="example-wrap">
                            <h4 class="example-title">Tambah Produk Baru</h4>
                            <div class="example">
                                <form id="add-new-product" autocomplete="off" method="POST" enctype="multipart/form-data">
                                    @csrf

                                    <div class="row row-lg">
                                        <div class="col-xl-6 form-horizontal">
                                            <div class="form-group row form-material">
                                                <label class="col-xl-12 col-md-3 form-control-label text-left">Nama Produk
                                                    <span class="required">*</span>
                                                </label>
                                                <div class=" col-xl-12 col-md-9">
                                                    <input type="text" class="form-control" name="nama"
                                                           placeholder="Contoh: Joe Malon" required=""
                                                           value="{{ $product->nama ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="form-group row form-material">
                                                <label class="col-xl-12 col-md-3 form-control-label text-left">Deskripsi
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="col-xl-12 col-md-9">
                                                    <textarea class="form-control" name="deskripsi" rows="3"
                                                            placeholder="Deskripsi Produk" required="">{{ $product->deskripsi ?? '' }}</textarea>
                                                </div>
                                            </div>

                                            <div class="form-group row form-material">
                                                <label class="col-xl-12 col-md-3 form-control-label text-left">Harga
                                                    <span class="required">*</span>
                                                </label>
                                                <div class=" col-xl-12 col-md-9">
                                                    <input type="text" class="form-control" name="harga"
                                                        placeholder="Contoh: 10000000" required="" value="{{ $product->harga ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="form-group row form-material">
                                                <label class="col-xl-12 col-md-3 form-control-label text-left">Stok Awal
                                                    <span class="required">*</span>
                                                </label>
                                                <div class=" col-xl-12 col-md-9">
                                                    <input type="text" class="form-control" name="stock"
                                                        placeholder="Contoh: 13" required="">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-xl-6 form-horizontal">
                                            <div class="form-group row form-material">
                                                <label class="col-xl-12 col-md-3 form-control-label text-left">Merek
                                                    <span class="required">*</span>
                                                </label>
                                                <div class=" col-xl-12 col-md-9">
                                                    <input type="text" class="form-control" name="merek"
                                                        placeholder="Contoh: Joe Malon" required="" value="{{ $product->merek ?? '' }}">
                                                </div>
                                            </div>

                                            <div class="form-group row form-material">
                                                <label class="col-xl-12 col-md-3 form-control-label text-left">Kategori
                                                    <span class="required">*</span>
                                                </label>
                                                <div class=" col-xl-12 col-md-9">
                                                    <select class="form-control" data-plugin="select2" name="kategori"
                                                        required style="z-index: -1;">
                                                        <option value="">Select</option>
                                                        @foreach (App\Category::tree() as $i => $child)
                                                            <option value="{{ $child['id'] }}" @if($product->kategori ?? '' === $child['id']) selected @endif>{{ $child['name'] }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="form-group row form-material">
                                                <label class="col-xl-12 col-md-3 form-control-label text-left">Jenis Produk
                                                    Berbahaya?
                                                    <span class="required">*</span>
                                                </label>
                                                <div class="col-xl-12 col-md-9">
                                                    <div class="d-flex flex-column">
                                                        <div class="radio-custom radio-primary">
                                                            <input type="radio" id="inputAwesome" name="jenis_berbahaya"
                                                                value="TIDAK" required
                                                                @if($product->jenis_berbahaya ?? '' == 'TIDAK') checked @endif>
                                                            <label for="inputAwesome">Tidak</label>
                                                        </div>

                                                        <div class="radio-custom radio-primary">
                                                            <input type="radio" id="inputVeryAwesome" name="jenis_berbahaya"
                                                                value="YA" required
                                                                @if($product->jenis_berbahaya ?? '' == 'YA') checked @endif>
                                                            <label for="inputVeryAwesome">Ya</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group row form-material">
                                                <label class="col-xl-9 col-md-9 form-control-label text-left">Photo Produk</label>
                                                <div class=" col-xl-3 col-md-3" style="padding-bottom: 20px">
                                                    <button type="button" class="btn btn-sm btn-primary" id="add-new-photo-produk" ><i class="icon md-plus" aria-hidden="true"></i></button>
                                                </div>
                                                <div class="col-xl-12 col-md-12">
                                                    <!-- Example Input Groups -->
                                                    <div class="row" id="photo-produk-wrap" style="padding-bottom: 20px">
                                                    </div>
                                                    <!-- End Example Input Groups -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group form-material col-xl-12 padding-top-m">
                                            <button type="button" class="btn btn-default" id="reset">Reset
                                            </button>
                                            <button type="submit" name="submit" class="btn btn-primary"
                                                id="validateButton1">Simpan
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!-- End Example Basic Form -->
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
            formValidation({
                element: $('#add-new-product'),
                action: '#validateButton1',
                fields: {
                    harga: {
                        validators: {
                            notEmpty: {
                                message: 'The Harga is required'
                            },
                            regexp: {
                                message: 'Input harus angka',
                                regexp: /^[0-9]+$/
                            }
                        }
                    },
                    stock: {
                        validators: {
                            notEmpty: {
                                message: 'The Stock is required'
                            },
                            regexp: {
                                message: 'Input harus angka',
                                regexp: /^[0-9]+$/
                            }
                        }
                    }
                },
                method: 'POST',
                targetUri: `{{ url('/dashboard/produk') }}`,
                strMessageSuccess: 'Berhasil menambahkan data produk baru.',
                strMessageError: 'Gagal menambahkan data produk baru'
            });

            // ---
            // photo
            $('#add-new-photo-produk').click(function() {
                let random = Math.random().toString(36).substring(7);
                $('#photo-produk-wrap')
                    .append(
                        `<div class="col-xl-4 col-md-4" style="margin-top: 10px"> <input type="file" style="padding-bottom: 20px" id="new-file-` + random + `" name="photo[]" /> </div>`
                    )

                var x = $('#new-file-' + random).dropify({
                    messages: {
                        'default': 'Drag and drop a file here or click',
                        'replace': 'Drag and drop or click to replace',
                        'remove': 'Remove',
                        'error': 'Ooops, something wrong happended.'
                    }
                });
            })

            // ---
            // reset
            $('#reset').click(function() {
                location.reload();
            });
        })

    </script>
@endsection
