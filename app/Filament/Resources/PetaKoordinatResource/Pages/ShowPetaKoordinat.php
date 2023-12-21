<?php

namespace App\Filament\Resources\PetaKoordinatResource\Pages;

use Filament\Resources\Pages\Page;
use App\Filament\Resources\PetaKoordinatResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ShowPetaKoordinat extends Page
{
    protected static string $resource = PetaKoordinatResource::class;

    protected static string $view = 'filament.resources.peta-koordinat-resource.pages.show-peta-koordinat';
    use InteractsWithRecord;
    public function mount(int|string $record): void
    {

        $this->record = $this->resolveRecord($record);
        static::authorizeResourceAccess();
        dd($this->record->name);
    }
}
