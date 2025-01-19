<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSession extends Model
{
    public $fillable = [
        'user_id',
        'session_token'
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
