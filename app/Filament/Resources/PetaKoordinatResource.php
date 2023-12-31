<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Pages\PetaLiflet;
use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Models\PetaKoordiant;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PetaKoordinatResource\Pages;
use App\Filament\Resources\PetaKoordinatResource\RelationManagers;
use App\Filament\Resources\PetaKoordinatResource\Pages\EditPetaKoordinat;
use App\Filament\Resources\PetaKoordinatResource\Pages\ListPetaKoordinats;
use App\Filament\Resources\PetaKoordinatResource\Pages\CreatePetaKoordinat;

class PetaKoordinatResource extends Resource
{
    protected static ?string $model = PetaKoordiant::class;

    protected static ?string $navigationIcon = 'heroicon-o-map-pin';

    protected static ?string $navigationLabel = 'Tambah Peta Karantina ';

    protected ?string $heading = 'Tambah Peta Karantina';
    protected static ?string $pluralModelLabel = 'Tambah Peta Karantina';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([


            ]);
    }
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('name')->label('Loaksi peta'),
                TextColumn::make('lokasi')->label('Gedung / Tempat'),
                TextColumn::make('coor')->label('Koordinat'),
                TextColumn::make('pembuat')->default(function (Model $record) {
                    if ($record->user_id == 1) {
                        return 'Admin';
                    } else {
                        return 'User';
                    }
                })->label('Pembuat'),

            ])->defaultSort('id', 'desc')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()
                    ->disabled(fn(PetaKoordiant $record) => auth()->user()->id != $record->user_id),
                // Action::make('show')
                //     ->url(fn(PetaKoordiant $record): string => route('filament.admin.resources.peta-koordinats.show', ['record' => $record->id]))
                //     ->icon('heroicon-m-pencil-square')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPetaKoordinats::route('/'),
            'create' => Pages\CreatePetaKoordinat::route('/create'),
            'edit' => Pages\ShowPetaKoordinat::route('/{record}/show'),
            // 'edit' => Pages\EditPetaKoordinat::route('/{record}/edit'),
        ];
    }
}
