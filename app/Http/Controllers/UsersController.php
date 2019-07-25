<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class UsersController extends Controller
{
    //
    public function create()
    {
        return view('users.create');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * 1.数据验证
     * 2.验证规则
     * 3.csrf伪造跨站请求
     *
     *  注册失败信息
     *  检测是否包含错误信息 count($errors)>0 获取所有错误信息 $errors->all()
     *  1.局部错误信息视图
     *  2.注册页面引入错误信息视图
     *  3.语言包设置 引入laravel-lang语言包 composer required laravel-lang 替换语言包路径 配置文件设置lang=zh-CN
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        /**
         * 1.非空验证 reqired 2.长度验证:min|max
         * 3.邮箱验证 email 4.唯一验证 unique:users 在users这张表进行唯一性验证 为了更安全在设计表之初对邮箱指定唯一键
         * 5.密码验证 confirmed 确认密码需要在name属性后面加_confirmation
         */
        $this->validate($request, [
            'name' => 'required|max:26',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password)
        ]);

        Auth::login($user);
        session()->flash('success', '注册成功,欢迎开启心情之旅');
        return redirect()->route('users.show', [$user]);
    }
}
