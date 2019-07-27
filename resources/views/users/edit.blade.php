@extends('layouts.default')
@section('title',$user->name.'-更新个人资料')
@section('content')
    <div class="panel panel-default">
        <div class="panel-header">
            <h2>更新资料</h2>
        </div>
        @include('shared._errors')
        <div class="panel-body">
            <div class="gravatar_edit">
                <a href="http://www.gravatar.com/emials" target="_blank">
                    <img src="{{$user->gravatar('200')}}" alt="{{$user->name}}" class="gravatar">
                </a>
            </div>
            <form method="POST" action="{{route('users.update',$user->id)}}">
                {{method_field('PATCH')}}
                {{csrf_field()}}
                <div class="form-group">
                    <label for="name">名称：</label>
                    <input type="text" name="name" class="form-control" value="{{$user->name}}">
                </div>

                <div class="form-group">
                    <label for="email">邮箱：</label>
                    <input type="text" name="email" class="form-control" value="{{$user->email}}" disabled>
                </div>

                <div class="form-group">
                    <label for="password">密码：</label>
                    <input type="password" name="password" class="form-control" value="{{old('password')}}">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">确认密码：</label>
                    <input type="password" name="password_confirmation" class="form-control" value="{{old('password_confirmation')}}">
                </div>

                <button type="submit" class="btn btn-primary">更新</button>
            </form>
        </div>
    </div>
@stop