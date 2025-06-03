<?php

namespace App\Livewire\Front;

use App\Models\SubjectMatter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class LearningClass extends Component
{
    use WithPagination;

    public $class_id;

    public function mount()
    {
        $user = User::with('student_classes')->where('email', auth()->user()->email)->first();
        $this->class_id = $user->student_classes[0]->id ?? null;
    }

    public function render()
    {
        $subjectMatters = [];

        if ($this->class_id) {
            $subjectMatters = SubjectMatter::whereHas('student_classes', function (Builder $query) {
                $query->where('student_class_id', $this->class_id);
            })->orderBy('created_at', 'desc')->paginate(7);
        }

        return view('livewire.front.learning-class', [
            'subjectMatters' => $subjectMatters
        ]);
    }
}
