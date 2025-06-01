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
        $this->belongsTo(User::class, 'user_id', 'id');
    }
}
