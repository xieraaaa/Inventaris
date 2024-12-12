<style>
    table,
    th,
    td {
        border: 1px solid black !important;
    }

    /* td {
        text-align: center !important;
        vertical-align: middle !important;
    } */
</style>

<h1 class="text-center mb-5"><strong>Daftar Pemindahan</strong></h1>
<table class="table align-middle text-center">
    <thead>
        <tr>
            <th>No.</th>
            <th>Tanggal</th>
            <th>Asal</th>
            <th>Tujuan</th>
            <th>Keterangan</th>
            <th>Nama Barang</th>
            <!-- <th>Jumlah</th> -->
        </tr>
    </thead>
    <tbody class="table-group-divider">
        @php
            $data_count = count($data);
        @endphp
        @for ($idx = 0; $idx < $data_count; ++$idx)
        @php
            $v = $data[$idx];
            $v_barang = $v['barang'];
            $v_barang_count = count($v_barang);
        @endphp
        <tr>
            <td rowspan="{{ $v_barang_count }}">{{ $idx + 1 }}</td>
            <td rowspan="{{ $v_barang_count }}">{{ $v['tanggal'] }}</td>
            <td rowspan="{{ $v_barang_count }}">{{ $v['asal'] }}</td>
            <td rowspan="{{ $v_barang_count }}">{{ $v['tujuan'] }}</td>
            <td rowspan="{{ $v_barang_count }}">{{ $v['keterangan'] }}</td>
            <td>{{ $v_barang[0]['nama_barang'] }}</td>
        </tr>
        @for ($vidx = 1; $vidx < $v_barang_count; ++$vidx)
        <tr>
            <td>{{ $v_barang[$vidx]['nama_barang'] }}</td>
        </tr>
        @endfor
        @endfor
    </tbody>
</table>
