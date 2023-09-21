<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>A4</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
        @page {
            size: A4
        }

        #title {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold
        }

        .tabeldatakaryawan {
            margin-top: 40px;
        }

        .tabeldatakaryawan,
        tr,
        td {
            padding: 3px;
        }

        .tabelabsensi {

            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .tabelabsensi tr th {
            border: 1px solid #121212;
            padding: 6px
        }

        .tabelabsensi tr td {
            border: 1px solid #121212;
            padding: 5px;
            font-size: 10px;
        }

        .foto {
            width: 60px;
            height: 50px;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4">
    @php
        function selisih($jam_masuk, $jam_keluar)
        {
            [$h, $m, $s] = explode(':', $jam_masuk);
            $dtAwal = mktime($h, $m, $s, '1', '1', '1');
            [$h, $m, $s] = explode(':', $jam_keluar);
            $dtAkhir = mktime($h, $m, $s, '1', '1', '1');
            $dtSelisih = $dtAkhir - $dtAwal;
            $totalmenit = $dtSelisih / 60;
            $jam = explode('.', $totalmenit / 60);
            $sisamenit = $totalmenit / 60 - $jam[0];
            $sisamenit2 = $sisamenit * 60;
            $jml_jam = $jam[0];
            return $jml_jam . ':' . round($sisamenit2);
        }
    @endphp
    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding-**mm" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10mm">

        <table style="width: 100%">
            <tr>
                <td style="width:30px">
                    <img src="{{ asset('assets/img/sample/photo/ur.png') }}" width="80" height="80"
                        alt="">
                </td>
                <td>
                    <span id="title">
                        Laporan Absensi Pegawai <br>
                        Periode {{ $namabulan[$bulan] }} {{ $tahun }}<br>
                        Universitas Raharja <br>
                    </span>
                    <span><i>Jl. Jenderal Sudirman No.40, Cikokol, Kec. Tangerang, Kota Tangerang, Banten
                            15117</i></span>
                </td>
            </tr>
        </table>
        <table class="tabeldatakaryawan">
            <tr>
                <td rowspan="6">
                    @php
                        $path = Storage::url('uploads/karyawan/' . $pegawai->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="" width="100px">
                </td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $pegawai->nip }}</td>
            </tr>
            <tr>
                <td>Nama Pegawai</td>
                <td>:</td>
                <td>{{ $pegawai->nama_lengkap }}</td>
            </tr>
            <tr>
                <td>Posisi</td>
                <td>:</td>
                <td>{{ $pegawai->posisi }}</td>
            </tr>
            <tr>
                <td>Bagian</td>
                <td>:</td>
                <td>{{ $pegawai->nama_dept }}</td>
            </tr>
            <tr>
                <td>No Handphone</td>
                <td>:</td>
                <td>{{ $pegawai->no_hp }}</td>
            </tr>
        </table>
        <table class="tabelabsensi">
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>NIP</th>
                <th>Jam Masuk</th>
                <th>Jam Pulang</th>
                <th>Keterangan</th>
                <th>Total Jam</th>
            </tr>
            @foreach ($presensi as $d)
                @php
                    $jamterlambat = selisih($d->jam_masuks, $d->jam_masuk);
                @endphp
                <tr>
                    <td>
                        {{ $loop->iteration }}
                    </td>
                    <td>
                        {{ date('d-m-Y', strtotime($d->tgl_presensi)) }}
                    </td>
                    <td>
                        {{ $d->nip }}
                    </td>
                    <td>
                        {{ $d->jam_masuk }}
                    </td>
                    <td>
                        {{ $d->jam_keluar != null ? $d->jam_keluar : 'Belum Absen' }}
                    </td>
                    <td>
                        @if ($d->jam_masuk > $d->jam_masuks)
                            Terlambat {{ $jamterlambat }}
                        @else
                            Tepat Waktu
                        @endif
                    </td>
                    <td>
                        @if ($d->jam_keluar != null)
                            @php
                                $jmljamkerja = selisih($d->jam_masuk, $d->jam_keluar);
                            @endphp
                        @else
                            @php
                                $jmljamkerja = 0;
                            @endphp
                        @endif
                        {{ $jmljamkerja }}
                    </td>
                </tr>
            @endforeach
        </table>

        <table width="100%" style="margin-top: 200px">
            <tr>
                <td colspan="2" style="text-align: right">
                    Tangerang, {{ date('d-m-Y') }}
                </td>
            </tr>
            <tr>
                <td style="text-align: center; vertical-align:bottom" height="100">
                    <u> Nama ...</u><br>
                    <i><b> Posisi</b></i>
                </td>
                <td style="text-align: center; vertical-align:bottom">
                    <u>Nama ...</u><br>
                    <i><b>Posisi</b></i>
                </td>
            </tr>
        </table>
    </section>

</body>

</html>
