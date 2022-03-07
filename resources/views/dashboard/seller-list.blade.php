@extends('template.pages.datatable', [
'page' => 'Seller List',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Account', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Seller List', 'link' => '', 'active' => 'active']
]
])


@section('table')

<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
    <thead>
        <tr align="center">
            <th width="1%">No.</th>
            <th>Nama Sales</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($seller as $key => $item)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$item['name']}}</td>
                <td align="center">
                    {{-- {{$item['id']}} --}}
                    <button onclick="salesBonus('{{$item['id']}}', '{{$item['name']}}')" class="btn btn-info"><i class="icon md-info"></i> Detail</button>
                </td>
            </tr>
        @endforeach
    </tbody>
</table>
@endsection

@section('js')
<script>
    function salesBonus(id, name) {
        window.location.href= '{{ url("dashboard/bonus/sales/") }}/'+parseInt(id) + '/' + name
    }
</script>
@endsection