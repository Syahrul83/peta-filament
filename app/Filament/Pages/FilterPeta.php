<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use App\Models\PetaKoordiant;

class FilterPeta extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.filter-peta';

    public $peta;


    public function mount()
    {
        $this->peta = PetaKoordiant::all();
    }

}
