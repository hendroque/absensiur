@extends('layouts.admin.tabler')
<link rel="stylesheet" href="{{ asset('assets/css/jam.css') }}">
@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <div class="page-pretitle">
                        Overview
                    </div>
                    <h2 class="page-title">
                        Dashboard
                    </h2>
                </div>
                <div class="col">
                    <div class="cointainer">
                        <div class="clock">
                            <span id="hours">00</span>
                            <span id="dot">:</span>
                            <span id="minutes">00</span>
                            <span id="dot">:</span>
                            <span id="seconds">00</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="row">
                    <div class="col-md-6 col-xl-5"> <!-- Lebar total 9 kolom untuk div scan -->
                        <div class="card card-sm" id="reader" width="10px"> <!-- Lebar div scan diatur di CSS -->
                            <!-- Isi konten div scan di sini -->
                        </div>
                        
                        <div>
                                <!--<label for="qr_code">Scan QR Code:</label>-->
                                <input type="hidden" id="result" name="result">
                                <!--<input type="text" id="qr_code" name="qr_code">-->
                                <!--<button id="submitBtn">Submit</button>-->
                        </div>
                        
                        {{-- FORM SCAN QR CODE 
                        <form action="{{ route('validasiqrcode') }}" method="POST"  id="form">
                        @csrf
                            <input type="hidden" name="qr_code" id="qr_code">
                        </form>--}}
                        <!--<div id="result"></div>-->
                    </div>
                    <div class="col-md-6 col-xl-6">
                        <!-- Lebar total 3 kolom untuk div leaderboard -->
                        <div class="card text-center" style="font-size: 24px; text-align: right;">Daftar Hadir</div>
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">Daftar Hadir Pegawai Pada
                                    <b>{{ date('l, d-m-Y', strtotime($hariini)) }}</b></h3>
                            </div>

                            <div class="card-body">
                                @foreach ($leaderboard as $d)
                                @php
                                    $path = Storage::url('uploads/pegawai/' . $d->foto);
                                @endphp
                                    <div class="row g-3">
                                        <div class="col-6">
                                            <div class="row g-3 align-items-center">
                                                <a href="#" class="col-auto">
                                                    <span class="avatar">
                                                        @if (empty($d->foto))
                                                            <img src="{{ asset('assets/img/no-photo.png') }}" class="avatar" alt="">
                                                        @else
                                                            <img src="{{ url($path) }}" alt="" class="avatar">
                                                        @endif
                                                        <span class="badge bg-green"></span>
                                                    </span>                                                    
                                                </a>
                                                <div class="col text-truncate">
                                                    <a class="text-reset d-block text-truncate">
                                                        <b>{{ $d->nama_lengkap }}</b><br>
                                                        <small class="text-muted">{{ $d->posisi }}</small></a>
                                                    <div class="row">
                                                        <div class="col">
                                                            <div class="text-muted text-truncate mt-n1">
                                                            @php
                                                                //Jam Ketika Absen
                                                                $jam_masuk = date('H:i', strtotime($d->jam_masuk));
                                                                //Jam Jadwal Absen
                                                                $jam_masuks = date('H:i', strtotime($d->jam_masuks));
                                                                
                                                                $jam_masukss = $d->tgl_presensi . ' ' . $jam_masuks;
                                                                $jam_presensi = $d->tgl_presensi . ' ' . $jam_masuk;
                                                                
                                                            @endphp
                                                                @if ($jam_masuk > $jam_masuks)
                                                                    @php
                                                                    @endphp
                                                                    <span style="color:red">{{ $jam_masuk }}</span>
                                                                @else
                                                                    <span
                                                                        style="color:greenyellow">{{ $jam_masuk }}</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="col">
                                                            <div class="text-muted text-truncate mt-n1">
                                                                <span style="color: {{ $d->jam_keluar ? 'red' : '' }}">{{ $d->jam_keluar }}</span>
                                                            </div>
                                                        </div>                                                        
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-body">
            <div class="container-xl">
                <div class="row">
                    <div class="col-md-6 col-xl-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span
                                            class="bg-success text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-fingerprint" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"
                                                fill="none" stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M18.9 7a8 8 0 0 1 1.1 5v1a6 6 0 0 0 .8 3"></path>
                                                <path d="M8 11a4 4 0 0 1 8 0v1a10 10 0 0 0 2 6"></path>
                                                <path d="M12 11v2a14 14 0 0 0 2.5 8"></path>
                                                <path d="M8 15a18 18 0 0 0 1.8 6"></path>
                                                <path d="M4.9 19a22 22 0 0 1 -.9 -7v-1a8 8 0 0 1 12 -6.95"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            {{ $rekappresensi->jmlHadir }}
                                        </div>
                                        <div class="text-muted">
                                            Pegawai Hadir
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span
                                            class="bg-info text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-file-text" width="24" height="24"
                                                viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M14 3v4a1 1 0 0 0 1 1h4"></path>
                                                <path
                                                    d="M17 21h-10a2 2 0 0 1 -2 -2v-14a2 2 0 0 1 2 -2h7l5 5v11a2 2 0 0 1 -2 2z">
                                                </path>
                                                <path d="M9 9l1 0"></path>
                                                <path d="M9 13l6 0"></path>
                                                <path d="M9 17l6 0"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            {{ $rekapizin->jmlizin != null ? $rekapizin->jmlizin : 0 }}
                                        </div>
                                        <div class="text-muted">
                                            Pegawai Cuti
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-xl-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span
                                            class="bg-warning text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-mood-sick" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M12 21a9 9 0 1 1 0 -18a9 9 0 0 1 0 18z"></path>
                                                <path d="M9 10h-.01"></path>
                                                <path d="M15 10h-.01"></path>
                                                <path d="M8 16l1 -1l1.5 1l1.5 -1l1.5 1l1.5 -1l1 1"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            {{ $rekapizin->jmlsakit != null ? $rekapizin->jmlsakit : 0 }}
                                        </div>
                                        <div class="text-muted">
                                            Pegawai Sakit
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-3">
                        <div class="card card-sm">
                            <div class="card-body">
                                <div class="row align-items-center">
                                    <div class="col-auto">
                                        <span
                                            class="bg-danger text-white avatar"><!-- Download SVG icon from http://tabler-icons.io/i/currency-dollar -->
                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-clock-exclamation" width="24"
                                                height="24" viewBox="0 0 24 24" stroke-width="2"
                                                stroke="currentColor" fill="none" stroke-linecap="round"
                                                stroke-linejoin="round">
                                                <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                <path d="M20.986 12.502a9 9 0 1 0 -5.973 7.98"></path>
                                                <path d="M12 7v5l3 3"></path>
                                                <path d="M19 16v3"></path>
                                                <path d="M19 22v.01"></path>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <div class="font-weight-medium">
                                            {{ $rekappresensi->jmlTerlambat != null ? $rekappresensi->jmlTerlambat : 0 }}
                                        </div>
                                        <div class="text-muted">
                                            Pegawai Terlambat
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    @endsection

    @push('myscript')
        <script>
            function updatetime() {
                var datetime = new Date();
                var hours = datetime.getHours();
                var minutes = datetime.getMinutes();
                var seconds = datetime.getSeconds();

                document.getElementById("hours").innerHTML = hours;
                document.getElementById("minutes").innerHTML = minutes;
                document.getElementById("seconds").innerHTML = seconds;
            }
            setInterval(updatetime, 1000);

            // Initial update outside setInterval
            updateClock();
        </script>
        <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
        
        <script>
            // Inisialisasi Html5QrcodeScanner
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader",
                { fps: 10, qrbox: { width: 250, height: 250 } },
                /* verbose= */ false
            );
    
            // Fungsi yang dipanggil ketika pemindaian berhasil
            function onScanSuccess(decodedText, decodedResult) {
                $('#result').val(decodedText);
                let id = decodedText;
                html5QrcodeScanner.clear().then(_ =>{
                // Kirim hasil pemindaian ke server menggunakan Ajax
                $.ajax({
                    url: "{{ route('validasiqrcode') }}",
                    type: "POST",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'qr_code': id
                    },
                    success: function(response) {
                        var status = response.split("|");
                        // Menampilkan pesan hasil validasi
                        if (status[0] == "success") {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: status[1],
                                icon: 'success',
                            });
                            // Refresh halaman setelah validasi sukses
                            setTimeout(function() {
                                location.reload();
                            }, 2000); // Delay 2000 milidetik (2 detik) sebelum merefresh halaman
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: status[1],
                                icon: 'error',
                            });
                            // Refresh halaman setelah validasi sukses
                            setTimeout(function() {
                                location.reload();
                            }, 2000); // Delay 2000 milidetik (2 detik) sebelum merefresh halaman
                        }
                    },
                    error: function(error) {
                        console.error(error);
                        // Handle kesalahan saat mengirim permintaan ke server
                        Swal.fire({
                            title: 'Error',
                            text: 'Terjadi kesalahan saat mengirim permintaan ke server.',
                            icon: 'error',
                        });
                        // Refresh halaman setelah validasi sukses
                        setTimeout(function() {
                                location.reload();
                            }, 2000); // Delay 2000 milidetik (2 detik) sebelum merefresh halaman
                    }
                });
                });
            }
            // Fungsi yang dipanggil ketika pemindaian gagal
            function onScanFailure(error) {
                // Handle scan failure, usually better to ignore and keep scanning.
                // for example:
                //console.warn(`Code scan error = ${error}`);
            }
    
            // Memulai pemindaian QR code
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        </script>
        <!--
        <script>
            // Inisialisasi scanner QR code
            let html5QrcodeScanner = new Html5QrcodeScanner(
                "reader", {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },
                /* verbose= */
                false
            );

            // Callback ketika pemindaian berhasil
            function onScanSuccess(decodedText, decodedResult) {
            // Tampilkan nilai QR code pada input text
            $("#qr_code").val(decodedText);

            }

            // Callback ketika pemindaian gagal
            function onScanFailure(error) {
            //console.warn(`Kesalahan dalam pemindaian kode QR = ${error}`);
            }
            // Render scanner QR code
            html5QrcodeScanner.render(onScanSuccess, onScanFailure);
        </script>
        
        
        <script>
                // Ketika tombol Submit di klik
                $("#submitBtn").on("click", function() {
                var qrCode = $("#qr_code").val();
                // Mengirimkan permintaan AJAX ke server
                $.ajax({
                    url: "{{ route('validasiqrcode') }}",
                    type: "POST",
                    data: {
                        '_token': "{{ csrf_token() }}",
                        'qr_code': qrCode
                    },
                    success: function(response) {
                        var status = response.split("|");
                        // Menampilkan pesan hasil validasi
                        if (status[0] == "success") {
                            Swal.fire({
                                title: 'Berhasil!',
                                text: status[1],
                                icon: 'success',
                            });
                            // Refresh halaman setelah validasi sukses
                            setTimeout(function() {
                                location.reload();
                            }, 2000); // Delay 2000 milidetik (2 detik) sebelum merefresh halaman
                        } else {
                            Swal.fire({
                                title: 'Error',
                                text: status[1],
                                icon: 'error',
                            });
                            // Refresh halaman setelah validasi sukses
                            setTimeout(function() {
                                location.reload();
                            }, 2000); // Delay 2000 milidetik (2 detik) sebelum merefresh halaman
                        }
                    },
                });
            });
        </script>
    -->
    @endpush
    @push('styles')
        <!-- Menambahkan CSS ke bagian head -->
        <style>
            /* CSS Anda yang sudah ada */

            /* Atur grid untuk leaderboard */
            .leaderboard {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                grid-gap: 10px;
                /* Jarak antar item */
            }

            /* Atur flexbox untuk item leaderboard */
            .item {
                display: flex;
                align-items: center;
                justify-content: space-between;
            }
        </style>
    @endpush
