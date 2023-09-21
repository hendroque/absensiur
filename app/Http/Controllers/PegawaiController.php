<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pegawai;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Redirect;

class PegawaiController extends Controller
{
    public function index(Request $request){

        $query = Pegawai::query();
        $query -> select('pegawai.*','nama_dept');
        $query -> join('bagian', 'pegawai.kode_dept', '=', 'bagian.kode_dept');
        $query -> orderBy('nama_lengkap');
        if (!empty($request->nama_pegawai)){
            $query->where('nama_lengkap', 'like', '%' . $request->nama_pegawai . '%');
        }

        if (!empty($request->kode_dept)) {
            $query->where('pegawai.kode_dept', $request->kode_dept);
        }
        $pegawai = $query->paginate(10);

        $bagian = DB::table('bagian')->get();
        return view('pegawai.index', compact('pegawai','bagian'));
        
    }
    public function store(Request $request){
        $nip = $request->nip;
        $nama_lengkap = $request->nama_lengkap;
        $posisi = $request->posisi;
        $no_hp =  $request->no_hp;
        $kode_dept = $request->kode_dept;

        $tanggal = date('Y-m-d');
        $qr_code = $request->nip . $request->nama_lengkap . Str::random(20) . $tanggal;

        $password = Hash::make('123');
        if($request ->hasFile('foto')){
            $foto = $nip."." .$request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = null;
        }

        try {
            $data = [ 
                'nip' => $nip,
                'nama_lengkap' => $nama_lengkap,
                'posisi' => $posisi,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'qr_code' => $qr_code,
                'password' => $password
            ];
            $simpan = DB::table('pegawai')->insert($data);
            if($simpan){
                if($request ->hasFile('foto')){
                    $folderPath = "public/uploads/pegawai/";
                    $request->file('foto')->storeAs($folderPath, $foto);
                } 
                return Redirect::back()->with(['success'=>'Data Berhasil Disimpan']);
            }
        } catch(\Exception $e){
            if ($e->getCode() == 23000){
                $message = " Data dengan NIP " . $nip . " Sudah Ada";
            } else{
                $message = ", Periksa Kembali Inputan Data!";
            }
            return Redirect::back()->with(['warning'=>'Data Tidak Berhasil Disimpan' . $message]);
        }
    }

    public function updateQRCodeDaily()
    {
    $pegawai = Pegawai::all();

    foreach ($pegawai as $p) {
        $tanggal = date('Ymd');
        $qr_code = $p->nip . $p->nama_lengkap . Str::random(20) . $tanggal;

        $p->update(['qr_code' => $qr_code]);
    }

    return "QR codes updated successfully!";
    }
    
    public function edit(Request $request){

        $nip = $request -> nip;
        $bagian = DB::table('bagian')->get();
        $pegawai = DB::table('pegawai')->where('nip',$nip)->first();
        return view('pegawai.edit', compact ('bagian', 'pegawai'));
    }

    public function update($nip, Request $request){
        $nip = $request -> nip;
        $nama_lengkap = $request->nama_lengkap;
        $posisi = $request->posisi;
        $no_hp =  $request->no_hp;
        $kode_dept = $request->kode_dept;
        $password = Hash::make('123');
        $old_foto = $request->old_foto;
        if($request ->hasFile('foto')){
            $foto = $nip . "." . $request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = $old_foto;
        }

        try {
            $data = [ 
                'nama_lengkap' => $nama_lengkap,
                'posisi' => $posisi,
                'no_hp' => $no_hp,
                'kode_dept' => $kode_dept,
                'foto' => $foto,
                'password' => $password
            ];
            $update = DB::table('pegawai')->where('nip', $nip)->update($data);
            if($update){
                if($request ->hasFile('foto')){
                    $folderPath = "public/uploads/pegawai/";
                    $folderPathOld = "public/uploads/pegawai/" . $old_foto;
                    Storage::delete($folderPathOld);
                    $request->file('foto')->storeAs($folderPath, $foto);
                }
                return Redirect::back()->with(['success'=>'Data Berhasil Diupdate']);
            }
        } catch(\Exception $e){
            
            return Redirect::back()->with(['warning'=>'Data Tidak Berhasil Diupdate']);
        }
    }
    public function delete($nip){
        $delete = DB::table('pegawai')->where('nip',$nip)->delete();
        if($delete) {
           return Redirect::back()->with(['success'=>'Data Berhasil Dihapus']);
        } else {
            return Redirect::back()->with(['warning'=>'Data Gagal Dihapus']);
        }
    }
}
