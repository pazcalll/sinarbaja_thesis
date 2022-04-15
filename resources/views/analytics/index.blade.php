@php
$showNavigation = false;
$bodyType = 'site-menubar-unfold';
@endphp

@extends('app')
@section('css')
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style>
        
    </style>
@endsection

@section('page')
<!-- Sidebar -->
{{-- <div class="w3-sidebar w3-border w3-light-grey w3-bar-block" style="width:15%; height: 75%">
    <h3 class="w3-bar-item">Analytics</h3>
    <a href="#" class="w3-bar-item w3-button">Preprocessing</a>
    <a href="#" class="w3-bar-item w3-button">Rabin Karp</a>
    <a href="#" class="w3-bar-item w3-button">Similarity</a>
</div> --}}
<div class="site-menubar" style="left: 0px;">
    <div class="site-menubar-body">
        <div>
            <div>
                <ul class="site-menu" data-plugin="menu">
                    <li class="site-menu-category">Analytics Menu</li>
					<li class="site-menu-item has-sub">
						<a href="javascript:void(0)">
							<i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
							<span class="site-menu-title">Preprocessing</span>
							<span class="site-menu-arrow"></span>
						</a>
						<ul class="site-menu-sub">
							<li class="site-menu-item item-point active" data-url='{{url("analytics/pre-casefolding")}}'>
								<a class="animsition-link" href="">
									<span class="site-menu-title">Case Folding</span>
								</a>
							</li>
							<li class="site-menu-item item-point" data-url='{{url("analytics/pre-punctuation")}}'>
								<a class="animsition-link" href="">
									<span class="site-menu-title">Punctuation Removal</span>
								</a>
							</li>
						</ul>
					</li>
					<li class="site-menu-item has-sub">
						<a href="javascript:void(0)">
							<i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
							<span class="site-menu-title">Rabin Karp</span>
							<span class="site-menu-arrow"></span>
						</a>
						<ul class="site-menu-sub">
							<li class="site-menu-item item-point" data-url='{{url("analytics/rabin-kgram")}}'>
								<a class="animsition-link" href="">
									<span class="site-menu-title">K-Gram</span>
								</a>
							</li>
							<li class="site-menu-item item-point" data-url='{{url("analytics/rabin-hashing")}}'>
								<a class="animsition-link" href="">
									<span class="site-menu-title">Hash</span>
								</a>
							</li>
							<li class="site-menu-item item-point" data-url='{{url("analytics/rabin-intersect")}}'>
								<a class="animsition-link" href="">
									<span class="site-menu-title">Intersect</span>
								</a>
							</li>
						</ul>
					</li>
					<li class="site-menu-item item-point" data-url='{{url("analytics/similarity")}}'>
						<a href="javascript:void(0)">
							<i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
							<span class="site-menu-title">Similarity</span>
						</a>
					</li>
					<li class="site-menu-item has-sub">
						<a href="javascript:void(0)">
							<i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
							<span class="site-menu-title">Test</span>
							<span class="site-menu-arrow"></span>
						</a>
						<ul class="site-menu-sub">
							<li class="site-menu-item item-point" data-url='{{url("analytics/speed")}}'>
								<a class="animsition-link" href="">
									<span class="site-menu-title">Speed and Rows</span>
								</a>
							</li>
						</ul>
					</li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div style="margin-left:20%">
	<div class="w3-container">
		<h1 id="page-title">Case Folding</h1>
	</div>
	<div style="width: 100%; right: 0px; background-color: white;" class="w3-container" id="main-content"></div>
</div>
@endsection

@section('js')
	<script>
		$(document).ready(function() {
			pageGetter('{{url("analytics/pre-casefolding")}}')
			// $('#main-content').load('{{url("analytics/pre-casefolding")}}');
		});
		$('.item-point').on('click', function(e) {
			e.preventDefault()
			$('.item-point').removeClass('active')
			$('.item-point').removeClass('open')
			$(this).parent().addClass('active');
			$(this).parent().addClass('open');
			$('#page-title').html($(this).find('span').html())
			$('#main-content').html('Please Wait')
			pageGetter($(this).data('url'))
		})

		function pageGetter(url) {
			$.ajax({
				url: url,
				type: 'post',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: {
					string: "{{$string}}"
				},
				success: (res) => {
					$('#main-content').html(res)
				}
			})
		}
	</script>
@endsection