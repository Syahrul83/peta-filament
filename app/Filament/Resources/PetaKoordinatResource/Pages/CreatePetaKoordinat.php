<?php

namespace App\Filament\Resources\PetaKoordinatResource\Pages;

use Ramsey\Uuid\Uuid;
use App\Models\Gambar;
use Filament\Forms\Form;
use App\Models\PetaKoordiant;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Resources\PetaKoordinatResource;

class CreatePetaKoordinat extends Page implements HasForms
{

    use InteractsWithForms;

    protected static string $resource = PetaKoordinatResource::class;

    protected static string $view = 'filament.resources.peta-koordinat-resource.pages.create-peta-koordinat';

    public $coor = '';
    public $name;
    public $lokasi;

    public $lat;
    public $lng;
    public $ket;
    public $images = [];
    public ?array $data = [];

    public $tampil = [];

    public function mount(): void
    {
        $this->form->fill();

        static::authorizeResourceAccess();
    }
    public function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Lokasi Peta')
                    ->required(),
                TextInput::make('coor')
                    ->label('cordinate')
                    ->required(),
                TextInput::make('lokasi')
                    ->label('Gedung / Tempat')
                    ->required(),
                MarkdownEditor::make('ket')
                    ->label('Keterangan. (optional)')
            ]);

    }

    public function deleteImg($index)
    {

        // deleler tampilan array kode php

        unset($this->tampil[$index]);

    }
    public function save()
    {


        $peta = PetaKoordiant::create([
            'name' => $this->name,
            'lokasi' => $this->lokasi,
            'coor' => new Point($this->lat, $this->lng),
            'ket' => $this->ket,
        ]);

        $directory = 'gambar';

        foreach ($this->tampil as $imageData) {
            $fileName = Uuid::uuid4() . '-img.txt';
            $contents = $imageData;


            // Create the directory if it doesn't exist
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }

            // Set the path to the public/img directory
            $path = public_path($directory) . '/' . $fileName;

            // Write the contents to the file
            file_put_contents($path, $contents);

            // Create a new PetaKoordiant instance

            Gambar::create([
                'peta_koordiant_id' => $peta->id,
                'path' => $directory . '/' . $fileName,
            ]);

        }
        // Clear the uploaded images after processing
        $this->images = [];
        $this->tampil = [];
        $this->form->fill();
        return redirect()->route('filament.admin.resources.peta-koordinats.index');
    }
}
