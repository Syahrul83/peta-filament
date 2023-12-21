<?php

namespace App\Filament\Resources\PetaKoordinatResource\Pages;

use App\Filament\Resources\PetaKoordinatResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPetaKoordinats extends ListRecords
{
    protected static string $resource = PetaKoordinatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
