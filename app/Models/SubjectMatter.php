<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Facades\Storage;

use function PHPUnit\Framework\fileExists;

class SubjectMatter extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'file_path',
        'video_path',
        'video_link',
        'is_has_assigment',
        'assigment_content',
    ];

    /**
     * function Using Closures to hook EventListeners [saved and deleted]
     *
     * @return void
     */
    protected static function booted(): void
    {
        static::saved(function ($model) {
            if ($model->isDirty('file_path')) {
                if ($model->getOriginal('file_path'))
                    Storage::disk('public')->delete($model->getOriginal('file_path'));
            }
            if ($model->isDirty('video_path')) {
                if ($model->getOriginal('video_path'))
                    Storage::disk('public')->delete($model->getOriginal('video_path'));
            }
        });

        static::deleted(function ($model) {
            if (! is_null($model->file_path)) {
                Storage::disk('public')->delete($model->file_path);
            }
            if (! is_null($model->video_path)) {
                Storage::disk('public')->delete($model->video_path);
            }
        });
    }

    /**
     * Relations Many to Many with table 'student_classes' using table pivot 'subject_matter_has_class'
     *
     * @return BelongsToMany
     */
    public function student_classes(): BelongsToMany
    {
        return $this->BelongSToMany(StudentClass::class, 'subject_matter_has_class');
    }

    /**
     * Relations Many to Many with table 'users' using table pivot 'student_subject_matter'
     *
     * @return BelongsToMany
     */
    public function usersOpened(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_subject_matter')
            ->withPivot('opened_at')
            ->withTimestamps();
    }

    /**
     * Relations One to Many with table 'assigments'
     *
     * @return HasMany
     */
    public function assigment(): HasMany
    {
        return $this->hasMany(Assigment::class);
    }
}
