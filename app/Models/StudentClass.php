<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudentClass extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Relations Many to Many with table 'users' using table pivot 'student_has_class'
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'student_has_class');
    }

    /**
     * Relations Many to Many with table 'subject_matter' using table pivot 'subject_matter_has_class'
     *
     * @return BelongsToMany
     */
    public function SubjectMatters(): BelongsToMany
    {
        return $this->belongToMany(SubjectMatter::class, 'subject_matter_has_class');
    }
}
