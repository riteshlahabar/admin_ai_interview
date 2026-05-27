<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class AdminAuthController extends Controller
{
  public function showLogin(){ return view('admin.auth.login'); }

  public function login(Request $r){
    $data = $r->validate([
      'email'=>'required|email',
      'password'=>'required|string',
    ]);

    if (Auth::attempt(['email'=>$data['email'], 'password'=>$data['password'], 'role'=>'admin'])) {
      $r->session()->regenerate();
      return redirect()->route('admin.dashboard');
    }
    return back()->withErrors(['email'=>'Invalid credentials'])->withInput();
  }

  public function logout(Request $r){
    Auth::logout();
    $r->session()->invalidate();
    $r->session()->regenerateToken();
    return redirect()->route('admin.login');
  }
}
