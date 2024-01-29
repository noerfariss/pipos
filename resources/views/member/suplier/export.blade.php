<table>
    <thead>
        <tr>
            <th width="3"><b>NO</b></th>
            <th width="12"><b>KODE</b></th>
            <th width="14"><b>SUPLIER</b></th>
            <th width="70"><b>ALAMAT</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $item)
            <tr>
                <td valign="top" align="left">{{ $no++ }}</td>
                <td valign="top">{{ $item->kode_label }}</td>
                <td valign="top">{{ $item->suplier }}</td>
                <td valign="top">{{ $item->alamat }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
