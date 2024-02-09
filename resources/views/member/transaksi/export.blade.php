<table>
    <thead>
        <tr>
            <th width="5"><b>NO</b></th>
            <th width="14"><b>TANGGAL</b></th>
            <th width="7"><b>JAM</b></th>
            <th width="17"><b>NO. TRANSAKSI</b></th>
            <th width="35"><b>PRODUK</b></th>
            <th width="10"><b>HARGA</b></th>
            <th width="5"><b>QTY</b></th>
            <th width="11"><b>SUBTOTAL</b></th>
            <th width="30"><b>MEMBER</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $item)
            @foreach (json_decode($item->items) as $produk)
                <tr>
                    <td valign="top">{{ $no++ }}</td>
                    <td valign="top">
                        {{ \Carbon\Carbon::parse($item->created_at)->timezone(session('zonawaktu'))->isoFormat('YYYY-MM-DD') }}
                    </td>
                    <td valign="top">
                        {{ \Carbon\Carbon::parse($item->created_at)->timezone(session('zonawaktu'))->isoFormat('HH:mm') }}
                    </td>
                    <td valign="top">{{ $item->no_transaksi }}</td>
                    <td valign="top">{{ $produk->produk }}</td>
                    <td valign="top">{{ $produk->harga }}</td>
                    <td valign="top">{{ $produk->qty }}</td>
                    <td valign="top">{{ $produk->subtotal }}</td>
                    <td valign="top">{{ $item->member?->nama }}</td>
                </tr>
            @endforeach
        @endforeach
    </tbody>
</table>
