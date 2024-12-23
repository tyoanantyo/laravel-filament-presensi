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
