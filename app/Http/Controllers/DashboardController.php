<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        return view('layout/admin_layout/dashboard', [
            'title'         => "Dashboard",
            'folder'        => "Home",
        ]);
    }
}
