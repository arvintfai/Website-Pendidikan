<?php

namespace App\Livewire\Front;

use App\Models\Announcement as ModelsAnnouncement;
use Livewire\Component;

class Announcement extends Component
{
    public function render()
    {
        $datas = ModelsAnnouncement::where('valid_until', '>=', now())->orderBy('created_at', 'desc')->get();
        return view('livewire.front.announcement', compact('datas'));
    }
}
