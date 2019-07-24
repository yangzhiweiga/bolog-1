@extends('layouts.default')
@section('title','注册')
@section('content')
    <div class="col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h5>注冊</h5>
            </div>
            <div class="panel-body">
                @include('shared._errors')
                <form method="POST" action="{{route('users.store')}}">
                    {{csrf_field()}}
                    <div class="form-group">
                        <label for="name">名称：</label>
                        <input type="text"  name="name"  class="form-control" value="{{old('name')}}">
                    </div>

                    <div class="form-group">
                        <label for="email">邮箱：</label>
                        <input type="email" name="email" class="form-control" value="{{old('email')}}">
                    </div>

                    <div class="form-group">
                        <label for="password">密码：</label>
                        <input type="password" class="form-control" name="password" value="{{old('password')}}">
                    </div>

                    <div class="form-group">
                        <label for="password_cofirmation">确认密码：</label>
                        <input type="password" class="form-control" name="password_confirmation" value="{{old('password_confirmation')}}">
                    </div>

                    <button type="sumbit" class="btn btn-primary">注册</button>
                </form>
            </div>
        </div>
    </div>
@stop