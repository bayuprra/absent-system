<?php

namespace App\Http\Controllers;

use App\Models\UserAbsent;
use Illuminate\Http\Request;

class AbsentDataController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => "Data Absent",
            'folder'        => "Home",
            'data'          => $this->userAbsent->getUserAbsentData()
        ];
        dump($data);
        return view('layout/admin_layout/absent', $data);
    }
}
