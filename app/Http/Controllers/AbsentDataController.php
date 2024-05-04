<?php

namespace App\Http\Controllers;

use App\Models\UserAbsent;
use Illuminate\Http\Request;
use carbon\Carbon;

class AbsentDataController extends Controller
{
    public function index(Request $request)
    {
        $selectedMonth = $request->input('month') ? Carbon::parse($request->input('month')) : Carbon::now();

        $data = [
            'title'         => "Data Absent",
            'folder'        => "Home",
            'selectedMonth' => $selectedMonth,
            'data'          => $this->userAbsent->getUserAbsentData(),
            'months'        => $this->generateMonthOptions()
        ];

        return view('layout/admin_layout/absent', $data);
    }

    public function userAbsentData(Request $request)
    {
        $selectedMonth = $request->input('month') ? Carbon::parse($request->input('month')) : Carbon::now();

        $data = [
            'title'         => "Data Absent",
            'folder'        => "Home",
            'selectedMonth' => $selectedMonth,
            'data'          => $this->userAbsent->getUserAbsentData(),
            'months'        => $this->generateMonthOptions()
        ];
        return view('layout/user_layout/data_absent', $data);
    }

    private function generateMonthOptions()
    {
        $months = [];
        $currentMonth = Carbon::now();
        for ($i = 0; $i < 12; $i++) {
            $months[] = $currentMonth->copy()->subMonths($i);
        }
        return $months;
    }
}
