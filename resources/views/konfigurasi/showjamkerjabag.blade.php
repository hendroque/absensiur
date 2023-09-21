@extends('layouts.admin.tabler')
@section('content')
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <!-- Page pre-title -->
                    <h2 class="page-title">
                        View Set Jam Kerja Bagian
                    </h2>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container-xl">
            <form action="/konfigurasi/jamkerjabag/{{ $jamkerjabag->kode_jk_dept }}/update" method="POST">
                @csrf
                <div class="row">
                    <div class="col-12">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <select name="kode_dept" id="kode_dept" class="form-select" disabled>
                                        <option value="">Pilih Bagian</option>
                                        @foreach ($bagian as $d)
                                            <option {{ $jamkerjabag->kode_dept == $d->kode_dept ? 'selected' : '' }}
                                                value="{{ $d->kode_dept }}">{{ strtoupper($d->nama_dept) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Hari</th>
                                    <th>Jam Kerja</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jamkerjabag_detail as $s)
                                    <tr>
                                        <td>{{ $s->hari }}
                                            <input type="hidden" name="hari[]" value="{{ $s->hari }}">
                                        </td>
                                        <td>
                                            {{ $s->nama_jam_kerja }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                    <div class="col-6">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th colspan="6">Master Jam Kerja</th>
                                <tr>
                                    <th>Kode</th>
                                    <th>Nama</th>
                                    <th>Awal Masuk</th>
                                    <th>Jam Masuk</th>
                                    <th>Akhir Masuk</th>
                                    <th>Jam Pulang</th>
                                </tr>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jamkerja as $d)
                                    <tr>
                                        <td>{{ $d->kode_jam_kerja }}</td>
                                        <td>{{ $d->nama_jam_kerja }}</td>
                                        <td>{{ $d->awal_jam_masuk }}</td>
                                        <td>{{ $d->jam_masuks }}</td>
                                        <td>{{ $d->akhir_jam_masuk }}</td>
                                        <td>{{ $d->jam_pulangs }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
        </form>
    </div>
@endsection
