<div>
    <div class="container mx-auto">
        <div class="bg-white p-6 rounded-lg mt-3 shadow-lg">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div>
                    <h2 class="text-2xl font-bold mb-2">Informasi Pegawai</h2>
                    <div class="bg-gray-100 p-4 rounded-lg">
                        <p class="mb-2"><strong>Nama Pegawai : </strong> {{ Auth::user()->name }}</p>
                        <p class="mb-2"><strong>Kantor : </strong>{{ $schedule->office->name }}</p>
                        <p class="mb-2"><strong>Shift :
                            </strong>{{ $schedule->shift->name }} ({{ $schedule->shift->start_time }} -
                            {{ $schedule->shift->end_time }})</p>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="text-l font-bold mb-2">Jam masuk : <p>07:00</p>
                            </h4>
                        </div>
                        <div class="bg-gray-100 p-4 rounded-lg">
                            <h4 class="text-l font-bold mb-2">Jam pulang : <p>16:00</p>
                            </h4>
                        </div>
                    </div>
                </div>
                <div>
                    <h2 class="text-2xl font-bold mb-2">Presensi</h2>
                    <div id="map" class="mb-4 rounded-lg border border-gray-300"wire:ignore></div>
                    <button type="button" onclick="tagLocation()"
                        class="px-4 py-2 bg-blue-500 text-white rounded-full">Tag
                        location</button>
                    @if ($insideRadius)
                        <button type="button" class="px-4 py-2 bg-green-500 text-white rounded-full">Submit
                            presensi</button>
                    @endif

                </div>
            </div>
        </div>
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        let map;
        let lat;
        let lng;
        const office = [{{ $schedule->office->latitude }}, {{ $schedule->office->longitude }}];
        const radius = {{ $schedule->office->radius }};
        let component;
        let marker;
        document.addEventListener('livewire:initialized', function() {
            component = @this;
            map = L.map('map').setView([{{ $schedule->office->latitude }},
                {{ $schedule->office->longitude }}
            ], 15);
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);

            const circle = L.circle(office, {
                color: 'red',
                fillColor: '#f03',
                fillOpacity: 0.5,
                radius: radius
            }).addTo(map);
        })

        function tagLocation() {
            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition(function(position) {
                    lat = position.coords.latitude;
                    lng = position.coords.longitude;


                    if (marker) {
                        map.removeLayer(marker);
                    }

                    marker = L.marker([lat, lng]).addTo(map);
                    map.setView([lat, lng], 75);

                    if (isWithinRadius(lat, lng, office, radius)) {
                        component.set('insideRadius', true);
                    } else {
                        alert('Anda di luar radius');
                    }
                })

            } else {
                alert('Tidak bisa get location');
            }
        }

        function isWithinRadius(lat, lng, center, radius) {
            let distance = map.distance([lat, lng], center);
            return distance <= radius;
        }
    </script>
</div>
