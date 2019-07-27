<li>
    <img src="{{$user->gravatar()}}" alt="{{$user->name}}" class="gravatar">
    <a href="{{route('users.show',$user->id)}}">{{$user->name}}</a>
    @can('destroy',$user)
        <form action="{{route('users.destroy',$user->id)}}" method="POST">
            {{method_field('DELETE')}}
            {{csrf_field()}}
            <button type="submit" class="btn btn-danger btn-sm delete-btn">删除</button>
        </form>
    @endcan
</li>

