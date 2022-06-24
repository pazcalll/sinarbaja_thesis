@extends('app')

@section('css')
    <link rel="stylesheet" href="{{ asset('themeforest/page-base/examples/css/widgets/statistics.css') }}">
@endsection

@section('page')
    
    <div class="page-content">
        <div class="row">
            <div class="col-xl-12 col-lg-12">
                <div class="row">
                    <div class="col-lg-4">
                        <a href="javascript:void(0)" onclick="{$('.item-list').parent().parent().click(); $('.item-list').click()}" class="card p-30 flex-row justify-content-between">
                            <div class="counter counter-md text-left">
                                <div class="counter-number-group">
                                    <span class="counter-number-related text-capitalize">Items</span>
                                    
                                </div>
                            </div>
                            <div class="white">
                                <i class="icon icon-circle icon-2x md-archive bg-blue-600" aria-hidden="true"></i>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-4">
                        <a href="javascript:void(0)" onclick="{$('.order-list').parent().parent().click(); $('.order-list').click()}" class="card p-30 flex-row justify-content-between">
                            <div class="counter counter-md text-left">
                                <div class="counter-number-group">
                                    <span class="counter-number-related text-capitalize">Orders</span>
                                </div>
                            </div>
                            <div class="white">
                                <i class="icon icon-circle icon-2x md-collection-item bg-blue-600" aria-hidden="true"></i>
                            </div>
                        </a>
                    </div>
                    
                    <div class="col-lg-4">
                        <a href="javascript:void(0)" onclick="{$('.users-feature').parent().click(); $('.users-feature').click()}" class="card p-30 flex-row justify-content-between">
                            <div class="counter counter-md text-left">
                                <div class="counter-number-group">
                                    <span class="counter-number-related text-capitalize">Users</span>
                                </div>
                            </div>
                            <div class="white">
                                <i class="icon icon-circle icon-2x md-account-circle bg-blue-600" aria-hidden="true"></i>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('js')

<script>
    $('.item-point').on('click', function(e) {
        e.preventDefault()
        $('.item-point').removeClass('active')
        $('.item-point').removeClass('open')
        $(this).parent().addClass('active');
        $(this).parent().addClass('open');
        $('.page-content').html('Please Wait')
        pageGetter($(this).data('url'))
    })

    function pageGetter(url) {
        $.ajax({
            url: url,
            type: 'get',
            success: (res) => {
                $('.page-content').html(res)
            }
        })
    }
</script>
@endsection