<?php

namespace App\Livewire\Front;

use App\Models\work_results;
use Livewire\Component;

class WorkResults extends Component
{
    private $work_results;

    public function mount() {}

    public function boot()
    {
        $this->work_results = work_results::with('User')->orderBy('created_at', 'desc')->where('showAtFront', true)->limit(9)->get();
    }

    public function render()
    {
        return view('livewire.front.work-results', ['work_results' => $this->work_results]);
    }
}
