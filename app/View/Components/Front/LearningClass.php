<?php

namespace App\View\Components\Front;

use App\Models\SubjectMatter;
use App\Models\User;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;

class LearningClass extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        $class_id = User::where('email', auth()->user()->email)->with(['student_classes'])->get()[0]->student_classes[0]->id;
        $subjectMatters = SubjectMatter::whereHas('student_classes', fn(Builder $query) => $query->where('student_class_id', $class_id))->orderBy('created_at', 'desc')->paginate(15);
        return view('components.front.learning-class', ['subjectMatters' => $subjectMatters]);
    }
}
