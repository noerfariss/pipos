<table>
    <thead>
        <tr>
            <th width="3"><b>NO</b></th>
            <th width="12"><b>NAMA</b></th>
            <th width="14"><b>EMAIL</b></th>
            <th width="14"><b>WHATSAPP</b></th>
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
                <td valign="top">{{ $item->nama }}</td>
                <td valign="top">{{ $item->email }}</td>
                <td valign="top">{{ $item->whatsapp }}</td>
                <td valign="top">{{ $item->status }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
