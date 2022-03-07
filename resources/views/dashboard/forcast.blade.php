@extends('template.pages.datatable', [
'page' => 'Data produk yang akan dibeli',
'breadcumbs' => [
['nama' => 'Dashboard', 'link' => 'javascript:void(0)', 'active' => ''],
['nama' => 'Forecasting', 'link' => '', 'active' => 'active']
]
])

@section('table')
<table class="table table-bordered table-hover table-striped" id="exampleAddRow">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Tipe User</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach (App\User::all() as $i => $item)
                <tr>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->email }}</td>
                    <td>{{ $item->group_id ?? '' }}</td>
                    <td class="actions">
                      <a href="#" class="btn btn-sm btn-icon btn-pure btn-default on-editing save-row"
                        data-toggle="tooltip" data-original-title="Save" hidden><i class="icon md-wrench" aria-hidden="true"></i></a>
                      <a href="#" class="btn btn-sm btn-icon btn-pure btn-default on-editing cancel-row"
                        data-toggle="tooltip" data-original-title="Delete" hidden><i class="icon md-close" aria-hidden="true"></i></a>
                      <a href="#" class="btn btn-sm btn-icon btn-pure btn-default on-default edit-row"
                        data-toggle="tooltip" data-original-title="Edit"><i class="icon md-edit" aria-hidden="true"></i></a>
                      <a href="#" class="btn btn-sm btn-icon btn-pure btn-default on-default remove-row"
                        data-toggle="tooltip" data-original-title="Remove"><i class="icon md-delete" aria-hidden="true"></i></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
