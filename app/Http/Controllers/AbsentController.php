<?php

namespace App\Http\Controllers;

use App\Models\Absent;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Exception;

class AbsentController extends Controller
{
    public function index()
    {
        $data = array(
            'title'         => "Absent",
            'folder'        => "User",
            'dataSession'   => session()->all()
        );
        return view('layout/user_layout/absent', $data);
    }

    public function create(Request $request)
    {
        try {
            $data = $request->input('data');
            $idKaryawan = $data['userId'];
            $karyawan = new Absent();
            $karyawan->karyawan_id = $idKaryawan;
            $karyawan->latitude = $data['latitude'];
            $karyawan->longitude = $data['longitude'];
            $karyawan->onRadius = 1;
            $karyawan->save();
            return response()->json([
                'success' => true,
                'data'    => $karyawan
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
