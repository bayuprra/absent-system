<?php

namespace App\Http\Controllers;

use App\Models\AbsenTime;
use App\Models\Divisi;
use App\Models\Karyawan;
use App\Models\UserAbsent;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today()->toDateString();
        $idAbsentDate = AbsenTime::where('tanggal', $today)->where('status', 0)->first('id');
        $dataUserAbsent = UserAbsent::where('absenttime_id', $idAbsentDate->id)->with('karyawan')->get();
        $countTodayAbsent = $dataUserAbsent->count();
        $countUserCheckin = $dataUserAbsent->whereNotNull('checkin')->count();
        $countUserCheckout = $dataUserAbsent->whereNotNull('checkout')->count();
        $countUserCheckinPercentage = intval($countUserCheckin / $countTodayAbsent * 100);
        $countUserCheckoutPercentage = intval($countUserCheckout / $countTodayAbsent * 100);

        $firstUserCheckin = UserAbsent::where('absenttime_id', $idAbsentDate->id)->with('karyawan')->whereNotNull('checkin')->orderBy('checkin', 'ASC')->first();
        $firstUserCheckout = UserAbsent::where('absenttime_id', $idAbsentDate->id)->with('karyawan')->whereNotNull('checkout')->orderBy('checkout', 'ASC')->first();
        $lastUserCheckin = UserAbsent::where('absenttime_id', $idAbsentDate->id)->with('karyawan')->whereNotNull('checkin')->orderBy('checkin', 'DESC')->first();
        $lastUserCheckout = UserAbsent::where('absenttime_id', $idAbsentDate->id)->with('karyawan')->whereNotNull('checkout')->orderBy('checkout', 'DESC')->first();
        $dataDashboard = array(
            'countTodayAbsent'              => $countTodayAbsent ?? null,
            'countUserCheckin'              => $countUserCheckin ?? null,
            'countUserCheckinPercentage'    => $countUserCheckinPercentage ?? null,
            'countUserCheckout'             => $countUserCheckout ?? null,
            'countUserCheckoutPercentage'   => $countUserCheckoutPercentage ?? null,
            'firstUserCheckin'              => $firstUserCheckin->karyawan->nama ?? null,
            'firstUserCheckinTime'          => $firstUserCheckin->checkin ?? null,
            'firstUserCheckout'             => $firstUserCheckout->karyawan->nama ?? null,
            'firstUserCheckoutTime'         => $firstUserCheckout->checkout ?? null,
            'lastUserCheckin'               => $lastUserCheckin->karyawan->nama ?? null,
            'lastUserCheckinTime'           => $lastUserCheckin->checkin ?? null,
            'lastUserCheckout'              => $lastUserCheckout->karyawan->nama ?? null,
            'lastUserCheckoutTime'          => $lastUserCheckout->checkout ?? null,
        );
        $data = [
            'title'         => "Dashboard",
            'folder'        => "Home",
            'sumKaryawan'   => Karyawan::all()->count(),
            'sumDivisi'     => Divisi::all()->count(),
            'dataDashboard' => $dataDashboard
        ];
        return view('layout/admin_layout/dashboard', $data);
    }
}
