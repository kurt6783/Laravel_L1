<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class SessionsController extends Controller
{
    //
    public function __construct(){
      $this->middleware('guest',[
        'only' => ['create']
      ]);
    }
    
    public function create(){
        return view('sessions.create');
    }

    public function store(Request $request)
    {
       $credentials = $this->validate($request, [
           'email' => 'required|email|max:255',
           'password' => 'required'
       ]);

       

       if (Auth::attempt($credentials, $request->has('rember'))) {
        if(Auth::user()->activated){
          session()->flash('success', '歡迎回來');
          $fallback =route('users.show', Auth::user());
          return redirect()->intended($fallback);
        }else{
          Auth::logout();
          session()->flash('warning', '您的帳號未啟用，請檢察信箱中的註冊郵件進行啟用。');
          return redirect('/');
        }           
       } else {
           session()->flash('danger', '很抱歉，您的email與密碼錯誤');
           return redirect()->back()->withInput();
       }
    }

    public function destroy()
    {
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        return redirect('login');
    }
}