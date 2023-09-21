@if ($history->isEmpty())
    <div class="alert alert-outline-warning">
        <p>Data Belum Ada</p>
    </div>
@endif
@foreach ($history as $d)
    <ul class="listview image-listview">
        <li>
            <div class="item">
                @php
                    $path = Storage::url('uploads/absensi/' . $d->foto_in);
                @endphp
                <img src="{{ url($path) }}" alt="image" class="image">
                <div class="in">
                    <div>
                        <b>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</b><br>
                        {{-- <small class="text-muted">{{ $d->jabatan }}</small> --}}
                    </div>
                    <span class="badge {{ $d->jam_masuk < '08.30' ? 'bg-success' : 'bg-danger' }}">
                        {{ $d->jam_masuk }}
                    </span>
                    <span class="badge bg-danger">{{ $d->jam_keluar }}</span>
                </div>
            </div>
        </li>
    </ul>
@endforeach
