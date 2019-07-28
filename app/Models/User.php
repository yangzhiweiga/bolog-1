<?php

namespace App\Models;

use App\Notifications\ResetPassword;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        return $this->statuses()
            ->orderBy('created_at','desc');
    }
}
