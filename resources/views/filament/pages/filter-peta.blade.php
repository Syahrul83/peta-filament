<x-filament-panels::page>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
    <link rel="stylesheet" href="https://opengeo.tech/maps/leaflet-search/src/leaflet-search.css" />
    <style>
        #map {
            height: 400px;
        }

        .leaflet-control-geocoder-form input[type="text"] {
            color: black;
        }

        .leaflet-control-search {
            background-color: #ffffff;
            color: #000000;
            border-radius: 5px;
            padding: 5px;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }
    </style>
    <div>



    </div>

    <div wire:ignore>
        <div id="map" style="width: 100%; height: 700px;"></div>
    </div>
    @foreach($peta as $index => $petas)
    @php
    var_dump($index);
    @endphp
    {{ $petas-> coor -> latitude }}
    @endforeach
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
    layer: markersLayer,
    initial: false,
    position: 'topleft',
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

    marker = new L.Marker(new L.latLng(latlng), { title: title }); // set property for searching
    marker.bindPopup('<strong>{{ $pet->lokasi }}</strong><br><img src="{{ $contents }}" alt="{{ $pet->name }}">');
    markersLayer.addLayer(marker);
@endforeach




// Add search control
L.Control.geocoder().addTo(map);
    })
        </script>

    </div>

</x-filament-panels::page>
