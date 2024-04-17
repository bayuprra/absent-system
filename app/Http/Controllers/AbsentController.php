<?php

namespace App\Http\Controllers;

use App\Models\Absent;
use App\Models\AbsenTime;
use App\Models\Karyawan;
use App\Models\UserAbsent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Exception;

class AbsentController extends Controller
{
    public function index(Request $request)
    {
        $dataLogin = AbsenTime::where('tanggal', date('Y-m-d'))->first('id');
        $dataLoginuser = UserAbsent::where('absenttime_id', $dataLogin->id)->where('karyawan_id', session()->get('data')->idKaryawan)->first();
        $data = array(
            'title'         => "Absent",
            'folder'        => "User",
            'dataSession'   => session()->all(),
            'dataAbsent'     => $dataLoginuser
        );
        return view('layout/user_layout/absent', $data);
    }

    public function absent(Request $request)
    {
        try {
            $date = Carbon::now();
            $time = $date->toDateTimeString();
            $data = $request->input('data');
            $idAbsent = $data['id'];
            $distance = $data['distance'];
            $flag = "WFO";
            if ($distance == "false") {
                $flag = "WFH";
            }
            $status = $data['status'];
            $absent = UserAbsent::find($idAbsent);
            if ($status == "in") {
                $absent->checkin = $time;
            } else {
                $absent->checkout = $time;
            }
            $absent->flag = $flag;
            $absent->save();
            return response()->json([
                'success' => true,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error'   => $e->getMessage(),
            ], 500);
        }
    }
}
