<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    protected $fillable = ['quiz_id', 'user_id', 'score'];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
