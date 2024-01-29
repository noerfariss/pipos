<table>
    <thead>
        <tr>
            <th width="3"><b>NO</b></th>
            <th width="14"><b>KATEGORI</b></th>
        </tr>
    </thead>
    <tbody>
        @php
            $no = 1;
        @endphp
        @foreach ($data as $item)
            <tr>
                <td valign="top" align="left">{{ $no++ }}</td>
                <td valign="top">{{ $item->unit }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
