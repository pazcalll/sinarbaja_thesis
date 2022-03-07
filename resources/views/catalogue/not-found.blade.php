@php
$showNavigation = false;
$bodyType = 'site-menubar-unfold';
@endphp

@extends('app')

@section('page')
<div class="row">
    <div class="col-12">
        <div class="py-15">
            <div class="text-center">
                <div class="btn-group" aria-label="Basic example" role="group">
                    <a type="button" class="btn btn-icon btn-light waves-effect waves-classic" href="{{ url('/') }}" style="color: #3f51b5"><i class="icon md-collection-image" aria-hidden="true"></i></a>
                    <a class="btn btn-icon btn-light waves-effect waves-classic" href="{{ url('index-tabel') }}" style="color: #3f51b5"><i class="icon md-view-list" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="panel ">
    <div class="panel-body" style="background-color: #f1f4f5;">
        <div class="vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out" style="background-color: #f1f4f5;">
            <div class="vertical-align-middle">
                <header>
                    <h1 class="animation-slide-top" style="font-size: 7rem;"> &#129488 </h1>
                    <br>
                            <p style="font-size: 1.5rem;">Data yang ada cari tidak ada !</p>
                </header>
                <br><br><br>
                <a class="btn btn-primary btn-round" href="{{ url('/') }}">Kembali</a>
                <br>
                <footer class="page-copyright">
                    <p>WEBSITE BY AGSATU</p>
                    <p>© 2021. All RIGHT RESERVED.</p>
                    <div class="social">
                        <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                            <i class="icon bd-twitter" aria-hidden="true"></i>
                        </a>
                        <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                            <i class="icon bd-facebook" aria-hidden="true"></i>
                        </a>
                        <a class="btn btn-icon btn-pure" href="javascript:void(0)">
                            <i class="icon bd-google-plus" aria-hidden="true"></i>
                        </a>
                    </div>
                </footer>
            </div>
        </div>
    </div>
</div>
@endsection

@section('modal')

@endsection

@section('js')

@endsection
