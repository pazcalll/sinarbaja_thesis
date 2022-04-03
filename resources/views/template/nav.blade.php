@php
// $DATAMENU = App\MappingMenu::whereIn('group', ['ALL', Auth::user()->group_id])
$id_group = App\GroupUser::where('id', Auth::user()->id_group)->get('group_name')->first();
@endphp

<div class="site-menubar">
    <div class="site-menubar-body">
        <div>
            <div>
                <ul class="site-menu" data-plugin="menu">
                    <li class="site-menu-category">Admin Menu</li>
					<li class="site-menu-item has-sub">
						<a href="javascript:void(0)">
							<i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
							<span class="site-menu-title">Items</span>
							<span class="site-menu-arrow"></span>
						</a>
						<ul class="site-menu-sub">
							<li class="site-menu-item item-point" data-url='{{url("dashboard/items")}}'>
								<a class="animsition-link" href="javascript:void(0)">
									<span class="site-menu-title">List</span>
								</a>
							</li>
							<li class="site-menu-item item-point" data-url='{{url("analytics/pre-punctuation")}}'>
								<a class="animsition-link" href="">
									<span class="site-menu-title">Stock</span>
								</a>
							</li>
						</ul>
					</li>
					<li class="site-menu-item has-sub">
						<a href="javascript:void(0)">
							<i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
							<span class="site-menu-title">Order</span>
							<span class="site-menu-arrow"></span>
						</a>
						<ul class="site-menu-sub">
							<li class="site-menu-item item-point" data-url='{{url("analytics/rabin-kgram")}}'>
								<a class="animsition-link" href="javascript:void(0)">
									<span class="site-menu-title">List</span>
								</a>
							</li>
							{{-- <li class="site-menu-item item-point" data-url='{{url("analytics/rabin-hashing")}}'>
								<a class="animsition-link" href="">
									<span class="site-menu-title">Hash</span>
								</a>
							</li>
							<li class="site-menu-item item-point" data-url='{{url("analytics/rabin-intersect")}}'>
								<a class="animsition-link" href="">
									<span class="site-menu-title">Intersect</span>
								</a>
							</li> --}}
						</ul>
					</li>
                    <li class="site-menu-item item-point" data-url='{{url("dashboard/table-users")}}'>
						<a href="javascript:void(0)">
							<i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
							<span class="site-menu-title">Users</span>
						</a>
					</li>
                </ul>
            </div>
        </div>
    </div>
</div>
