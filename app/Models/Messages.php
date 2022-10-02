<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Messages extends Model
{
    use HasFactory;

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id', 'id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }
}
