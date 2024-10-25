<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;

class EditProfile extends Page
{
    protected static bool $shouldRegisterNavigation = false; //untuk membuat agar menu tidak ditampilkan dalam list menu

    protected static string $view = 'filament.pages.edit-profile';

    public $user;

    public function mount()
    {
        $this->user = auth()->user();
    }
}
