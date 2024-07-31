<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdminAuthController extends Controller
{
    public function showLoginForm(){
        return Inertia::render("Admin/Auth/login");
    }

    public function login(Request $request){
        if(Auth::attempt(['email' => $request->email , 'password' => $request->password, 'isAdmin' => true]) ){
            return redirect()->route('admin.dashboard')->with('success','Login successfull');
        }
        return redirect()->route('admin.login')->with('error','Username or Password false');

        // Dalam contoh ini, Auth::attempt('email' => $request->email , 'password' => $request->password,) mencoba melakukan otentikasi berdasarkan alamat email dan kata sandi yang diberikan. Jika otentikasi berhasil, pengguna akan diarahkan ke dashboard. Jika gagal, pesan kesalahan akan ditampilkan kepada pengguna.
    }

    public function logout(Request $request){
        Auth::guard('web')->logout();
        $request->session()->invalidate(); // ini untuk menghilangkan sessionnya

        return redirect()->route('admin.login');
    }


}
