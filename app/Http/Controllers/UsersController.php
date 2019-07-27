<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Mockery\Exception;

class UsersController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth', [
            'except' => ['show', 'create', 'store']
        ]);
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }

    public function index()
    {
        $users = User::paginate(10);
        return view('users.index', compact('users'));
    }

    //用户登陆
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

    public function edit(User $user)
    {
        try {
            //授权认证
            $this->authorize('update', $user);
        } catch (\Exception $e) {
            return abort(403, '你没有权限访问这个页面');
        }

        return view('users.edit', compact('user'));
    }

    /**
     * @param User $user
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(User $user, Request $request)
    {
        $this->validate($request, [
            'name' => 'required|max:55',
            'password' => 'nullable|confirmed|min:6'
        ]);

        try {
            //授权认证
            $this->authorize('update', $user);
        } catch (\Exception $e) {
            return abort(403, '你没有权限访问这个页面');
        }

        $data = [];
        $data['name'] = $request->name;
        if ($request->password) {
            $data['password'] = $request->password;
        }

        $user->update($data);

        session()->flash('success', '更新资料成功');
        return redirect()->route('users.show', $user->id);
    }

    public function destroy(User $user)
    {
        try {
            $this->authorize('destroy', $user);
        } catch (\Exception $e) {
            return abort(403, '你没有权限执行删除操作');
        }

        $user->delete();
        session()->flash('success', '删除成功');
        return back();
    }
}
