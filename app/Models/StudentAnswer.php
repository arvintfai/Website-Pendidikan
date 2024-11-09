<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    protected $fillable = ['quiz_id', 'user_id', 'question_id', 'option_id'];
}
