@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-success text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="card w-100">
        <div class="row text-center" style="margin-top: 100px">
            <div class="col"> {!! QrCode::size(250)->generate($qr_codes_string) !!}
            </div>
        </div>

        <div class="row mt-3">
            <div class="col">
                <p>Nama Jam Kerja : {{ $jamkerja->nama_jam_kerja }}</p>
                <p>Awal Jam Masuk : {{ $jamkerja->awal_jam_masuk }}</p>
                <p>Jam Masuk : {{ $jamkerja->jam_masuks }}</p>
                <p>Akhir Jam Masuk : {{ $jamkerja->akhir_jam_masuk }}</p>
                <p>Jam Pulang : {{ $jamkerja->jam_pulangs }}</p>
            </div>
        </div>
    </div>
@endsection
