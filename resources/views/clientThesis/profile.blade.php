@php
$showNavigation = false;
@endphp

@extends('app')

@section('page')
<div class="page-content">
    <div class="col-lg-12">
        <!-- Page Widget -->
        <div class="card card-shadow text-center" style="text-align: center">
            <div class="card-block">
                <a class="avatar avatar-100" href="javascript:void(0)">
                    <img src="{{ asset('public/img/5.jpg') }}" alt="..." style="height: 100px; width: 100px;">
                </a>
                <h4 class="profile-user">{{ $customer->name }}</h4>
                <h5><b>{{ $customer->address }}</b></h5>
                <h5><b>{{ $customer->no_handphone }}</b></h5>
            </div>

            <div class="card-block">
                <button data-target="#modalEditProfile" data-toggle="modal" type="button" data-original-title="EditProfile" class="btn btn-primary"><i class="icon md-edit" aria-hidden="true"></i>Edit</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')
<div class="modal fade example-modal-md modal-3d-sign" id="modalEditProfile" aria-hidden="true" aria-labelledby="modalEditProfile" role="dialog" tabindex="-1">
    <div class="modal-dialog modal-simple modal-center modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
                <h4 class="modal-title" style="color: blue">Form Edit Profil Pengguna</h4>
            </div>
            <form id="form-update-profile" enctype="multipart/form-data" method="POST">
                @csrf
                <div class="modal-body" style="margin-bottom: -50px; margin-left: 30px;">
                    <div class="row row-md">
                        <div class="col-xl-12">
                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label text-left">Nama
                                    <span class="required">*</span>
                                </label>
                                <div class="col-xl-12 col-md-6">
                                    <input type="text" class="form-control" name="name" value="{{ $customer->name }}" required="" readonly>
                                </div>
                            </div>

                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label text-left">Alamat
                                    <span class="required">*</span>
                                </label>
                                <div class=" col-xl-12 col-md-6">
                                    <input type="text" class="form-control" name="address" value="{{ $customer->address }}" required="">
                                </div>
                            </div>

                            <div class="form-group row form-material">
                                <label class="col-xl-12 col-md-3 form-control-label text-left">No. Telepon
                                    <span class="required">*</span>
                                </label>
                                <div class=" col-xl-12 col-md-6">
                                    <input type="text" class="form-control" name="no_handphone" value="{{ $customer->no_handphone }}" required="">
                                </div>
                            </div>
                        </div>

                        <div class="form-group form-material col-xl-12" style="margin-bottom: 50px;">
                            {{-- <button type="reset" class="btn btn-round" style="background: #fb8b34; color: white;" id="resetBtn">Reset</button> --}}
                            <button type="submit" name="submit" class="btn btn-round btn-primary" id="update-profile">Simpan</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
</div>

@endsection

@section('js')
<script src="{{ asset('public/themeforest/global/vendor/toastr/toastr.js') }}"></script>
<script src="{{ asset('public/themeforest/global/js/Plugin/toastr.js') }}"></script>

<script>
    $(document).ready(function() {
        $('#form-update-profile').on('submit', function(e) {
            e.preventDefault()
            $.ajax({
                url: '{{route("profile_update")}}',
                type: 'POST',
                data: $(this).serialize(),
                success: () => {
                    toastr['success']("Update Data Success")
                    setTimeout(() => {
                        window.location.reload()
                    }, 3000);
                },
                error: ()=> {
                    toastr['error']('Update Failed')
                }
            })
        })
    })
</script>
@endsection