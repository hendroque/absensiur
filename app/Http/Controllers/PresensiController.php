<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Pegawai;
use App\Models\pengajuan_izin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function gethari()
    {
        $hari = date("D");
        switch ($hari) {
            case 'Sun':
                $hari_ini = "Minggu";
                break;

            case 'Mon':
                $hari_ini = "Senin";
                break;

            case 'Tue':
                $hari_ini = "Selasa";
                break;

            case 'Wed':
                $hari_ini = "Rabu";
                break;

            case 'Thu':
                $hari_ini = "Kamis";
                break;

            case 'Fri':
                $hari_ini = "Jumat";
                break;

            case 'Sat':
                $hari_ini = "Sabtu";
                break;

            default:
                $hari_ini = "Tidak di ketahui";
                break;
        }
        return $hari_ini;
    }

    public function create(Request $request)
    {

        $hariini = date("Y-m-d");
        $namahari = $this->gethari();
        $nip = Auth::guard('pegawai')->user()->nip;
        $kode_dept = Auth::guard('pegawai')->user()->kode_dept;
        $pegawai = Pegawai::where('nip', $nip)->pluck('qr_code');
        $qr_codes_string = $pegawai->implode(',');
        $cek = DB::table('presensi')->where('tgl_presensi', $hariini)->where('nip', $nip)->count();
        $jamkerja = DB::table('konf_jam_kerja')
            ->join('jam_kerja', 'konf_jam_kerja.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nip', $nip)->where('hari', $namahari)
            ->first();

        if ($jamkerja == null) {
            $jamkerja = DB::table('konf_jk_dept_detail')
                ->join('konf_jk_dept', 'konf_jk_dept_detail.kode_jk_dept', '=', 'konf_jk_dept.kode_jk_dept')
                ->join('jam_kerja', 'konf_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
                ->where('kode_dept', $kode_dept)->where('hari', $namahari)
                ->first();
        }
        if ($jamkerja == null) {
            return view('presensi.notifjadwal');
        } else {
            return view('presensi.create', compact('cek', 'qr_codes_string', 'jamkerja'));
        }
    }

    public function editprofile()
    {
        $nip = Auth::guard('pegawai')->user()->nip;
        $pegawai = DB::table('pegawai')->where('nip', $nip)->first();
        return view('presensi.editprofile', compact('pegawai'));
    }

    public  function updateprofile(Request $request)
    {
        $nip = Auth::guard('pegawai')->user()->nip;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $pegawai = DB::table('pegawai')->where('nip', $nip)->first();
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,jpg|max:1024'
        ]);
        if ($request->hasFile('foto')) {
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();
        } else {
            $foto = $pegawai->foto;
        }
        if (empty($request->password)) {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password,
                'foto' => $foto
            ];
        }
        $update = DB::table('pegawai')->where('nip', $nip)->update($data);
        if ($update) {
            if ($request->hasFile('foto')) {
                $folderPath = "public/uploads/pegawai/";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['error' => 'Data Gagal Di Update']);
        }
    }

    public function history()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.history', compact('namabulan'));
    }

    public function gethistory(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nip = Auth::guard('pegawai')->user()->nip;

        $history = DB::table('presensi')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->where('nip', $nip)
            ->orderBy('tgl_presensi')
            ->get();
        return view('presensi.gethistory', compact('history'));
    }

    public function Izin()
    {
        $nip = Auth::guard('pegawai')->user()->nip;
        $dataizin = DB::table('pengajuan_izin')->where('nip', $nip)->get();
        return view('presensi.izin', compact('dataizin'));
    }
    public function buatizin()
    {
        return view('presensi.buatizin');
    }
    public function storeizin(Request $request)
    {
        $nip = Auth::guard('pegawai')->user()->nip;
        $tgl_izin = $request->tgl_izin;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nip' => $nip,
            'tgl_izin' => $tgl_izin,
            'status' => $status,
            'keterangan' => $keterangan,
        ];

        $simpan = DB::table('pengajuan_izin')->insert($data);

        if ($simpan) {
            return redirect('/presensi/izin')->with(['success' => 'Data Cuti Berhasil Dikirim']);
        } else {
            return redirect('/presensi/izin')->with(['error' => 'Data Cuti Gagal Dikirim']);
        }
    }

    public function monitoring()
    {
        return view('presensi.monitoring');
    }

    public function getabsensi(Request $request)
    {
        $tanggal = $request->tanggal;
        $presensi = DB::table('presensi')
            ->select('presensi.*', 'nama_lengkap', 'nama_dept', 'jam_masuks', 'nama_jam_kerja', 'jam_masuks', 'jam_pulangs')
            ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->join('pegawai', 'presensi.nip', '=', 'pegawai.nip')
            ->join('bagian', 'pegawai.kode_dept', '=', 'bagian.kode_dept')
            ->where('tgl_presensi', $tanggal)
            ->get();

        return view('presensi.getabsensi', compact('presensi'));
    }

    public function laporan()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $pegawai = DB::table('pegawai')->orderBy('nama_lengkap')->get();
        return view('presensi.laporan', compact('namabulan', 'pegawai'));
    }

    public function cetaklaporan(Request $request)
    {
        $nip = $request->nip;
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $pegawai = DB::table('pegawai')->where('nip', $nip)
            ->join('bagian', 'pegawai.kode_dept', '=', 'bagian.kode_dept')
            ->first();

        $presensi = DB::table('presensi')
            ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where('nip', $nip)
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->orderBy('tgl_presensi')
            ->get();
        return view('presensi.cetaklaporan', compact('bulan', 'tahun', 'namabulan', 'pegawai', 'presensi'));
    }

    public function rekap()
    {
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        return view('presensi.rekap', compact('namabulan'));
    }
    public function cetakrekap(Request $request)
    {
        $bulan = $request->bulan;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        $tahun = $request->tahun;
        $rekap = DB::table('presensi')
            ->selectRaw('presensi.nip,nama_lengkap, jam_masuks,
        MAX(IF(DAY(tgl_presensi) = 1,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_1,
        MAX(IF(DAY(tgl_presensi) = 2,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_2,
        MAX(IF(DAY(tgl_presensi) = 3,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_3,
        MAX(IF(DAY(tgl_presensi) = 4,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_4,
        MAX(IF(DAY(tgl_presensi) = 5,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_5,
        MAX(IF(DAY(tgl_presensi) = 6,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_6,
        MAX(IF(DAY(tgl_presensi) = 7,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_7,
        MAX(IF(DAY(tgl_presensi) = 8,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_8,
        MAX(IF(DAY(tgl_presensi) = 9,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_9,
        MAX(IF(DAY(tgl_presensi) = 10,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_10,
        MAX(IF(DAY(tgl_presensi) = 11,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_11,
        MAX(IF(DAY(tgl_presensi) = 12,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_12,
        MAX(IF(DAY(tgl_presensi) = 13,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_13,
        MAX(IF(DAY(tgl_presensi) = 14,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_14,
        MAX(IF(DAY(tgl_presensi) = 15,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_15,
        MAX(IF(DAY(tgl_presensi) = 16,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_16,
        MAX(IF(DAY(tgl_presensi) = 17,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_17,
        MAX(IF(DAY(tgl_presensi) = 18,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_18,
        MAX(IF(DAY(tgl_presensi) = 19,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_19,
        MAX(IF(DAY(tgl_presensi) = 20,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_20,
        MAX(IF(DAY(tgl_presensi) = 21,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_21,
        MAX(IF(DAY(tgl_presensi) = 22,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_22,
        MAX(IF(DAY(tgl_presensi) = 23,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_23,
        MAX(IF(DAY(tgl_presensi) = 24,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_24,
        MAX(IF(DAY(tgl_presensi) = 25,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_25,
        MAX(IF(DAY(tgl_presensi) = 26,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_26,
        MAX(IF(DAY(tgl_presensi) = 27,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_27,
        MAX(IF(DAY(tgl_presensi) = 28,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_28,
        MAX(IF(DAY(tgl_presensi) = 29,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_29,
        MAX(IF(DAY(tgl_presensi) = 30,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_30,
        MAX(IF(DAY(tgl_presensi) = 31,CONCAT(jam_masuk,"-",IFNULL(jam_keluar,"00:00:00")),"")) as tgl_31',)
            ->join('pegawai', 'presensi.nip', '=', 'pegawai.nip')
            ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->whereRaw('MONTH(tgl_presensi)="' . $bulan . '"')
            ->whereRaw('YEAR(tgl_presensi)="' . $tahun . '"')
            ->groupByRaw('presensi.nip,nama_lengkap,jam_masuks')
            ->get();

        $izin = DB::table('pengajuan_izin')
            ->selectRaw('nip,
    SUM(CASE WHEN status = "S1" AND status_approved = 1 THEN 1 ELSE 0 END) as jumlah_sakit,
    SUM(CASE WHEN status IN ("C1", "CH", "CK", "C", "P1") AND status_approved = 1 THEN 1 ELSE 0 END) as jumlah_cuti,
    GROUP_CONCAT(CASE WHEN status IN ("C1", "CH", "CK", "C", "P1") AND status_approved = 1 THEN status ELSE NULL END) as status_cuti')
            ->whereRaw('MONTH(tgl_izin) = ?', [$bulan])
            ->whereRaw('YEAR(tgl_izin) = ?', [$tahun])
            ->where('status_approved', 1)
            ->groupBy('nip')
            ->get();

        foreach ($rekap as $pegawai) {
            $izinPegawai = $izin->where('nip', $pegawai->nip)->first();
            if ($izinPegawai) {
                $pegawai->jumlah_sakit = $izinPegawai->jumlah_sakit;
                $pegawai->jumlah_cuti = $izinPegawai->jumlah_cuti;
                $pegawai->status_cuti = $izinPegawai->status_cuti;
            } else {
                $pegawai->jumlah_sakit = 0;
                $pegawai->jumlah_cuti = 0;
                $pegawai->status_cuti = null;
            }
        }
        return view('presensi.cetakrekap', compact('bulan', 'tahun', 'namabulan', 'rekap'));
    }

    public function cetakrekapbulan(Request $request)
    {
        $bulan_awal = $request->bulan_awal;
        $bulan_akhir = $request->bulan_akhir;
        $tahun = $request->tahun;
        $bulan = $request->bulan;
        $namabulan = ["", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
        // Validasi rentang bulan, pastikan bulan_awal lebih kecil atau sama dengan bulan_akhir
        if ($bulan_awal > $bulan_akhir) {
            return redirect()->route('rekap-absensi')->with('error', 'Rentang bulan tidak valid.');
        }
        $rekapbulan = DB::table('presensi')
            ->select('pegawai.nip', 'pegawai.nama_lengkap', DB::raw('SUM(CASE WHEN presensi.status = "Hadir" THEN 1 ELSE 0 END) as total_hadir'))
            ->join('pegawai', 'presensi.nip', '=', 'pegawai.nip')
            ->leftjoin('jam_kerja', 'presensi.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
            ->where(function ($query) use ($bulan_awal, $bulan_akhir, $tahun) {
                $query->where(function ($q) use ($bulan_awal, $tahun) {
                    $q->whereYear('tgl_presensi', '=', $tahun)
                        ->whereMonth('tgl_presensi', '>=', $bulan_awal);
                })->orWhere(function ($q) use ($bulan_akhir, $tahun) {
                    $q->whereYear('tgl_presensi', '=', $tahun)
                        ->whereMonth('tgl_presensi', '<=', $bulan_akhir);
                });
            })
            ->groupBy('pegawai.nip', 'pegawai.nama_lengkap')
            ->get();

        $total_tidak_hadir_tanpa_alasan = DB::table('pegawai')
            ->leftJoin('presensi', function ($join) use ($bulan_awal, $bulan_akhir, $tahun) {
                $join->on('pegawai.nip', '=', 'presensi.nip')
                    ->where(function ($query) use ($bulan_awal, $tahun) {
                        $query->whereRaw('YEAR(presensi.tgl_presensi) = ?', [$tahun])
                            ->whereRaw('MONTH(presensi.tgl_presensi) >= ?', [$bulan_awal]);
                    })->orWhere(function ($query) use ($bulan_akhir, $tahun) {
                        $query->whereRaw('YEAR(presensi.tgl_presensi) = ?', [$tahun])
                            ->whereRaw('MONTH(presensi.tgl_presensi) <= ?', [$bulan_akhir]);
                    });
            })
            ->whereNull('presensi.nip')
            ->count();


        $total_tidak_hadir_tanpa_alasan_value = $total_tidak_hadir_tanpa_alasan;

        $izin = DB::table('pengajuan_izin')
            ->selectRaw('nip,
            SUM(CASE WHEN status = "S1" AND status_approved = 1 THEN 1 ELSE 0 END) as jumlah_sakit,
            SUM(CASE WHEN status = "CB" AND status_approved = 1 THEN 1 ELSE 0 END) as jumlah_CB,
            SUM(CASE WHEN status = "CH2" AND status_approved = 1 THEN 1 ELSE 0 END) as jumlah_CH2,
            SUM(CASE WHEN status = "P1" AND status_approved = 1 THEN 1 ELSE 0 END) as jumlah_P1,
            SUM(CASE WHEN status = "P2" AND status_approved = 1 THEN 1 ELSE 0 END) as jumlah_P2,
            SUM(CASE WHEN status = "CK" AND status_approved = 1 THEN 1 ELSE 0 END) as jumlah_CK,
            SUM(CASE WHEN status = "C" AND status_approved = 1 THEN 1 ELSE 0 END) as jumlah_C,
            SUM(CASE WHEN status = "C1" AND status_approved = 1 THEN 1 ELSE 0 END) as jumlah_C1,
            GROUP_CONCAT(CASE WHEN status IN ("CB", "CH2", "P1", "P2", "CK", "C", "C1") AND status_approved = 1 THEN status ELSE NULL END) as status_cuti')
            ->where(function ($query) use ($bulan_awal, $bulan_akhir, $tahun) {
                $query->where(function ($q) use ($bulan_awal, $tahun) {
                    $q->whereRaw('YEAR(tgl_izin) = ?', [$tahun])
                        ->whereRaw('MONTH(tgl_izin) >= ?', [$bulan_awal]);
                })->orWhere(function ($q) use ($bulan_akhir, $tahun) {
                    $q->whereRaw('YEAR(tgl_izin) = ?', [$tahun])
                        ->whereRaw('MONTH(tgl_izin) <= ?', [$bulan_akhir]);
                });
            })
            ->where('status_approved', 1)
            ->groupBy('nip')
            ->get();


        $rekapbulan->transform(function ($item) use ($izin) {
            $izinPegawai = $izin->where('nip', $item->nip)->first();

            $item->jumlah_sakit = $izinPegawai ? $izinPegawai->jumlah_sakit : 0;
            $item->jumlah_CB = $izinPegawai ? $izinPegawai->jumlah_CB : 0;
            $item->jumlah_CH2 = $izinPegawai ? $izinPegawai->jumlah_CH2 : 0;
            $item->jumlah_P1 = $izinPegawai ? $izinPegawai->jumlah_P1 : 0;
            $item->jumlah_P2 = $izinPegawai ? $izinPegawai->jumlah_P2 : 0;
            $item->jumlah_CK = $izinPegawai ? $izinPegawai->jumlah_CK : 0;
            $item->jumlah_C = $izinPegawai ? $izinPegawai->jumlah_C : 0;
            $item->jumlah_C1 = $izinPegawai ? $izinPegawai->jumlah_C1 : 0;

            return $item;
        });
        $total_sakit = $rekapbulan->sum('jumlah_sakit');
        $total_CB = $rekapbulan->sum('jumlah_CB');
        $total_CH2 = $rekapbulan->sum('jumlah_CH2');
        $total_P1 = $rekapbulan->sum('jumlah_P1');
        $total_P2 = $rekapbulan->sum('jumlah_P2');
        $total_CK = $rekapbulan->sum('jumlah_CK');
        $total_C = $rekapbulan->sum('jumlah_C');
        $total_C1 = $rekapbulan->sum('jumlah_C1');
        return view('presensi.cetakrekapbulan', compact(
            'bulan_awal',
            'bulan_akhir',
            'bulan',
            'tahun',
            'namabulan',
            'rekapbulan',
            'total_tidak_hadir_tanpa_alasan_value',
            'total_sakit',
            'total_CB',
            'total_CH2',
            'total_P1',
            'total_P2',
            'total_CK',
            'total_C',
            'total_C1'
        ));
    }

    public function izinsakit(Request $request)
    {

        $query = pengajuan_izin::query();
        $query->select('id', 'tgl_izin', 'pengajuan_izin.nip', 'nama_lengkap', 'posisi', 'status', 'status_approved', 'keterangan');
        $query->join('pegawai', 'pengajuan_izin.nip', '=', 'pegawai.nip');
        if (!empty($request->dari) && !empty($request->sampai)) {
            $query->whereBetween('tgl_izin', [$request->dari, $request->sampai]);
        }
        if (!empty($request->nip)) {
            $query->where('pengajuan_izin.nip', $request->nip);
        }
        if (!empty($request->nama_lengkap)) {
            $query->where('nama_lengkap', 'like', '%' . $request->nama_lengkap . '%');
        }
        if ($request->status_approved === '0' || $request->status_approved === '1' || $request->status_approved === '2') {
            $query->where('status_approved', $request->status_approved);
        }
        $query->orderBy('tgl_izin', 'desc');
        $izinsakit = $query->paginate(10);
        $izinsakit->appends($request->all());
        return view('presensi.izinsakit', compact('izinsakit'));
    }

    public function approveizinsakit(Request $request)
    {
        $status_approved = $request->status_approved;
        $id_izinsakit_form = $request->id_izinsakit_form;

        $update = DB::table('pengajuan_izin')->where('id', $id_izinsakit_form)->update([
            'status_approved' => $status_approved
        ]);
        if ($update) {
            return redirect()->back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return redirect()->back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function batalkanizinsakit($id)
    {
        $update = DB::table('pengajuan_izin')->where('id', $id)->update([
            'status_approved' => 0
        ]);
        if ($update) {
            return redirect()->back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return redirect()->back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function cekpengajuanizin(Request $request)
    {
        $tgl_izin = $request->tgl_izin;
        $nip = Auth::guard('pegawai')->user()->nip;

        $cek = DB::table('pengajuan_izin')->where('nip', $nip)->where('tgl_izin', $tgl_izin)->count();
        return $cek;
    }
}
