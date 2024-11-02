<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class student_subject_matter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subject_matter_id',
        'opened_at',
    ];
}
