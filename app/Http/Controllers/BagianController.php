<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Bagian;
use Illuminate\Support\Facades\Redirect;
class BagianController extends Controller
{
    public function index(Request $request){
        $nama_dept = $request->nama_dept;
        $query = Bagian::query();
        $query -> select('*');
        if(!empty($nama_dept)){
            $query->where('nama_dept', 'like','%'.$nama_dept.'%');
        }
        $bagian = $query->get();
        //$departement = DB::table('departement')->orderBy('kode_dept')->get();
        return view('bagian.index', compact('bagian'));
    }

    public function store(Request $request){
        $kode_dept = $request->kode_dept;
        $nama_dept = $request->nama_dept;
        $data = [
            'kode_dept' => $kode_dept,
            'nama_dept' => $nama_dept,
        ];

        $simpan = DB::table('bagian')->insert($data);
        if($simpan){
            return Redirect::back()->with(['success'=>'Data Departement Berhasil Disimpan']);
        } else{
            return Redirect::back()->with(['warning'=>'Data Departement Gagal Disimpan']);
        }

    }

    public function edit(Request $request){
        $kode_dept = $request->kode_dept;
        $bagian = DB::table('bagian')->where('kode_dept', $kode_dept)->first();
        return view('bagian.edit', compact('bagian'));
    }

    public function update($kode_dept, Request $request){
        $nama_dept = $request->nama_dept;
        $data = [
            'nama_dept' => $nama_dept,
        ];
        $update = DB::table('bagian')->where('kode_dept', $kode_dept)->update($data);
        if($update){
            return Redirect::back()->with(['success'=>'Data Berhasil Diupdate']);
        } else{
            return Redirect::back()->with(['warning'=>'Data Tidak Berhasil Diupdate']);
        }
    }

    public function delete($kode_dept){
        $hapus = DB::table('bagian')->where('kode_dept', $kode_dept)->delete();
        if($hapus){
            return Redirect::back()->with(['success'=>'Data Berhasil Dihapus']);
        } else{
            return Redirect::back()->with(['warning'=>'Data Tidak Berhasil Dihapus']);
        }
    }
}
