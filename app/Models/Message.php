<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table = 'message';

    /**
     * 可批量赋值的属性。
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'message',
    ];

    /**
     * 禁用自动管理的时间戳
     */
    public $timestamps = false;

    /**
     * 获取与消息关联的用户。
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Boot方法用于注册模型事件。
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->created_at = now();
        });
    }
}