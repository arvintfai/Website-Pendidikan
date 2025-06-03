<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['option_text', 'is_correct'];

    /**
     *  Relations Many to One with table 'options'
     *
     * @return BelongsTo
     */
    public function questions(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
