<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Listing extends Model
{
    use HasFactory;

    /**
     * モデルが参照するテーブル名を明示
     *
     * デフォルトではクラス名の複数形（listings）が使われるため、
     * マイグレーションで作成した単数形テーブル listing に合わせる。
     */
    protected $table = 'listing';

    protected $fillable = [
        'user_id',
        'content_id',
    ];

    public function content()
    {
        return $this->belongsTo(Content::class, 'content_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
