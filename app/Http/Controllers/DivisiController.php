<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class DivisiController extends Controller
{
    public function index()
    {
        $data = array(
            'title'             => "Data Divisi",
            'folder'            => "Admin",
            'data'              => Divisi::all(),
        );
        return view('layout/admin_layout/divisi', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:divisi'
        ], [
            'nama.unique' => 'Nama Divisi telah digunakan. Silakan pilih nama lain.',
        ]);
        try {
            $divisi = new Divisi();
            $divisi->nama = $request->nama;
            $divisi->save();
            return redirect()->back()->with('success', 'Divisi Berhasil Ditambahkan');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data. Mohon coba lagi.');
        }
    }

    public function update(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|unique:divisi'
        ], [
            'nama.unique' => 'Nama Divisi telah digunakan. Silakan pilih nama lain.',
        ]);
        try {
            $divisi = Divisi::find($request->id);
            $divisi->nama = $request->nama;
            $divisi->save();
            return redirect()->back()->with('success', 'Divisi Berhasil Dirubah');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal Diupdate data. Mohon coba lagi.');
        }
    }

    public function delete(Request $request)
    {
        try {
            $akun = Divisi::find($request->id);
            $akun->delete();
            return redirect()->back()->with('success', 'Divisi berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data Divisi. Mohon coba lagi.');
        }
    }
}
