{{-- @php
    dd($arrayExcel);    
@endphp --}}
<table>
    <thead>
    <tr>
        <th>ID Barang</th>
        <th>Nama Barang</th>
        <th>Kode Barang</th>
        <th>Stok</th>
        <th>Nama Satuan</th>
        <th>ID Satuan</th>
    </tr>
    </thead>
    <tbody>
    @foreach($arrayExcel as $item)
        <tr>
            <td>{{ $item->barang_id }}</td>
            <td>{{ $item->barang_nama }}</td>
            <td>{{ $item->barang_kode }}</td>
            <td>{{ $item->stok }}</td>
            <td>{{ $item->nama_satuan }}</td>
            <td>{{ $item->id_satuan }}</td>
        </tr>
    @endforeach
    </tbody>
</table>