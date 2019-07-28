<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SessionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest',[
            'only'=>['create']
        ]);
    }

    //
    public function create()
    {
        return view('sessions.create');
    }

    /**
     * 登陆
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $req = $this->validate($request,[
            'email'=>'required|email|max:255',
            'password'=>'required'
        ]);

        //登陆身份认证
        if(Auth::attempt($req,$request->has('remember'))){
            if(Auth::user()->activated){
                session()->flash('success','欢迎回来');
                //intended自动获取退出登陆哪一个页面地址如果存在跳转到上一次页面,不存在指定跳转到个人中心页面
                return redirect()->intended(route('users.show',[Auth::user()]));
            }else{
                Auth::logout();
                session()->flash('warning','你的账号未激活,请检查邮箱中的邮件进行激活');
                return redirect('/');
            }
        }else{
            session()->flash('danger','邮箱或密码不正确');
            return redirect()->back();
        }
    }

    public function destory()
    {
        Auth::logout();
        session()->flash('success','你已经成功退出');
        return redirect('login');
    }
}
