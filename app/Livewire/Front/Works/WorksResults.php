<?php

namespace App\Livewire\Front\Works;

use App\Models\work_results;
use Livewire\Component;

class WorksResults extends Component
{
    private $works;

    public function mount() {}

    public function boot()
    {
        $this->works = work_results::where('showAtFront', true)->orderBy('created_at', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.front.works.works-results', ['works' => $this->works]);
    }
}
