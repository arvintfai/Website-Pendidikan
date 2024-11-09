<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Option extends Model
{
    /**
     * @var array
     */
    protected $fillable = ['option_text', 'is_correct'];

    /**
     * @return BelongsTo
     */
    public function questions(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }
}
