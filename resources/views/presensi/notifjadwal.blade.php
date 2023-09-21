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
    <div class="container mt-5">
        <div class="row" style="margin-top: 60px">
            <div class="col-xl-10 col-lg-8 col-md-10 mx-auto text-center">
                <div class="alert alert-warning" role="alert">
                    <p><strong>Anda Belum Memiliki Jadwal Pada Hari Ini, Silahkan Hub HRD!</strong></p>
                </div>
            </div>
        </div>
    </div>
@endsection
