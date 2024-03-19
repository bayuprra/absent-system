<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function index()
    {
        return view('layout/change_password', [
            'title'         => "Password",
            'folder'        => "Home"
        ]);
    }

    public function change(Request $request)
    {
        $id = session()->get('data')->id;
        if (!$id) {
            return redirect()->to('/login');
        }
        $data = $request->all();
        $akun = $this->akunModel->find($id);
        if (!$akun) {
            return redirect()->to('/login');
        }
        $pass = $data['old'];
        $newPass = $data['new'];
        if (password_verify($pass, $akun['password'])) {
            $passwordChange = array(
                'password'      => bcrypt($newPass)
            );
            $changed = $akun->update($passwordChange);
            if ($changed) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->to('/login')->with('success', 'Password Berhasil Diubah, Harap Login menggunakan Password Baru');
            }
        }
        return redirect()->to('/login')->with('error', 'Password Gagal Diubah');
    }
}
