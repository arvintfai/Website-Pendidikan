<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTO;
use Illuminate\Support\Facades\Storage;

class Assigment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'subject_matter_id',
        'file_name',
        'scores'
    ];

    /**
     * Relations Many to On with table 'users'
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * function Using Closures to hook EventListeners [saved and deleted]
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::deleted(function ($model) {
            if (! is_null($model->file_name)) {
                Storage::disk('public')->delete($model->file_name);
            }
        });
    }

    /**
     * Relations Many to One with table 'users'
     *
     * @return BelongsTO
     */
    public function subject_matter(): BelongsTo
    {
        return $this->belongsTo(SubjectMatter::class);
    }
}
