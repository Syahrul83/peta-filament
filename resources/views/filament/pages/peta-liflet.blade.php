<x-filament-panels::page>

    <div>


        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
            integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />

    </div>


    <!-- <div class="w-full max-w-xs">
        <form class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    coordinate
                </label>
                <input wire:model="coor"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="coordinate" type="text">
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">
                    latitude
                </label>
                <input wire:model="lat"
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                    id="latitude" type="text">
            </div>
            <div class="mb-6">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">
                    Longitude
                </label>
                <input wire:model="lng"
                    class="shadow appearance-none border border-red-500 rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
                    id="longitude" type="text">
                <p class="text-red-500 text-xs italic">Please choose a longitude.</p>
            </div>

        </form>

    </div> -->

    <form wire:submit.prevent="save">
        {{ $this->form }}


        <input type="file" wire:model="images" id="image-input" accept="image/*" multiple>
        <button type="submit">Save</button>
    </form>
    <br>

    @foreach($tampil as $index => $fileName)
    <div class="relative">
        <img src="{{ $fileName }}" alt="Image" class="w-full h-auto">
        <button wire:click="deleteImg({{ $index }})"
            class="absolute top-0 right-0 flex items-center p-2 bg-red-500 text-white rounded">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" viewBox="0 0 20 20" fill="red">
                <path fill-rule="evenodd"
                    d="M16 4a1 1 0 0 0-1-1h-3V2H8v1H5a1 1 0 0 0-1 1v1H3v2h1v9a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V8h1V6h-1V4zm-1 2H5v9a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V6zm-5 1a1 1 0 0 0-1 1v5a1 1 0 0 0 2 0V8a1 1 0 0 0-1-1z"
                    clip-rule="evenodd" />
            </svg>
            <span style="color: red;">Delete</span>
        </button>
    </div>
    @endforeach

    <br>

    <div wire:ignore>
        <div id="map" style="width: 100%; height: 700px;"></div>
    </div>

    <div>

        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
        {{-- <script>
            document.addEventListener('livewire:initialized', () => {
                const map = L.map('map').setView([-1.5910990402674419, 118.14384327799779], 8);

                const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);



                var markerIcon = L.icon({
                    iconUrl: '{{asset("img/leaf-red.png")}}',
                    iconSize: [50, 50],
                })


                var marker = L.marker([-0.5749753487084528, 117.036304782707],
                    {
                        icon: markerIcon,
                        draggable: true
                    }
                )
                    .bindPopup('data 1')
                    .addTo(map)

                // CARA KEDUA
                marker.on('dragend', function () {
                    var coordinate = marker.getLatLng();
                    marker.setLatLng(coordinate, {
                        draggable: true
                    })

                    console.log(coordinate.lat + "," + coordinate.lng);
                    @this.coor = coordinate.lat + "," + coordinate.lng;
                    @this.lat = coordinate.lat;
                    @this.lng = coordinate.lng;

                })
                // CARA KEDUA
            })
        </script> --}}

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
                    marker.setLatLng(e.latlng);
                    // Reverse Geocoding to get the city name
                    fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${e.latlng.lat}&lon=${e.latlng.lng}`)
                        .then(response => response.json())
                        .then(data => {
                            var city = data.address.city || data.address.town || data.address.village;
                            console.log('City:', city);
                            @this.name = city;
                            // You can use the city variable as needed, for example, display it in a popup
                            L.marker(e.latlng).addTo(map)
                                .bindPopup('Your Location (' + radius + ' meters accuracy)<br>City: ' + city).openPopup();
                        })
                        .catch(error => console.error('Reverse Geocoding Error:', error));
                });

                // Handle location error event
                map.on('locationerror', function (e) {
                    console.error('Error getting geolocation:', e.message);
                });

                var markerIcon = L.icon({
                    iconUrl: '{{ asset('img/leaf-red.png') }}',
                    iconSize: [50, 50],
                });

                // Initialize marker with a default position
                var marker = L.marker([0, 0], {
                    icon: markerIcon,
                    draggable: true
                }).bindPopup('data 1').addTo(map);

                // Handle marker dragend event
                marker.on('dragend', function () {
                    var coordinate = marker.getLatLng();
                    marker.setLatLng(coordinate, {
                        draggable: true
                    });

                    console.log(coordinate.lat + "," + coordinate.lng);
                    @this.coor = coordinate.lat + "," + coordinate.lng;
                    @this.lat = coordinate.lat;
                    @this.lng = coordinate.lng;

                    // console.log(city);
                });
            });
        </script>




        <script>
            document.addEventListener('livewire:init', () => {
            document.getElementById('image-input').addEventListener('change', function (e) {
                const files = e.target.files;
                const previewContainer = document.getElementById('preview-container');

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const reader = new FileReader();

                    reader.onload = function (event) {
                        const img = new Image();
                        img.src = event.target.result;

                        img.onload = function () {
                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');

                            // Set desired dimensions (e.g., 80x80 pixels)
                            const targetWidth = 500;

                            // Calculate targetHeight to maintain aspect ratio
                            const aspectRatio = img.width / img.height;
                            const targetHeight = targetWidth / aspectRatio;

                            canvas.width = targetWidth;
                            canvas.height = targetHeight;

                            // Resize the image
                            ctx.drawImage(img, 0, 0, targetWidth, targetHeight);

                            // Display the resized image
                            const resizedDataURL = canvas.toDataURL('image/jpeg');
                            // const previewImg = document.createElement('img');
                            // previewImg.src = resizedDataURL;
                            // previewContainer.appendChild(previewImg);
                            // console.log("resizedDataURL: " + JSON.stringify(resizedDataURL));
                            @this.tampil.push(resizedDataURL);
                            console.log(resizedDataURL);
                        };
                    };

                    reader.readAsDataURL(file);
                }
            });
        });
        </script>

    </div>
</x-filament-panels::page>