{{-- @php
    dd($arrayExcel);
@endphp --}}
<table>
    <thead>
    <tr>
        <th>Kode Item</th>
        <th>Nama Item</th>
        <th>Nama Barang Asli</th>
        <th>Jenis</th>
        <th>Satuan</th>
        <th>Harga Level 1</th>
        <th>Harga Level 2</th>
        <th>Harga Level 3</th>
        <th>Harga Level 4</th>
    </tr>
    </thead>
    <tbody>
    @foreach($arrayExcel as $item)
        <tr>
            <td>{{ $item['kode_item'] }}</td>
            <td>{{ $item['nama_item'] }}</td>
            <td>{{ $item['keterangan'] }}</td>
            <td>{{ $item['barang_alias'] }}</td>
            <td>{{ $item['satuan'] }}</td>
            <td>{{ $item['harga1'] }}</td>
            <td>{{ $item['harga2'] }}</td>
            <td>{{ $item['harga3'] }}</td>
            <td>{{ $item['harga4'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
