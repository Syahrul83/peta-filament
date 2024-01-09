<?php

namespace App\Filament\Pages;


use Filament\Forms\Get;
use Filament\Forms\Form;
use Filament\Pages\Page;
use App\Models\PetaKoordiant;
use Livewire\WithFileUploads;
use Livewire\Component as Livewire;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Facades\Storage;
use Filament\Forms\Components\TextInput;
use MatanYadaev\EloquentSpatial\Objects\Point;
use Filament\Forms\Concerns\InteractsWithForms;
use Livewire\Attributes\On;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;


class PetaLiflet extends Page implements HasForms
{
    use InteractsWithForms, WithFileUploads;


    public $coor = '';
    public $name;
    public $lat;
    public $lng;
    public $images = [];
    public ?array $data = [];

    public $tampil = [];

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static bool $shouldRegisterNavigation = false;
    protected static string $view = 'filament.pages.peta-liflet';

    public function mount(): void
    {
        $this->form->fill();
        // abort_unless(false, 403);
    }

    public function form(Form $form): Form
    {

        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Kota')
                    ->required(),
                TextInput::make('coor')
                    ->label('cordinate')
                    ->required(),
                TextInput::make('lat')
                    ->label('latitude')
                    ->required(),
                TextInput::make('lng')
                    ->label('langitude')
                    ->required(),
                // TextInput::make('image')
                //     ->label('gambar')
                //     ->required(),
            ]);

    }


    #[On('update-images-uploaded')]
    public function updateImagesUploaded($resizedImages)
    {
        Log::debug('Received resized images:', $resizedImages);

        // Update the component property
        $this->images = $resizedImages;
        // $this->nilai = $value;

        // Log the updated property
        Log::debug('Updated images property:', $this->images);
    }


    public function deleteImg($index)
    {

        // deleler tampilan array kode php

        unset($this->tampil[$index]);

    }
    public function save()
    {

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
            PetaKoordiant::create([
                'name' => $this->name,
                'coor' => new Point($this->lat, $this->lng),
                'path' => $directory . '/' . $fileName,
            ]);
        }
        // Clear the uploaded images after processing
        $this->images = [];
        $this->tampil = [];
        $this->form->fill();
    }




}
