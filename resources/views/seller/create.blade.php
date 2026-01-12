@extends('layouts.main')

@section('content')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<div class="auth-container" style="max-width: 600px;">
    <div class="auth-header">
        <h2>List your property</h2>
    </div>

    <form method="POST" action="/seller/properties" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label for="title">Property Title</label>
            <input type="text" id="title" name="title" required placeholder="e.g. Cozy Cottage" value="{{ old('title') }}">
            @error('title') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required placeholder="e.g. Islamabad, ISB" value="{{ old('location') }}">
            @error('location') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div class="form-group">
            <label for="price">Price (Rs per night)</label>
            <input type="number" id="price" name="price" step="0.01" required value="{{ old('price') }}">
            @error('price') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <div style="display: flex; gap: 1rem;">
            <div class="form-group" style="flex: 1;">
                <label for="rooms">Rooms</label>
                <input type="number" id="rooms" name="rooms" min="1" required value="{{ old('rooms') }}">
                @error('rooms') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
            <div class="form-group" style="flex: 1;">
                <label for="bathrooms">Bathrooms</label>
                <input type="number" id="bathrooms" name="bathrooms" min="1" required value="{{ old('bathrooms') }}">
                @error('bathrooms') <div class="error-msg">{{ $message }}</div> @enderror
            </div>
        </div>

        <div class="form-group">
            <label for="image">Upload Photo</label>
            <input type="file" id="image" name="image" required accept="image/*">
            @error('image') <div class="error-msg">{{ $message }}</div> @enderror
        </div>
        
        <!-- Map Integration -->
        <div class="form-group">
            <label>Select Location on Map</label>
            <button type="button" id="use-my-location" style="margin-bottom:0.5rem; padding:0.4rem 0.8rem; background:#f0f0f0; border:1px solid #ccc; border-radius:4px; cursor:pointer;">Use My Location</button>
            <div id="map" style="height: 300px; width: 100%; border-radius: 8px; margin-bottom: 0.5rem; border: 1px solid #ddd;"></div>
            <p style="font-size: 0.85rem; color: #666;">Click on the map to set location.</p>
            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
            
            <div style="display: flex; gap: 1rem; margin-top: 0.5rem;">
                <input type="text" id="lat_display" placeholder="Latitude" readonly style="background: #f0f0f0;">
                <input type="text" id="lng_display" placeholder="Longitude" readonly style="background: #f0f0f0;">
            </div>
            @error('latitude') <div class="error-msg">Location is required.</div> @enderror
        </div>

        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5" required style="width:100%; padding:0.8rem; border:1px solid #b0b0b0; border-radius:8px;">{{ old('description') }}</textarea>
            @error('description') <div class="error-msg">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn-primary btn-block">List Property</button>
        <a href="/seller/dashboard" style="display:block; text-align:center; margin-top:1rem; color:#777;">Cancel</a>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Default to New York or previous selection
        var lat = {{ old('latitude') ?? 40.7128 }};
        var lng = {{ old('longitude') ?? -74.0060 }};
        
        var map = L.map('map').setView([lat, lng], 13);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        var marker;

        // If old values exist, show marker
        if('{{ old("latitude") }}') {
            marker = L.marker([lat, lng]).addTo(map);
            document.getElementById('lat_display').value = lat;
            document.getElementById('lng_display').value = lng;
        }

        function onMapClick(e) {
            var lat = e.latlng.lat.toFixed(6);
            var lng = e.latlng.lng.toFixed(6);

            if (marker) {
                marker.setLatLng(e.latlng);
            } else {
                marker = L.marker(e.latlng).addTo(map);
            }

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;
            document.getElementById('lat_display').value = lat;
            document.getElementById('lng_display').value = lng;
        }

        map.on('click', onMapClick);

        // Geolocation
        document.getElementById('use-my-location').addEventListener('click', function() {
            if (!navigator.geolocation) {
                alert('Geolocation is not supported by your browser');
                return;
            }

            navigator.geolocation.getCurrentPosition(function(position) {
                var lat = position.coords.latitude.toFixed(6);
                var lng = position.coords.longitude.toFixed(6);
                var latlng = [lat, lng];

                if (marker) {
                    marker.setLatLng(latlng);
                } else {
                    marker = L.marker(latlng).addTo(map);
                }
                
                map.setView(latlng, 15);

                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;
                document.getElementById('lat_display').value = lat;
                document.getElementById('lng_display').value = lng;

            }, function() {
                alert('Unable to retrieve your location');
            });
        });
    });
</script>
@endsection
