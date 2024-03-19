<?php

namespace App\Http\Controllers;

use App\Models\akunModel;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;

class KaryawanController extends Controller
{
    public function index()
    {
        $data = array(
            'title'             => "Data Karyawan",
            'folder'            => "Admin",
            'data'              => Karyawan::with('akun')->get()
        );
        return view('layout/admin_layout/karyawan', $data);
    }

    public function create(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'username' => 'required|string|unique:akun',
            'jenis_kelamin' => 'required|string',
            'no_telepon' => 'required|string',
            'divisi' => 'required|string',
        ], [
            'username.unique' => 'Username atau email sudah digunakan. Silakan pilih username lain.',
        ]);
        $password = $request->nama . "1234";
        try {
            DB::transaction(function () use ($request, $password) {
                $akun = new akunModel();
                $akun->username = $request->username;
                $akun->password = bcrypt($password);
                $akun->role_id = 2;
                $akun->save();

                $karyawan = new Karyawan();
                $karyawan->nama = $request->nama;
                $karyawan->jenis_kelamin = $request->jenis_kelamin;
                $karyawan->no_telepon = $request->no_telepon;
                $karyawan->divisi = $request->divisi;
                $karyawan->akun_id = $akun->id;
                $karyawan->save();
            });
            return redirect()->back()->with('success', 'Karyawan Berhasil Ditambahkan');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menyimpan data. Mohon coba lagi.');
        }
    }

    public function update(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $akun = akunModel::find($request->akunId);
                $akun->username = $request->username;
                $akun->save();

                $karyawan = Karyawan::find($request->id);
                $karyawan->nama = $request->nama;
                $karyawan->jenis_kelamin = $request->jenis_kelamin;
                $karyawan->no_telepon = $request->no_telepon;
                $karyawan->divisi = $request->divisi;
                $karyawan->save();
            });
            return redirect()->back()->with('success', 'Karyawan Berhasil Diupdate');
        } catch (QueryException $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal Diupdate data. Mohon coba lagi.');
        }
    }

    public function delete(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $akun = akunModel::find($request->id);
                $akun->delete();
            });
            return redirect()->back()->with('success', 'Karyawan berhasil dihapus.');
        } catch (QueryException $e) {
            return redirect()->back()->with('error', 'Gagal menghapus data karyawan. Mohon coba lagi.');
        }
    }
}
