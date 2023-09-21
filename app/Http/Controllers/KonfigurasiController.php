<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use App\Models\Setjamkerja;
use App\Models\Setjamkerjabag;
class KonfigurasiController extends Controller
{
    public function jamkerja()
    {
        $jam_kerja = DB::table('jam_kerja')->orderBy('kode_jam_kerja')->get();
        return view('konfigurasi.jamkerja', compact('jam_kerja'));
    }
    public function storejamkerja(Request $request){
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuks = $request->jam_masuks;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulangs = $request->jam_pulangs;

        $data = [
            'kode_jam_kerja' => $kode_jam_kerja,
            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuks' => $jam_masuks,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'jam_pulangs' => $jam_pulangs
        ];
        try {
            DB::table('jam_kerja')->insert($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e){

            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function editjamkerja(Request $request){
        $kode_jam_kerja = $request->kode_jam_kerja;
        $jam_kerja = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->first();
        return view('konfigurasi.editjamkerja', compact('jam_kerja'));

    }

    public function updatejamkerja(Request $request){
        $kode_jam_kerja = $request->kode_jam_kerja;
        $nama_jam_kerja = $request->nama_jam_kerja;
        $awal_jam_masuk = $request->awal_jam_masuk;
        $jam_masuks = $request->jam_masuks;
        $akhir_jam_masuk = $request->akhir_jam_masuk;
        $jam_pulangs = $request->jam_pulangs;

        $data = [
            'nama_jam_kerja' => $nama_jam_kerja,
            'awal_jam_masuk' => $awal_jam_masuk,
            'jam_masuks' => $jam_masuks,
            'akhir_jam_masuk' => $akhir_jam_masuk,
            'jam_pulangs' => $jam_pulangs
        ];
        try {
            DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->update($data);
            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e){
            dd($data);
            //return Redirect::back()->with(['warning' => 'Data Gagal Diupdate']);
        }
    }
    public function deletejamkerja($kode_jam_kerja){
        $hapus = DB::table('jam_kerja')->where('kode_jam_kerja', $kode_jam_kerja)->delete();
        if($hapus){
            return Redirect::back()->with(['success'=>'Data Berhasil Dihapus']);
        } else{
            return Redirect::back()->with(['warning'=>'Data Tidak Berhasil Dihapus']);
        }
    }

    public function setjamkerja($nip){

        $pegawai = DB::table('pegawai')->where('nip', $nip)->first();
        $jamkerja = DB::table('jam_kerja')->orderBy('nama_jam_kerja')->get();
        $cekjamkerja = DB::table('konf_jam_kerja')->where('nip', $nip)->count();
        //dd($cekjamkerja);
        if($cekjamkerja > 0){
            $setjamkerja = DB::table('konf_jam_kerja')->where('nip', $nip)->get();
            return view('konfigurasi.editsetjamkerja', compact('pegawai','jamkerja', 'setjamkerja'));
        } else {
            return view('konfigurasi.setjamkerja', compact('pegawai','jamkerja'));
        }
    }

    public function storesetjamkerja(Request $request){
        $nip = $request->nip;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja; 
        
        for ($i = 0; $i < count($hari); $i++){
            $data[] = [
                'nip' => $nip,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i]
            ];
        }
        
        try{
            Setjamkerja::insert($data);
            return redirect('/pegawai')->with(['success'=>'Jam Kerja Berhasil Disimpan']);
        } catch(\Throwable $th){
            return redirect('/pegawai')->with(['warning'=>'Jam Kerja Gagal Disimpan']);
            //dd($th);
        }
    }
    public function updatesetjamkerja(Request $request){
        $nip = $request->nip;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja; 
        
        for ($i = 0; $i < count($hari); $i++){
            $data[] = [
                'nip' => $nip,
                'hari' => $hari[$i],
                'kode_jam_kerja' => $kode_jam_kerja[$i]
            ];
        }
        DB::beginTransaction();
        try{
            DB::table('konf_jam_kerja')->where('nip', $nip)->delete();
            Setjamkerja::insert($data);
            DB::commit();
            return redirect('/pegawai')->with(['success'=>'Jam Kerja Berhasil Disimpan']);
        } catch(\Throwable $th){
            DB::rollback();
            return redirect('/pegawai')->with(['warning'=>'Jam Kerja Gagal Disimpan']);
            //dd($th);
        }
    }

    public function jamkerjabag(){
        $jamkerjabag = DB::table('konf_jk_dept')
        ->join('bagian', 'konf_jk_dept.kode_dept', '=', 'bagian.kode_dept')
        ->get();
        return view('konfigurasi.jamkerjabag', compact('jamkerjabag'));
    }

    public function createjamkerjabag(){
        $jamkerja = DB::table('jam_kerja')->orderBy('nama_jam_kerja')->get();
        $bagian = DB::table('bagian')->get();
        return view('konfigurasi.createjamkerjabag',compact('jamkerja','bagian'));
    }

    public function storejamkerjabag(Request $request){
        $kode_dept = $request->kode_dept;
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja; 
        $kode_jk_dept = "J" . $kode_dept;

        DB::beginTransaction();
        try{
            DB::table('konf_jk_dept')->insert([
                'kode_jk_dept' => $kode_jk_dept,
                'kode_dept' => $kode_dept
            ]);
            for ($i = 0; $i < count($hari); $i++){
                $data[] = [
                    'kode_jk_dept' => $kode_jk_dept,
                    'hari' => $hari[$i],
                    'kode_jam_kerja' => $kode_jam_kerja[$i]
                ];
            }
            Setjamkerjabag::insert($data);
            DB::commit();
            return redirect('/konfigurasi/jamkerjabag')->with(['success'=>'Jam Kerja Bagian Berhasil Disimpan']);
        } catch(\Exception $e){
            DB::rollback();
            return redirect('/konfigurasi/jamkerjabag')->with(['warning'=>'Jam Kerja Bagian Gagal Disimpan']);
        }
    }

    public function editjamkerjabag($kode_jk_dept){
        $jamkerja = DB::table('jam_kerja')->orderBy('nama_jam_kerja')->get();
        $bagian = DB::table('bagian')->get();
        $jamkerjabag = DB::table('konf_jk_dept')->where('kode_jk_dept', $kode_jk_dept)->first();
        $jamkerjabag_detail = DB::table('konf_jk_dept_detail')->where('kode_jk_dept', $kode_jk_dept)->get();
        return view('konfigurasi.editjamkerjabag',compact('jamkerja','bagian','jamkerjabag','jamkerjabag_detail'));
    }

    public function updatejamkerjabag($kode_jk_dept, Request $request){
        $hari = $request->hari;
        $kode_jam_kerja = $request->kode_jam_kerja; 

        DB::beginTransaction();
        try{
            //hapus  data jam kerja bagian sebelumnya
            DB::table('konf_jk_dept_detail')->where('kode_jk_dept', $kode_jk_dept)->delete();
            
            for ($i = 0; $i < count($hari); $i++){
                $data[] = [
                    'kode_jk_dept' => $kode_jk_dept,
                    'hari' => $hari[$i],
                    'kode_jam_kerja' => $kode_jam_kerja[$i]
                ];
            }
            Setjamkerjabag::insert($data);
            DB::commit();
            return redirect('/konfigurasi/jamkerjabag')->with(['success'=>'Jam Kerja Bagian Berhasil Disimpan']);
        } catch(\Exception $e){
            DB::rollback();
            return redirect('/konfigurasi/jamkerjabag')->with(['warning'=>'Jam Kerja Bagian Gagal Disimpan']);
        }
    }

    public function showjamkerjabag($kode_jk_dept){
        $jamkerja = DB::table('jam_kerja')->orderBy('nama_jam_kerja')->get();
        $bagian = DB::table('bagian')->get();
        $jamkerjabag = DB::table('konf_jk_dept')->where('kode_jk_dept', $kode_jk_dept)->first();
        $jamkerjabag_detail = DB::table('konf_jk_dept_detail')
        ->join('jam_kerja', 'konf_jk_dept_detail.kode_jam_kerja', '=', 'jam_kerja.kode_jam_kerja')
        ->where('kode_jk_dept', $kode_jk_dept)->get();
        return view('konfigurasi.showjamkerjabag',compact('jamkerja','bagian','jamkerjabag','jamkerjabag_detail'));
    }

    public function deletejamkerjabag($kode_jk_dept){
        try {
            DB::table('konf_jk_dept')->where('kode_jk_dept', $kode_jk_dept)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e){
            return Redirect::back()->with(['warning' => 'Data Gagal Dihapus']);
        }
    }
}
