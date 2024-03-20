<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'title'         => "Dashboard",
            'folder'        => "Home",
        ];
        return view('layout/admin_layout/dashboard', $data);
    }
}
