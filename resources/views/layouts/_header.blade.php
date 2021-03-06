<header class="navbar navbar-fixed-top navbar-inverse">
    <div class="container">
        <div class="col-md-12">
            <a href="{{route('home')}}" id="logo">CSDN</a>
            <nav>
                <ul class="nav navbar-nav navbar-right">
                    @if(Auth::check())
                        <li><a href="{{route('users.index')}}">用户列表</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                {{Auth::user()->name}}<b class="caret"></b>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a href="{{route('users.show',\Illuminate\Support\Facades\Auth::user()->id)}}">个人中心</a></li>
                                <li><a href="{{route('users.edit',Auth::user()->id)}}">编辑资料</a></li>
                                <li class="divider"></li>
                                <li>
                                    <form method="POST" action="{{route('logout')}}">
                                        {{csrf_field()}}
                                        {{method_field('DELETE')}}
                                        <button type="submit" class="btn btn-block btn-danger">退出</button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <li><a href="{{route('help')}}">帮助</a></li>
                        <li><a href="#">登录</a></li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</header>
