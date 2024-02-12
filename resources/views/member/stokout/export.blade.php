<table>
    <thead>
        <tr>
            <th width="12"><b>TANGGAL</b></th>
            <th width="12"><b>JAM</b></th>
            <th width="12"><b>TIPE</b></th>
            <th width="12"><b>ALASAN</b></th>
            <th width="20"><b>KODE</b></th>
            <th width="25"><b>PRODUK</b></th>
            <th width="10"><b>QTY</b></th>
            <th width="60"><b>KETERANGAN</b></th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $item)
            <tr>
                <td valign="top">
                    {{ \Carbon\Carbon::parse($item->created_at)->timezone(session('zonawaktu'))->isoFormat('YYYY-MM-DD') }}
                </td>
                <td valign="top">
                    {{ \Carbon\Carbon::parse($item->created_at)->timezone(session('zonawaktu'))->isoFormat('HH:mm') }}
                </td>
                <td valign="top">{{ stokTipe($item->tipe) }}</td>
                <td valign="top">{{ $item->reason }}</td>
                <td valign="top">{{ $item->produk->barcode }}</td>
                <td valign="top">{{ $item->produk->produk }}</td>
                <td valign="top">{{ $item->qty }}</td>
                <td valign="top">{{ $item->keterangan }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
