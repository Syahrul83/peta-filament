<x-filament-panels::page>
    <div>


        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

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
        <script>
            document.addEventListener('livewire:initialized', () => {
                const map = L.map('map').setView([-1.5910990402674419, 118.14384327799779], 8);

                const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                var markerIcon = L.icon({
                    iconUrl: '{{ asset("img/leaf-red.png") }}',
                    iconSize: [50, 50],
                })

                @foreach($peta as $index => $pet)

                L.marker([{{ $pet-> coor -> latitude }}, {{ $pet-> coor -> longitude }}], { icon: markerIcon }).bindPopup('<strong>{{ $pet->name }}</strong><br><img src="{{ $pet->image }}" alt="{{ $pet->name }}" style="max-width: 100px;">').addTo(map);
            @endforeach
            })
        </script>

    </div>

</x-filament-panels::page>