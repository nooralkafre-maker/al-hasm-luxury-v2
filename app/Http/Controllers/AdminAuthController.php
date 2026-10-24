<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class AdminAuthController {
 public function show(){if(Auth::check())return redirect('/admin');return view('login');}
 public function login(Request $request){
  $credentials=$request->validate(['email'=>['required','email'],'password'=>['required','string']]);
  if(Auth::attempt($credentials)) {
   if(!Auth::user()?->is_admin) {Auth::logout();return back()->withErrors(['email'=>'Administrator access required.']);}
   $request->session()->regenerate();return redirect()->intended('/admin');
  }
  return back()->withErrors(['email'=>'The supplied credentials are not valid.'])->onlyInput('email');
 }
 public function logout(Request $request){Auth::logout();$request->session()->invalidate();$request->session()->regenerateToken();return redirect('/admin/login');}
}
