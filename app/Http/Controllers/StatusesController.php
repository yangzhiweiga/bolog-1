<?php

namespace App\Http\Controllers;

use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatusesController extends Controller
{
    //过滤未登录用户
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request)
    {
        $this->validate($request,[
            'content'=>'required|max:255'
        ]);

        Auth::user()->statuses()->create([
            'content'=>$request['content']
        ]);

        return redirect()->back();
    }

    /**
     * 删除微博操作
     *
     * @param Status $status
     * @return \Illuminate\Http\RedirectResponse|void
     * @throws \Exception
     */
    public function destroy(Status $status)
    {
        try{
            $this->authorize('destroy',$status);
        }catch (\Exception $e){
            return abort(403,'没有权限删除这条微博');
        }

        $status->delete();
        session()->flash('success','微博已被成功删除！');
        return redirect()->back();
    }
}
