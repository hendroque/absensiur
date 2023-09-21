<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use App\Models\Pegawai;
use App\Models\Presensi;
use Redirect;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini = date("Y-m-d");
        $bulanini = date("m") * 1;
        $tahunini = date("Y");
        $nip = Auth::guard('pegawai')->user()->nip;
        $presensihariini = DB::table('presensi')->where('nip', $nip)->where('tgl_presensi', $hariini)->first();
        $historybulanini = DB::table('presensi')
            ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->orderBy('tgl_presensi')
            ->get();

        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nip) as jmlHadir, SUM(IF(jam_masuk > jam_masuks ,1,0)) as jmlTerlambat')
            ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahunini . '"')
            ->first();


        $leaderboard = DB::table('presensi')
            ->join('pegawai', 'presensi.nip', '=', 'pegawai.nip')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_masuk')
            ->get();
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $status_cuti = ['C1', 'CH', 'CK', 'C', 'P1'];
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('SUM(IF(status IN ("' . implode('","', $status_cuti) . '"), 1, 0)) as jmlizin,SUM(IF(status="S1",1,0)) as jmlsakit')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tgl_izin)="' . $bulanini . '"')
            ->whereRaw('YEAR(tgl_izin)="' . $tahunini . '"')
            ->where('status_approved', 1)
            ->first();
        return view('dashboard.dashboard', compact('presensihariini', 'historybulanini', 'namabulan', 'bulanini', 'tahunini', 'rekappresensi', 'leaderboard', 'rekapizin'));
    }

    public function dashboardadmin()
    {

        $hariini = date("Y-m-d");
        $tanggal_hari_ini = strftime('%A, %d-%m-%Y', strtotime($hariini));
        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nip) as jmlHadir, SUM(IF(jam_masuk > jam_masuks ,1,0)) as jmlTerlambat')
            ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('tgl_presensi', $hariini)
            ->first();

        $status_cuti = ['C1', 'CH', 'CK', 'C', 'P1'];
        $rekapizin = DB::table('pengajuan_izin')
            ->selectRaw('SUM(IF(status IN ("' . implode('","', $status_cuti) . '"), 1, 0)) as jmlizin,SUM(IF(status="S1",1,0)) as jmlsakit')
            ->where('tgl_izin', $hariini)
            ->where('status_approved', 1)
            ->first();
        $leaderboard = DB::table('presensi')
            ->join('pegawai', 'presensi.nip', '=', 'pegawai.nip')
            ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('tgl_presensi', $hariini)
            ->orderByDesc('jam_masuk')
            ->get();

        $pegawai = Pegawai::all();
        foreach ($pegawai as $p) {
            $tanggal = date('Ymd');
            $qr_code = $p->nip . $p->nama_lengkap . Str::random(20) . $tanggal;

            $p->update(['qr_code' => $qr_code]);
        }

        return view('dashboard.dashboardadmin', compact('rekappresensi', 'rekapizin', 'leaderboard', 'hariini'));
    }

    public function validasiQrcode(Request $request)
    {
        $hariini = date('Y-m-d');
        $qrCode = $request->qr_code;

        // Mengambil data 'qr_code' dari tabel 'pegawai' berdasarkan nilai QR code yang dipindai
        $pegawai = DB::table('pegawai')->where('qr_code', $qrCode)->first();
        // Periksa apakah data pegawai ditemukan berdasarkan QR code yang dipindai
        if ($pegawai) {
            // Verifikasi bahwa QR code terkait dengan NIP tertentu (gunakan mekanisme sesuai dengan format QR code)
            // Misalnya, jika QR code menyimpan informasi NIP dalam format tertentu:
            $nip = $pegawai->nip;
            // Lakukan verifikasi QR code dan NIP yang sesuai sesuai dengan format yang diinginkan
            $kode_dept = Pegawai::where('nip', $nip)->value('kode_dept');
            // Jika QR code sesuai dengan NIP tertentu, simpan data presensi
            $tgl_presensi = date('Y-m-d');
            $jam = date('H:i:s');
            $cek = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nip', $nip)->count();
            $jamkerja = DB::table('konf_jam_kerja')->where('nip', $nip)
                ->join('jam_kerja', 'konf_jam_kerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->first();

            if ($jamkerja == null) {
                $jamkerja = DB::table('konf_jk_dept_detail')
                    ->join('konf_jk_dept', 'konf_jk_dept_detail.kode_jk_dept', '=', 'konf_jk_dept.kode_jk_dept')
                    ->join('jam_kerja', 'konf_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                    ->where('kode_dept', $kode_dept)
                    ->first();
            }
            if ($cek > 0) {
                if ($jam < $jamkerja->jam_pulangs) {
                    echo "error|Maaf Belum Waktunya Pulang|out";
                } else {
                    $ket = "out";
                    $data_pulang = [
                        'jam_keluar' => $jam,
                    ];

                    $update = DB::table('presensi')->where('tgl_presensi', $tgl_presensi)->where('nip', $nip)->update($data_pulang);
                    if ($update) {
                        echo "success|Terimakasih, Hati-hati di Jalan!";
                    } else {
                        echo "error|Maaf Gagal Absen|out";
                    }
                }
            } else {
                if ($jam < $jamkerja->awal_jam_masuk) {
                    echo "error|Maaf, Belum Waktunya Absen|out";
                } else if ($jam > $jamkerja->akhir_jam_masuk) {
                    echo "error|Maaf, Waktu Absen Sudah Habis|out";
                } else {
                    $ket = "in";
                    $data = [
                        'nip' => $nip,
                        'tgl_presensi' => $tgl_presensi,
                        'jam_masuk' => $jam,
                        'kode_jam_kerja' => $jamkerja->kode_jam_kerja,
                        'status' => "Hadir",
                    ];
                    $simpan = DB::table('presensi')->insert($data);
                    if ($simpan) {
                        echo "success|Terimakasih, Selamat Bekerja|in";
                    } else {
                        echo "error|Maaf Gagal Absen|out";
                    }
                }
            }
        }
    }
}
