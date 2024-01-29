<table>
    <thead>
        <tr>
            <th width="3"><b>NO</b></th>
            <th width="14"><b>NAMA</b></th>
            <th width="14"><b>JENIS KELAMIN</b></th>
            <th width="14"><b>WHATSAPP</b></th>
            <th width="14"><b>EMAIL</b></th>
            <th width="14"><b>ALAMAT</b></th>
            <th width="14"><b>KOTA</b></th>
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
            <td valign="top">{{ genderResource($item->jenis_kelamin) }}</td>
            <td valign="top">{{ $item->phone }}</td>
            <td valign="top">{{ $item->email }}</td>
            <td valign="top">{{ $item->alamat }}</td>
            <td valign="top">{{ $item->kota->kota }}</td>
            <td valign="top">{{ statusTable($item->status, false) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
