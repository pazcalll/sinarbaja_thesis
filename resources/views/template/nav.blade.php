@php
// $DATAMENU = App\MappingMenu::whereIn('group', ['ALL', Auth::user()->group_id])
$id_group = App\GroupUser::where('id', Auth::user()->id_group)->get('group_name')->first();
$DATAMENU = App\MappingMenu::whereIn('group', ['ALL', $id_group->group_name])
    ->get()
    ->sortBy('menu.urutan');
@endphp

<div class="site-menubar">
    <div class="site-menubar-body">
        <div>
            <div>
                <ul class="site-menu" data-plugin="menu">
                    <li class="site-menu-category"></li>

                    @foreach ($DATAMENU as $i => $item)
                        @if ($item->menu->parent == null)
                            @php
                                $filter = $DATAMENU->filter(function ($query) use ($item) { return $query->menu->parent == $item->menu->id; });
                                $open = false;

                                foreach ($filter->all() ?? [] as $i => $submenu){
                                    if (url()->current() == url($submenu->menu->link)){
                                        $open = true;
                                        break;
                                    }
                                }
                            @endphp

                            <li class="site-menu-item has-sub @if (url()->current() == url($item->menu->link) || $open ?? false) active open @endif">
                                <a
                                    href="{{ $filter->count() > 0 ? 'javascript:void(0)' : url($item->menu->link) }}">
                                    <i class="site-menu-icon md-view-compact" aria-hidden="true"></i>
                                    <span class="site-menu-title"> {{ $item->menu->nama }} </span>
                                    @if ($filter->count() > 0)
                                        <span class="site-menu-arrow"></span>
                                    @endif
                                </a>
                                @if ($filter->count() > 0)
                                    <ul class="site-menu-sub">
                                        @foreach ($filter->all() ?? [] as $i => $submenu)
                                            @if ($submenu->menu->nama != 'Tambah Produk')
                                                <li class="site-menu-item  @if (url()->current() == url($submenu->menu->link)) 'active' @endif">
                                                    <a class="animsition-link" href="{{ url($submenu->menu->link ?? '') }}">
                                                        <span class="site-menu-title">{{ $submenu->menu->nama }}</span>
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
</div>
