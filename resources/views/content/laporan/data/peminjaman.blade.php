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

<h1 class="text-center mb-5"><strong>Daftar Peminjaman</strong></h1>
<table class="table align-middle text-center">
    <thead>
        <tr>
            <th>No.</th>
            <th>Nama Peminjam</th>
            <th>Peminjaman</th>
            <th>Pengembalian</th>
            <th>Keterangan</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
        </tr>
    </thead>
    <tbody class="table-group-divider">
        @php
            $data_count = count($data);
        @endphp
        @for ($idx = 1; $idx <= $data_count; ++$idx)
        @php
            $v = $data[$idx - 1];
            $v_barang = $v['barang'];
            $v_barang_count = count($v_barang);
        @endphp
        <tr>
            <td rowspan="{{ $v_barang_count }}">{{ $idx }}</td>
            <td rowspan="{{ $v_barang_count }}">{{ $v['peminjam'] }}</td>
            <td rowspan="{{ $v_barang_count }}">{{ $v['tanggal_peminjaman'] }}</td>
            <td rowspan="{{ $v_barang_count }}">{{ $v['tanggal_pengembalian'] }}</td>
            <td rowspan="{{ $v_barang_count }}">{{ $v['keterangan'] }}</td>
            <td>{{ $v_barang[0]['nama_barang'] }}</td>
            <td>{{ $v_barang[0]['jumlah'] }}</td>
        </tr>
        @for ($vidx = 1; $vidx < $v_barang_count; ++$vidx)
        <tr>
            <td>{{ $v_barang[$vidx]['nama_barang'] }}</td>
            <td>{{ $v_barang[$vidx]['jumlah'] }}</td>
        </tr>
        @endfor
        @endfor
    </tbody>
</table>
