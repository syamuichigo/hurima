<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'image',
        'name',
        'brand',
        'price',
        'info',
        'condition_id',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'content_category');
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    /**
     * 商品状態（conditionテーブル）とのリレーション
     * condition_id カラムで condition.id を参照
     */
    public function condition()
    {
        return $this->belongsTo(Condition::class, 'condition_id');
    }
}
