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
            padding: 8px;
            font-size: 10px
        }

        .tabelabsensi tr td {
            border: 1px solid #121212;
            padding: 5px;
            font-size: 12px;
        }

        .foto {
            width: 60px;
            height: 50px;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->

<body class="A4 landscape">
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
                        Laporan Rekap Pegawai <br>
                        Periode {{ $namabulan[$bulan] }} {{ $tahun }}<br>
                        Universitas Raharja <br>
                    </span>
                    <span><i>Jl. Jenderal Sudirman No.40, Cikokol, Kec. Tangerang, Kota Tangerang, Banten
                            15117</i></span>
                </td>
            </tr>
        </table>
        <table class="tabelabsensi">
            <tr>
                <th rowspan="2">NIP</th>
                <th rowspan="2">Nama</th>
                <th colspan="31">Tanggal</th>
                <th rowspan="2">Total Hadir</th>
                <th rowspan="2">Jumlah Izin Sakit</th>
                <th rowspan="2">Jumlah Izin Cuti</th>
            </tr>
            <tr>
                @for ($i = 1; $i <= 31; $i++)
                    <th>{{ $i }}</th>
                @endfor
            </tr>
            @foreach ($rekap as $d)
                <tr>
                    <td>{{ $d->nip }}</td>
                    <td>{{ $d->nama_lengkap }}</td>
                    <?php
                    $totalhadir = 0;
                    $totalCuti = 0; // Menyimpan jumlah cuti
                    $totalSakit = 0; // Menyimpan jumlah sakit
                    
                    ?>
                    @for ($i = 1; $i <= 31; $i++)
                        @php
                            $tgl = 'tgl_' . $i;
                            if (empty($d->$tgl)) {
                                $hadir = ['M', ''];
                                $status = ''; // Menyimpan status cuti/sakit
                            } else {
                                $hadir = explode('-', $d->$tgl);
                                $totalhadir += 1; // Menghitung total kehadiran
                            
                                if (in_array($hadir[0], ['C1', 'CH', 'CK', 'C', 'P1', 'S1'])) {
                                    if ($hadir[0] === 'S1') {
                                        $totalSakit += 1; // Menambah jumlah sakit
                                    } else {
                                        $totalCuti += 1; // Menambah jumlah cuti
                                    }
                                    $status = $hadir[0];
                                } else {
                                    $status = '';
                                }
                            }
                        @endphp
                        <td>
                            @if (empty($hadir[0]) || $hadir[0] === 'M')
                                {{ $hadir[0] }}
                            @else
                                @if ($status)
                                    <span>Status:
                                        @if (in_array($status, ['C1', 'CH', 'CK', 'C', 'P1']))
                                            Cuti
                                        @elseif ($status === 'S1')
                                            Sakit
                                        @endif
                                    </span><br>
                                @endif
                                <span class="{{ $hadir[0] === 'T1' ? 'merah' : '' }}"
                                    style="color: {{ $hadir[0] === 'T1' ? 'red' : '' }}">
                                    @if (
                                        $hadir[0] === 'C1' ||
                                            $hadir[0] === 'CH' ||
                                            $hadir[0] === 'CK' ||
                                            $hadir[0] === 'C' ||
                                            $hadir[0] === 'P1' ||
                                            $hadir[0] === 'S1')
                                        @if (in_array($status, ['C1', 'CH', 'CK', 'C', 'P1']))
                                            Cuti
                                        @elseif ($status === 'S1')
                                            Sakit
                                        @endif
                                    @else
                                        @if ($hadir[0] === 'M')
                                            {{ $hadir[0] }}
                                        @else
                                            @if ($hadir[0] > $d->jam_masuks)
                                                <span style="color: red">T1</span>
                                            @else
                                                H
                                            @endif
                                        @endif
                                    @endif
                                </span>
                            @endif
                        </td>
                    @endfor
                    <td>{{ $totalhadir }}</td>
                    <td>{{ $totalSakit }}</td>
                    <td>{{ $totalCuti }}</td>
                </tr>
            @endforeach
        </table>

        <table width="100%" style="margin-top: 200px">
            <tr>
                <td>

                </td>
                <td style="text-align: center">
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
