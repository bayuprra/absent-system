<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AbsentDataController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => "Data Absent",
            'folder'        => "Home",
        ];
        return view('layout/admin_layout/absent', $data);
    }
}
