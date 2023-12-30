<x-filament-panels::page>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    {{--
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" /> --}}

    {{--
    <link rel="stylesheet" href="https://opengeo.tech/maps/leaflet-search/src/leaflet-search.css" /> --}}
    <link rel="stylesheet" href="{{ asset('peta/geo.css') }}" />
    <link rel="stylesheet" href="{{ asset('peta/peta.css') }}" />
    <style>
        #map {
            height: 400px;
        }

        .leaflet-control-geocoder-form input[type="text"] {
            color: black;
        }


        #geocoder-input {
            height: 30px;
            color: black;
            margin-top: 4px;
        }


        .leaflet-control-search {
            color: #000000;

        }

        /* peta Geocoder CSS - Hidden */
        .leaflet-control-geocoder {
            display: none;
            /* Hide the geocoder control */
        }

        .leaflet-control-geocoder.search-exp {
            display: none;
            /* Hide the expanded state */
        }

        .leaflet-control-geocoder .leaflet-control-geocoder-form input {
            display: none;
            /* Hide the geocoder input */
        }

        .leaflet-control-geocoder.search-load .leaflet-control-geocoder-form input {
            display: none;
            /* Hide the loading state */
        }

        .leaflet-control-geocoder .leaflet-control-geocoder-form button {
            display: none;
            /* Hide the geocoder button */
        }

        .leaflet-control-geocoder .leaflet-control-geocoder-icon {
            display: none;
            /* Hide the geocoder icon */
        }

        /* Additional styles for the geocoder */
        .leaflet-control-geocoder .leaflet-control-geocoder-alternatives {
            display: none;
            /* Hide the alternatives */
        }

        .leaflet-control-geocoder .leaflet-control-geocoder-alternatives li {
            display: none;
            /* Hide the alternative list items */
        }

        .leaflet-control-geocoder .leaflet-control-geocoder-address-context {
            display: none;
            /* Hide the address context */
        }

        .leaflet-control-geocoder .leaflet-control-geocoder-address-detail {
            display: none;
            /* Hide the address detail */
        }

        .leaflet-control-geocoder .leaflet-control-geocoder-error {
            display: none;
            /* Hide the geocoder error */
        }

        /* button geocoder   */
        #geocoder-button {
            display: block;
            float: right;
            width: 30px;
            height: 30px;
            margin-top: 4px;
            /* Add margin-top to align with the input field */
            background: url('../img/search-icon.png') no-repeat 4px 4px #fff;
            border-radius: 4px;
        }

        #geocoder-button:hover {
            /* Add styles for the hover state if needed */
            /* For example, you can add a box shadow or change the opacity */
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
        }
    </style>



    <div>
        @if (!empty($datas->name))
        <h1> Lokasi /gedung : {!! $datas->lokasi??null !!} </h1>
        <br>
        <h5> Kota / Daerah : {!! $datas->name??null !!} </h5>
        <br>
        @endif

        <div style="display: flex; flex-wrap: wrap;">
            @foreach($marks as $index => $fileName)
            @php
            $filePath = asset($fileName->path);
            $contents = file_get_contents($filePath);
            @endphp
            <div class="relative" style="width: 300px; margin-right: 10px;">
                <img src="{{ $contents }}" class="w-full h-auto" style="max-width: 100%; max-height: 300px;"
                    alt="{{ $fileName->path }}">

            </div>
            @endforeach
        </div>
    </div>
    @if (!empty($datas->name))
    <div>
        <br>
        Keterangan :
        <br>
        {!! $datas->ket??null !!}
    </div>
    @endif
    <div class="flex flex-row">
        <div class="p-3" wire:ignore>
            <x-filament::button color="success" onclick="location.reload();">
                Refresh / Restat Map
            </x-filament::button>
        </div>
        <div class="p-3" wire:ignore>
            <input type="text" id="geocoder-input" placeholder="Pencarian Kota">
            <button id="geocoder-button"></button>
        </div>

        <div class="p-3" wire:ignore>
            <div id="findbox"></div>
        </div>


    </div>

    <div wire:ignore>
        <div id="map" style="width: 100%; height: 700px;  z-index: -1;"></div>
    </div>

    <div>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
        <script src="https://unpkg.com/leaflet-search"></script>
        <script src="https://unpkg.com/jquery@3.3.1/dist/jquery.js"></script>

        <script>
            document.addEventListener('livewire:initialized', () => {
                var map = L.map('map').setView([0, 0], 13);

L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; OpenStreetMap contributors'
}).addTo(map);

// Automatically get and update the current location
map.locate({
    setView: true,
    maxZoom: 13
});

// Handle location found event
map.on('locationfound', function (e) {
    var radius = e.accuracy / 2;

    L.marker(e.latlng).addTo(map)
        .bindPopup('Your Location (' + radius + ' meters accuracy)').openPopup();

    L.circle(e.latlng, radius).addTo(map);

    // Reverse Geocoding to get the city name
    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
        .then(response => response.json())
        .then(data => {
            var city = data.address.city || data.address.town || data.address.village;
            console.log('City:', city);
            // @this.name = city; // Uncomment and modify this line to use with Livewire

            // You can use the city variable as needed, for example, display it in a popup
            L.marker(e.latlng).addTo(map)
                .bindPopup('Your Location (' + radius + ' meters accuracy)<br>Location: ' + city).openPopup();
        })
        .catch(error => console.error('Reverse Geocoding Error:', error));
});

// Handle location error event
map.on('locationerror', function (e) {
    console.error('Error getting geolocation:', e.message);
});

var markerIcon = L.icon({
    iconUrl: '{{ asset("img/leaf-red.png") }}',
    iconSize: [50, 50],
});

var markersLayer = new L.LayerGroup();  // layer containing searched elements
map.addLayer(markersLayer);

var controlSearch = new L.Control.Search({
    container: 'findbox',
    layer: markersLayer,
    initial: false,
    collapsed: false,
    textPlaceholder: 'Gedung/Lokasi markers...' // Add the placeholder text here
});
map.addControl(controlSearch);

@foreach($peta as $index => $pet)
    @php
        $lok = DB::table('gambars')->where('peta_koordiant_id', $pet->id)->first();
        $filePath = $lok ? asset($lok->path) : null;
        $contents = null;
        if ($filePath) {
            $contents = file_get_contents($filePath);
        }
    @endphp

    var title = "{{ $pet->lokasi }}";
    var latlng = [{{ $pet->coor->latitude }}, {{ $pet->coor->longitude }}];

    var marker = new L.Marker(
        new L.latLng(latlng),
        {
            title: title,
            icon: markerIcon // Set the custom marker icon
        }
    );

    var popupContent = '<strong>{{ $pet->lokasi }}</strong><br><img src="{{ $contents }}" alt="{{ $pet->name }}"><br><a href="#" class="fi-btn relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus-visible:ring-2 rounded-lg fi-color-custom fi-btn-color-primary fi-size-md fi-btn-size-md gap-1.5 px-3 py-2 text-sm inline-grid shadow-sm bg-custom-600 text-white hover:bg-custom-500 dark:bg-custom-500 dark:hover:bg-custom-400 focus-visible:ring-custom-500/50 dark:focus-visible:ring-custom-400/50" style="--c-400:var(--primary-400);--c-500:var(--primary-500);--c-600:var(--primary-600);">Selengkapnya</a>';

    var petId = "{{ $pet->id }}";
    var button = document.createElement('div');
    button.innerHTML = popupContent;
    button.querySelector('.fi-btn').addEventListener('click', (function(id) {
        return function() {
            // console.log(id);
        //    @this.idx = id;
           @this.set('idx',id);
            console.log( @this.idx );
        };
    })(petId));

    marker.bindPopup(button, {
        closeButton: true,
        minWidth: 200
    });

    markersLayer.addLayer(marker);
@endforeach

// Add search control
// L.Control.geocoder().addTo(map);

var geocoderControl = L.Control.geocoder({
    defaultMarkGeocode: false,
    placeholder: 'Pencarian Kota',
}).addTo(map);

// Event listener for when a location is selected
geocoderControl.on('markgeocode', function (e) {
    var latlng = e.geocode.center;
    // Do something with the selected location, e.g., pan to it on the map
    map.panTo(latlng);

    // Add a marker at the selected location
    var marker = L.marker(latlng).addTo(map);
    marker.bindPopup('Selected Location: ' + e.geocode.name).openPopup();
});

// Handle button click to trigger geocoding
document.getElementById('geocoder-button').addEventListener('click', function () {
    var query = document.getElementById('geocoder-input').value;

    // Trigger the geocoding
    geocoderControl.options.geocoder.geocode(query, function (results) {
        if (results.length > 0) {
            var latlng = results[0].center;
            map.panTo(latlng);

            // Add a marker at the geocoded location
            var marker = L.marker(latlng).addTo(map);
            marker.bindPopup('Geocoded Location: ' + results[0].name).openPopup();
        } else {
            console.error('Location not found');
        }
    });
});



    })
        </script>

    </div>

</x-filament-panels::page>