<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StaticPagesController extends Controller
{
    //主页
    public function home()
    {
        $feed_items = [];
        if(Auth::check()){
            $feed_items = Auth::user()->feed()->paginate(30);
        }
        return view('static_pages/home',compact('feed_items'));
    }

    public function help()
    {
        return '帮助';
    }

    public function about()
    {
        return '关于';
    }
}
