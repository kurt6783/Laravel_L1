<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Mail;

class UsersController extends Controller
{
    //
    public function __construct(){
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store', 'index', 'confirmEmail']
        ]);

        $this->middleware('guest',[
            'only' => ['create']
        ]);
    }

    public function index(){
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    public function create(){
    	return view('users.create');
    }

    public function show(User $user){
        $statuses = $user->statuses()
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        return view('users.show', compact('user', 'statuses'));
    }

    public function store(Request $request){
        $this->validate($request, [
            'name' => 'required|unique:users|max:50',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|confirmed|min:6'
        ]);
        
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);

        $this->sendEmailConfirmationTo($user);
        // session()->flash('success', '驗證郵件已發送到您的註冊信箱，請查收。'); 等待email功能完成後啟用
        session()->flash('success', '請複製以下鏈接以驗證帳號 http://laravel-l1.herokuapp.com/signup/confirm/'.$user->activation_token);
        return redirect('/');
    }

    protected function sendEmailConfirmationTo($user){
        $view = 'emails.confirm';
        $data = compact('user');
        $from = 'k0911245920@gmail.com';
        $name = 'kurt6783';
        $to = $user->email;
        $subject = "感谢註冊 Weibo 應用！請確認您的信箱。";

        Mail::send($view, $data, function ($message) use ($from, $name, $to, $subject) {
            $message->from($from, $name)->to($to)->subject($subject);
        });
    }

    public function confirmEmail($token){
        $user = User::where('activation_token', $token)->firstOrFail();

        $user->activated = true;
        $user->activation_token = null;
        $user->email_verified_at = now();
        $user->save();

        Auth::login($user);
        session()->flash('success', '恭喜你，啟用成功！');
        return redirect()->route('users.show', [$user]);
    }

    public function edit(User $user){
        $this->authorize('update', $user);
        return view('users.edit' ,compact('user'));
    }

    public function update(User $user, Request $request){
        $this->authorize('update', $user);
        $this->validate($request, [
            'name' => 'required|max:50',
            'password' => 'nullable|confirmed|min:6'
        ]);

        $data = [];
        $data['name'] = $request->name;
        if($request->password){
            $data['password'] = bcrypt($request->password);
        }

        $user->update($data);

        session()->flash('success', '個人資料更新成功!');

        return redirect()->route('users.show', $user);
    }

    public function destroy(User $user){
        $this->authorize('destroy', $user);
        $user->delete();
        session()->flash('success', '成功刪除用戶!');
        return back();
    }

    public function followings(User $user){
        $users = $user->followings()->paginate(30);
        $title = $user->name . '關注的人';
        return view('users.show_follow', compact('users', 'title'));
    }

    public function followers(User $user){
        $users = $user->followers()->paginate(30);
        $title = $user->name . '的粉絲';
        return view('users.show_follow', compact('users', 'title'));
    }
}
