<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Condition extends Model
{
    use HasFactory;

    /**
     * テーブル名
     *
     * マイグレーションが単数形 `condition` なので明示的に指定
     */
    protected $table = 'condition';

    /**
     * 一括代入可能な属性
     */
    protected $fillable = [
        'name',
    ];
}


