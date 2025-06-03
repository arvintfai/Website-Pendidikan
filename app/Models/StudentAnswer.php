<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['quiz_id', 'user_id', 'question_id', 'option_id'];

    public function questions()
    {
        return $this->hasOne(Question::class, 'id');
    }

    public function options()
    {
        return $this->hasOne(Option::class, 'id', 'option_id');
    }
}
