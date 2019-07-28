<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Auth;

/**
 * 用户模型
 *
 * Authenticatable 授权相关功能的引用
 */
class User extends Authenticatable
{
    //消息通知相关功能
    use Notifiable;

    //定义模型对应的数据表默认为模型名复数形式(User模型对应users表)
//    protected $table = 'users';

    /**
     * 只有包含在该属性中的字段才能够被正常更新
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * 隐藏显示字段
     * 当我们需要对用户密码或其它敏感信息在用户实例通过数组或json显示时进行隐藏
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * 获取用户gravatar头像
     */
    public function gravatar($size = '100')
    {
        //根据用户在gravatar注册的邮箱生成MD5
        $hash = md5(strtolower(trim($this->attributes['email'])));
        //拼接gravatar服务器URL
        $url = sprintf('http://www.gravatar.com/avatar/%s?s=%s', $hash, $size);
        return $url;
    }

    /**
     * 事件 注册前设置activation_token
     */
    public static function boot()
    {
        parent::boot();
        static::creating(function($user){
            $user->activation_token = str_random(30);
        });
    }

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPassword($token));
    }

    //一个用户对应多条微博
    public function statuses()
    {
        return $this->hasMany(Status::class);
    }

    public function feed()
    {
        $user_ids = Auth::user()->followings->pluck('id')->toArray();
        array_push($user_ids,Auth::user()->id);
        return Status::whereIn('user_id',$user_ids)
            ->with('user')
            ->orderBy('created_at','desc');
    }

    /**
     * 获取粉丝列表
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followers()
    {
        return $this->belongsToMany(User::class,'followers','user_id','follower_id');
    }

    /**
     * 获取关注人列表
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function followings()
    {
        return $this->belongsToMany(User::class,'followers','follower_id','user_id');
    }

    /**
     * 关注用户
     *
     * @param $user_ids
     */
    public function follow($user_ids)
    {
        //compact的字符串就是变量的名字,多个变量名用逗号隔开.
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->sync($user_ids,false);
    }

    /**
     * 取消关注
     *
     * @param $user_ids
     */
    public function unfollow($user_ids)
    {
        if(!is_array($user_ids)){
            $user_ids = compact('user_ids');
        }
        $this->followings()->detach($user_ids);
    }

    /**
     * 是否在关注列表当中
     *
     * @param $user_id
     * @return mixed
     */
    public function isFollowing($user_id)
    {
        return $this->followings->contains($user_id);
    }
}
