<table>
    <thead>
        <tr>
            <th width="3"><b>NO</b></th>
            <th width="14"><b>KODE</b></th>
            <th width="14"><b>PRODUK</b></th>
            <th width="14"><b>KETERANGAN</b></th>
            <th width="14"><b>KATEGORI</b></th>
            <th width="14"><b>STOK</b></th>
            <th width="14"><b>STOK WARNING</b></th>
            <th width="14"><b>HARGA</b></th>
            <th width="14"><b>UNIT</b></th>
            <th width="14"><b>IS APP</b></th>
            <th width="14"><b>STATUS</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $item)
            <tr>
                <td valign="top" align="left">{{ $no++ }}</td>
                <td valign="top">{{ $item->barcode }}</td>
                <td valign="top">{{ $item->produk }}</td>
                <td valign="top">{{ $item->keterangan }}</td>
                <td valign="top">{{ $item->kategori->kategori }}</td>
                <td valign="top">{{ $item->stok }}</td>
                <td valign="top">{{ $item->stok_warning }}</td>
                <td valign="top">{{ $item->harga }}</td>
                <td valign="top">{{ $item->unit->unit }}</td>
                <td valign="top">{{ statusTable($item->is_app, false) }}</td>
                <td valign="top">{{ statusTable($item->status, false) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
