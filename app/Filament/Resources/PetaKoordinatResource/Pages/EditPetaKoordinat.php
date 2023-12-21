<?php

namespace App\Filament\Resources\PetaKoordinatResource\Pages;

use App\Filament\Resources\PetaKoordinatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPetaKoordinat extends EditRecord
{
    protected static string $resource = PetaKoordinatResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
