<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['quiz_id', 'question_text'];

    /**
     *  Relations Many to One with table 'quizzes'
     *
     * @return BelongsTo
     */
    public function Quizzes(): BelongsTo
    {
        return $this->belongsTo(Quiz::class);
    }

    /**
     *  Relations One to Many with table 'options'
     *
     * @return HasMany
     */
    public function options(): HasMany
    {
        return $this->hasMany(Option::class);
    }
}
