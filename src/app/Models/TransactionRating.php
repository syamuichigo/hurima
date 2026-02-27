<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionRating extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'rater_user_id',
        'rated_user_id',
        'rating',
    ];

    public function purchase()
    {
        return $this->belongsTo(Purchase::class, 'purchase_id');
    }

    public function rater()
    {
        return $this->belongsTo(User::class, 'rater_user_id');
    }

    public function ratedUser()
    {
        return $this->belongsTo(User::class, 'rated_user_id');
    }
}
