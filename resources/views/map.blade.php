@extends('layouts.app')

@section('title', 'Carte des producteurs - AgriCarte')

@section('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <style>
        #map {
            height: 600px;
            width: 100%;
            border-radius: 8px;
        }
        .producer-card {
            cursor: pointer;
            transition: all 0.3s;
        }
        .producer-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
    </style>
@endsection

@section('content')
<section class="hero-section bg-light">
        <div class="container text-center hero-content">
            <h1 class="display-4 fw-bold mb-4">Carte des producteurs</h1>
        </div>
    </section>
    <div class="container py-5">        
        <div class="row mb-4">
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" placeholder="Rechercher par lieu...">
                    <button class="btn btn-success" type="button">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-md-end mt-3 mt-md-0">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="legumesCheck" value="legumes" checked>
                        <label class="form-check-label" for="legumesCheck">Légumes</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="fruitsCheck" value="fruits" checked>
                        <label class="form-check-label" for="fruitsCheck">Fruits</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="laitCheck" value="lait">
                        <label  type="checkbox" id="laitCheck" value="lait">
                        <label class="form-check-label" for="laitCheck">Produits laitiers</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="viandeCheck" value="viande">
                        <label class="form-check-label" for="viandeCheck">Viandes</label>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div id="map"></div>
            </div>
            <div class="col-md-4">
                <h4 class="mb-3">Producteurs à proximité</h4>
                <div class="producers-list">
                    @foreach($producers as $producer)
                        <div class="card producer-card mb-3" data-id="{{ $producer['id'] }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $producer['name'] }}</h5>
                                <p class="card-text">
                                    <i class="fas fa-map-marker-alt text-success me-2"></i>{{ $producer['address'] }}
                                </p>
                                <p class="card-text">
                                    <strong>Produits :</strong> {{ implode(', ', $producer['products']) }}
                                </p>
                                <a href="#" class="btn btn-sm btn-outline-success">Voir détails</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialiser la carte
            var map = L.map('map').setView([43.296482, 5.369780], 10);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            }).addTo(map);
            
            // Ajouter les marqueurs pour chaque producteur
            var producers = @json($producers);
            
            producers.forEach(function(producer) {
                var marker = L.marker([producer.lat, producer.lng]).addTo(map);
                marker.bindPopup("<b>" + producer.name + "</b><br>" + producer.address + "<br><a href='#' class='text-success'>Voir détails</a>");
                
                // Lier le marqueur à la carte du producteur
                document.querySelector('.producer-card[data-id="' + producer.id + '"]').addEventListener('click', function() {
                    marker.openPopup();
                    map.setView([producer.lat, producer.lng], 13);
                });
            });
        });
    </script>
@endsection
