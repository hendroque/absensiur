@extends('layouts.presensi')
@section('content')
    <!-- App Capsule -->
    <div class="section" id="user-section">
        <div id="user-detail">
            <div class="avatar">
                @if (!empty(Auth::guard('pegawai')->user()->foto))
                    @php
                        $path = Storage::url('uploads/pegawai/' . Auth::guard('pegawai')->user()->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="avatar" class="imaged w64" style="height: 80px">
                @else
                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="imaged w64 rounded">
                @endif
            </div>
            <div id="user-info">
                <h2 id="user-name">{{ Auth::guard('pegawai')->user()->nama_lengkap }}</h2>
                <span id="user-role">{{ Auth::guard('pegawai')->user()->posisi }}</span>
            </div>
        </div>
    </div>

    <div class="section mt-1" id="menu-section">
        <div class="card">
            <div class="card-body text-center">
                <div class="list-menu">
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/editprofile" class="green" style="font-size: 40px;">
                                <ion-icon name="person-sharp"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Profil</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/presensi/izin" class="danger" style="font-size: 40px;">
                                <ion-icon name="calendar-number"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Cuti</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="/presensi/history" class="warning" style="font-size: 40px;">
                                <ion-icon name="document-text"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            <span class="text-center">Histori</span>
                        </div>
                    </div>
                    <div class="item-menu text-center">
                        <div class="menu-icon">
                            <a href="" class="orange" style="font-size: 40px;">
                                <ion-icon name="location"></ion-icon>
                            </a>
                        </div>
                        <div class="menu-name">
                            Lokasi
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="section mt-2" id="presence-section">
        <div class="todaypresence">
            <div class="row">
                <div class="col-6">
                    <div class="card gradasigreen">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-friends"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M7 5m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M5 22v-5l-1 -1v-4a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4l-1 1v5"></path>
                                        <path d="M17 5m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path d="M15 22v-4h-2l2 -6a1 1 0 0 1 1 -1h2a1 1 0 0 1 1 1l2 6h-2v4"></path>
                                    </svg>
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Masuk</h4>
                                    <span>{{ $presensihariini != null ? $presensihariini->jam_masuk : 'Belum Absen' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6">
                    <div class="card gradasired">
                        <div class="card-body">
                            <div class="presencecontent">
                                <div class="iconpresence">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-friends-off"
                                        width="24" height="24" viewBox="0 0 24 24" stroke-width="2"
                                        stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 5a2 2 0 0 0 2 2m2 -2a2 2 0 0 0 -2 -2"></path>
                                        <path d="M5 22v-5l-1 -1v-4a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v4l-1 1v5"></path>
                                        <path d="M17 5m-2 0a2 2 0 1 0 4 0a2 2 0 1 0 -4 0"></path>
                                        <path
                                            d="M15 22v-4h-2l1.254 -3.763m1.036 -2.942a1 1 0 0 1 .71 -.295h2a1 1 0 0 1 1 1l1.503 4.508m-1.503 2.492v3">
                                        </path>
                                        <path d="M3 3l18 18"></path>
                                    </svg>
                                </div>
                                <div class="presencedetail">
                                    <h4 class="presencetitle">Pulang</h4>
                                    <span>{{ $presensihariini != null && $presensihariini->jam_keluar != null ? $presensihariini->jam_keluar : 'Belum Absen' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="rekappresensi">
            <h3>Rekap Absen Bulan {{ $namabulan[$bulanini] }} Tahun {{ $tahunini }}</h3>
            <div class="row">
                <div class="col-3">
                    <div class="card" style="background-color : #4aff53">
                        <div class="card-body text-center" style="padding: 12px 10px !important; line-height: 0.8rem">
                            <span class="badge bg-danger"
                                style="position : absolute; top :3px; right: 10px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlHadir }}</span>
                            <ion-icon name="accessibility-outline" style="font-size: 1.6rem;"
                                class="text-warning mb-1"></ion-icon>
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500">Hadir</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card">
                        <div class="card-body text-center" style="padding: 12px 10px !important; line-height: 0.8rem ">
                            <span class="badge bg-danger"
                                style="position : absolute; top :3px; right: 10px; font-size:0.6rem; z-index:999">{{ $rekapizin->jmlsakit }}</span>
                            <ion-icon name="medkit-outline" style="font-size: 1.6rem;"
                                class="text-danger mb-1"></ion-icon>
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500">Sakit</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card" style="background-color : #fcfc6a">
                        <div class="card-body text-center" style="padding: 12px 10px !important; line-height: 0.8rem">
                            <span class="badge bg-danger"
                                style="position : absolute; top :3px; right: 10px; font-size:0.6rem; z-index:999">{{ $rekapizin->jmlizin }}</span>
                            <ion-icon name="newspaper-outline" style="font-size: 1.6rem;"
                                class="text-success mb-1"></ion-icon>
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500">Izin</span>
                        </div>
                    </div>
                </div>
                <div class="col-3">
                    <div class="card" style="background-color : #ff173e">
                        <div class="card-body text-center" style="padding: 12px 10px !important; line-height: 0.8rem">
                            <span class="badge bg-danger"
                                style="position : absolute; top :3px; right: 10px; font-size:0.6rem; z-index:999">{{ $rekappresensi->jmlTerlambat }}</span>
                            <ion-icon name="alarm-outline" style="font-size: 1.6rem;"
                                class="text-light mb-1 "></ion-icon>
                            <br>
                            <span style="font-size: 0.8rem; font-weight:500">Telat</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="presencetab mt-2">
            <div class="tab-pane fade show active" id="pilled" role="tabpanel">
                <ul class="nav nav-tabs style1" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
                            Bulan Ini
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
                            Leaderboard
                        </a>
                    </li>
                </ul>
            </div>
            <div class="tab-content mt-2" style="margin-bottom:100px;">
                <div class="tab-pane fade show active" id="home" role="tabpanel">
                    <!--
                            <ul class="listview image-listview">
                                @foreach ($historybulanini as $d)
    <li>
                                    <div class="item">
                                        <div class="icon-box bg-success">
                                            <ion-icon name="receipt-outline" role="img" class="md hydrated"
                                                aria-label="receipt outline"></ion-icon>
                                        </div>
                                        <div class="in">
                                            <div>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</div>
                                            <span class="badge badge-success">{{ $d->jam_masuk }}</span>
                                            <span class="badge badge-danger"> {{ $presensihariini != null && $d->jam_keluar != null ? $d->jam_keluar : 'Belum Absen' }}</span>
                                        </div>
                                    </div>
                                </li>
    @endforeach
                                    
                            </ul>
                            -->
                    <style>
                        .historycontent {
                            display: flex;
                        }

                        .datapresensi {
                            margin-left: 10px;
                        }
                    </style>
                    @foreach ($historybulanini as $d)
                        <div class="card">
                            <div class="card-body">
                                <div class="historycontent">
                                    <div class="iconpresensi">
                                        <ion-icon name="receipt-outline" style="font-size:40px"
                                            class="text-success"></ion-icon>
                                    </div>
                                    <div class="datapresensi">
                                        <h3 style="line-height: 3px">{{ $d->nama_jam_kerja }}</h3>
                                        <h4 style="margin:0px !important">{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}
                                        </h4>
                                        <span>
                                            {!! $d->jam_masuk != null
                                                ? date('H:i', strtotime($d->jam_masuk))
                                                : '<span class="text-danger">Belum absen</span>' !!}
                                        </span>
                                        <span>
                                            {!! $d->jam_keluar != null
                                                ? '-' . date('H:i', strtotime($d->jam_keluar))
                                                : '<span class="text-danger">- Belum absen</span>' !!}
                                        </span>
                                        <div id="keterangan">
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
                                                    $jmlterlambat = hitungjamterlambat($jam_masukss, $jam_presensi);
                                                @endphp
                                                <span class="danger">Terlambat {{ $jmlterlambat }}</span>
                                            @else
                                                <span style="color:greenyellow">Tepat Waktu</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel">
                    <ul class="listview image-listview">
                        @foreach ($leaderboard as $d)
                            <li>
                                <div class="item">
                                    <img src="assets/img/sample/avatar/avatar1.jpg" alt="image" class="image">
                                    <div class="in">
                                        <div>
                                            <b>{{ $d->nama_lengkap }}</b><br>
                                            <small class="text-muted">{{ $d->posisi }}</small>
                                        </div>
                                        <span
                                            class="badge {{ $d->jam_masuk < '08:30' ? 'bg-success' : 'bg-danger' }}">{{ $d->jam_masuk }}</span>
                                    </div>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!-- * App Capsule -->
@endsection
