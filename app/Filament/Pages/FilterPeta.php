<?php

namespace App\Filament\Pages;

use App\Models\Gambar;
use Filament\Pages\Page;
use App\Models\PetaKoordiant;

class FilterPeta extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static string $view = 'filament.pages.filter-peta';
    protected static ?string $navigationLabel = 'Cari Peta Karantina ';

    protected ?string $heading = 'Cari Peta Karantina';

    public $peta;

    public $idx;

    public $marks;
    public $datas;



    public function mount()
    {
        $this->peta = PetaKoordiant::all();
        // $this->marks = Gambar::where('peta_koordiant_id', $this->idx)->get();

        $this->marks = Gambar::where('peta_koordiant_id', $this->idx)->get();
        $this->datas = PetaKoordiant::where('id', $this->idx)->first();

    }



    public function updatedIdx()
    {
        $this->marks = Gambar::where('peta_koordiant_id', $this->idx)->get();
        $this->datas = PetaKoordiant::where('id', $this->idx)->first();
    }

}
