<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class work_results extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'photo_path',
        'video_path',
        'paragraph',
    ];

    /**
     * function Using Closures to hook EventListeners [saved and deleted]
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::deleted(function ($model) {
            if (! is_null($model->photo_path)) {
                Storage::disk('public')->delete(str_replace('/storage', '', $model->photo_path));
            }
            if (! is_null($model->video_path)) {
                Storage::disk('public')->delete(str_replace('/storage', '', $model->video_path));
            }
        });
    }

    /**
     * Relation Many to One to table 'User'
     *
     * @return BelongsTo
     */
    public function User(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
