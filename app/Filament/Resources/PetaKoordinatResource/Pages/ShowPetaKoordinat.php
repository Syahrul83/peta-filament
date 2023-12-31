<?php

namespace App\Filament\Resources\PetaKoordinatResource\Pages;

use Ramsey\Uuid\Uuid;
use App\Models\Gambar;
use Filament\Forms\Form;
use App\Models\PetaKoordiant;
use Filament\Resources\Pages\Page;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\MarkdownEditor;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Filament\Forms\Concerns\InteractsWithForms;
use App\Filament\Resources\PetaKoordinatResource;
use Filament\Resources\Pages\Concerns\InteractsWithRecord;

class ShowPetaKoordinat extends Page implements HasForms
{

    use InteractsWithForms, InteractsWithRecord;
    protected static string $resource = PetaKoordinatResource::class;

    protected static string $view = 'filament.resources.peta-koordinat-resource.pages.show-peta-koordinat';


    public $coor = '';
    public $name;
    public $lokasi;

    public $lat;
    public $lng;
    public $ket;
    public $images = [];
    public ?array $data = [];
    public $gambar;
    public $tampil = [];

    public function mount(int|string $record): void
    {

        $this->record = $this->resolveRecord($record);
        static::authorizeResourceAccess();


        $this->name = $this->record->name;
        $this->lokasi = $this->record->lokasi;
        $this->coor = $this->record->coor->latitude . ',' . $this->record->coor->longitude;
        $this->lat = $this->record->coor->latitude;
        $this->lng = $this->record->coor->longitude;
        $this->ket = $this->record->ket;
        $this->gambar = Gambar::where('peta_koordiant_id', $this->record->id)->get();

    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Lokasi Peta')
                    ->disabled()
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

    public function updateTampil($resizedDataURL)
    {
        array_push($this->tampil, $resizedDataURL);
    }

    public function deleteGmbr($id)
    {


        $file = Gambar::where('id', $id)->first();
        $filePath = public_path($file->path);


        if (file_exists($filePath)) {
            unlink($filePath);

        }

        $file->delete();
        $this->gambar = Gambar::where('peta_koordiant_id', $this->record->id)->get();
        //erase file from disk and database code



    }


    public function deleteImg($index)
    {

        // deleler tampilan array kode php

        unset($this->tampil[$index]);

    }
    public function save()
    {


        $peta = PetaKoordiant::findOrFail($this->record->id);
        $peta->name = $this->name;
        $peta->lokasi = $this->lokasi;
        $peta->coor = new Point($this->lat, $this->lng);
        $peta->ket = $this->ket;
        $peta->save();

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
                'peta_koordiant_id' => $this->record->id,
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
