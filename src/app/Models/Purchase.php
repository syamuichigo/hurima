<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    /**
     * モデルが参照するテーブル名を明示
     *
     * デフォルトではクラス名の複数形（purchases）が使われるため、
     * マイグレーションで作成した単数形テーブル purchase に合わせる。
     */
    protected $table = 'purchase';

    protected $fillable = [
        'user_id',
        'content_id',
        'image',
        'name',
        'brand',
        'price',
        'detail',
        'info',
    ];

    public function content()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
