<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    use HasFactory;
    protected $guarded = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function pictures()
    {
        return $this->hasMany(Pictures::class, 'order_id');
    }

    protected $casts = [
        'complete_time' => 'datetime'
    ];
}
