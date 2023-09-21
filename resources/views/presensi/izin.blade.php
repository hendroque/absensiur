@extends('layouts.presensi')
@section('header')
    <div class="appHeader bg-success text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">Data Cuti</div>
        <div class="right"></div>
    </div>
@endsection

@section('content')
    <div class="row" style="margin-top:70px">
        <div class="col">
            @php
                $messagesuccess = Session::get('success');
                $messageerror = Session::get('error');
            @endphp
            @if (Session::get('success'))
                <div class="alert alert-success">
                    {{ $messagesuccess }}
                </div>
            @endif
            @if (Session::get('error'))
                <div class="alert alert-danger">
                    {{ $messageerror }}
                </div>
            @endif
        </div>
    </div>
    <div class="row">
        <div class="col">
            @foreach ($dataizin as $d)
                <ul class="listview image-listview">
                    <li>
                        <div class="item">
                            <div class="in">
                                <div>
                                    <b>
                                        {{ date('d-m-Y', strtotime($d->tgl_izin)) }}
                                        @if ($d->status == 'S1')
                                            <span class="badge bg-warning">Sakit</span>
                                        @elseif($d->status == 'C1')
                                            <span class="badge bg-warning">Cuti Khusus</span>
                                        @elseif($d->status == 'CH')
                                            <span class="badge bg-warning">Cuti Melahirkan</span>
                                        @elseif($d->status == 'CK')
                                            <span class="badge bg-warning">Cuti Kematian</span>
                                        @elseif($d->status == 'C')
                                            <span class="badge bg-warning">Cuti Tahunan</span>
                                        @elseif($d->status == 'P1')
                                            <span class="badge bg-warning">Cuti Tertulis</span>
                                        @endif
                                    </b> <br>
                                    <small class="text-muted">{{ $d->keterangan }}</small>
                                </div>
                                @if ($d->status_approved == 0)
                                    <span class="badge bg-warning">Waiting</span>
                                @elseif($d->status_approved == 1)
                                    <span class="badge bg-success">Approved</span>
                                @elseif($d->status_approved == 2)
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </div>
                        </div>
                    </li>
                </ul>
            @endforeach
        </div>
    </div>
    <div class="fab-button animate bottom-right dropdown" style="margin-bottom: 70px">
        <a href="/presensi/buatizin" class="fab"> 
            <ion-icon name="add-outline"></ion-icon>
        </a>
        <!--<a href="#" class="fab bg-success" data-toggle="dropdown">
            <ion-icon name="add-outline"></ion-icon>
        </a>
        <div class="dropdown-menu">
            <a class="dropdown-item bg-success" href="/presensi/izin">
                <ion-icon name="document-text-outline"></ion-icon>
                <p>Izin Absen</p>
            </a>

            <a class="dropdown-item bg-success" href="/presensi/izin">
                <ion-icon name="document-text-outline"></ion-icon>
                <p>Sakit</p>
            </a>

            <a class="dropdown-item bg-success" href="/presensi/izin">
                <ion-icon name="document-text-outline"></ion-icon>
                <p>Cuti</p>
            </a>
        </div>-->
    </div>
@endsection
