<form action="{{route('statuses.store')}}" method="POST">
    @include('shared._errors')
    {{csrf_field()}}
    <textarea name="content" class="form-control" rows="5"
    placeholder="聊聊新鲜事儿...">
        {{old('content')}}
    </textarea>
    <button type="submit" class="btn btn-primary pull-right">发布</button>
</form>