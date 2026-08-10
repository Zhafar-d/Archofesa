@props([
    'lat'    => config('archofesa.latitude',  -6.995822),
    'lng'    => config('archofesa.longitude', 110.472230),
    'zoom'   => config('archofesa.map_zoom',  19),
    'height' => '320px',
    'label'  => config('archofesa.property_name', 'ARCHOFESA KOST'),
])

@php $mapId = 'lmap_' . substr(md5(uniqid()), 0, 8); @endphp

{{-- Leaflet CSS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>

{{-- Map container: explicit pixel height so Leaflet can measure it --}}
<div id="{{ $mapId }}"
     style="height: {{ $height }}; width: 100%; border-radius: 16px; overflow: hidden; position: relative; z-index: 0;">
</div>

{{-- Leaflet JS --}}
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script>
(function() {
    function initMap() {
        var el = document.getElementById('{{ $mapId }}');
        if (!el || el._leaflet_id) return;

        var map = L.map('{{ $mapId }}', {
            scrollWheelZoom: false,
            zoomControl: true
        }).setView([{{ $lat }}, {{ $lng }}], {{ $zoom }});

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        var icon = L.divIcon({
            className: '',
            html: '<div style="background:#c9a227;width:18px;height:18px;border-radius:50%;border:3px solid #fff;box-shadow:0 2px 8px rgba(0,0,0,.5);"></div>',
            iconSize: [18, 18],
            iconAnchor: [9, 9],
        });

        L.marker([{{ $lat }}, {{ $lng }}], { icon: icon })
            .addTo(map)
            .bindPopup('<strong>{{ $label }}</strong><br>Pedurungan, Semarang')
            .openPopup();

        // Force re-check size after render
        setTimeout(function() { map.invalidateSize(); }, 200);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMap);
    } else {
        initMap();
    }
}());
</script>
