<?php

namespace App\View\Components;

use App\Models\SubjectMatter;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\Component;
use Illuminate\View\View;

class FrontLayout extends Component
{
    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('layouts.front');
    }
}
