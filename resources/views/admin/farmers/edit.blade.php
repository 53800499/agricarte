@extends('layouts.admin')

@section('title', 'Modifier Producteur')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Modifier Producteur</h1>
            <a href="{{ route('admin.farmers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.farmers.update', $farmer) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Informations personnelles -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0 text-primary">Informations personnelles</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom complet</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name', $farmer->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-envelope"></i>
                                            </span>
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                   id="email" name="email" value="{{ old('email', $farmer->email) }}" required>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="phone" class="form-label">Téléphone</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-phone"></i>
                                            </span>
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone" name="phone" value="{{ old('phone', $farmer->phone) }}">
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password" class="form-label">Nouveau mot de passe</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                                   id="password" name="password">
                                            @error('password')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">Laisser vide pour ne pas changer le mot de passe</small>
                                    </div>

                                    <div class="mb-3">
                                        <label for="password_confirmation" class="form-label">Confirmer le nouveau mot de passe</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-lock"></i>
                                            </span>
                                            <input type="password" class="form-control"
                                                   id="password_confirmation" name="password_confirmation">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Adresse et localisation -->
                        <div class="col-md-6">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0 text-primary">Adresse et localisation</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Adresse</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-map-marker-alt"></i>
                                            </span>
                                            <input type="text" class="form-control @error('address') is-invalid @enderror"
                                                   id="address" name="address" value="{{ old('address', $farmer->address) }}" required>
                                            @error('address')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- <div class="row">
                                        <div class="col-md-6"> --}}
                                            <div class="mb-3">
                                                <label for="city" class="form-label">Ville</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-city"></i>
                                                    </span>
                                                    <input type="text" class="form-control @error('city') is-invalid @enderror"
                                                           id="city" name="city" value="{{ old('city', $farmer->city) }}">
                                                    @error('city')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                         {{--</div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="postal_code" class="form-label">Code postal</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-mail-bulk"></i>
                                                    </span>
                                                    <input type="text" class="form-control @error('postal_code') is-invalid @enderror"
                                                           id="postal_code" name="postal_code" value="{{ old('postal_code', $farmer->postal_code) }}">
                                                    @error('postal_code')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div> 
                                    </div>--}}

                                    {{-- <div class="mb-3">
                                        <label for="country" class="form-label">Pays</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-globe"></i>
                                            </span>
                                            <input type="text" class="form-control @error('country') is-invalid @enderror"
                                                   id="country" name="country" value="{{ old('country', $farmer->country) }}">
                                                    @error('country')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                        </div>
                                    </div> --}}

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="latitude" class="form-label">Latitude</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-map-marked-alt"></i>
                                                    </span>
                                                    <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror"
                                                           id="latitude" name="latitude" value="{{ old('latitude', $farmer->latitude) }}" required>
                                                    @error('latitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="longitude" class="form-label">Longitude</label>
                                                <div class="input-group">
                                                    <span class="input-group-text bg-light">
                                                        <i class="fas fa-map-marked-alt"></i>
                                                    </span>
                                                    <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror"
                                                           id="longitude" name="longitude" value="{{ old('longitude', $farmer->longitude) }}" required>
                                                    @error('longitude')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Informations supplémentaires -->
                        <div class="col-12">
                            <div class="card mb-4">
                                <div class="card-header">
                                    <h6 class="mb-0 text-primary">Informations supplémentaires</h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-align-left"></i>
                                            </span>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                      id="description" name="description" rows="3">{{ old('description', $farmer->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="profile_image" class="form-label">Photo de profil</label>
                                        @if($farmer->profile_image)
                                            <div class="mb-2">
                                                <img src="{{ asset('storage/' . $farmer->profile_image) }}"
                                                     alt="Photo de profil actuelle"
                                                     class="img-thumbnail"
                                                     style="max-width: 150px;">
                                            </div>
                                        @endif
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-image"></i>
                                            </span>
                                            <input type="file" class="form-control @error('profile_image') is-invalid @enderror"
                                                   id="profile_image" name="profile_image" accept="image/*">
                                            @error('profile_image')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <small class="text-muted">Laisser vide pour conserver l'image actuelle</small>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                                                   value="1" {{ old('is_active', $farmer->is_active) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_active">
                                                Compte actif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Géocodage automatique de l'adresse
    const addressInput = document.getElementById('address');
    const cityInput = document.getElementById('city');
    const postalCodeInput = document.getElementById('postal_code');
    const countryInput = document.getElementById('country');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');

    function updateCoordinates() {
        const address = `${addressInput.value}, ${postalCodeInput.value} ${cityInput.value}, ${countryInput.value}`;
        if (address.trim().length > 0) {
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                .then(response => response.json())
                .then(data => {
                    if (data && data[0]) {
                        latitudeInput.value = data[0].lat;
                        longitudeInput.value = data[0].lon;
                    }
                })
                .catch(error => console.error('Erreur de géocodage:', error));
        }
    }

    // Écouter les changements sur les champs d'adresse
    [addressInput, cityInput, postalCodeInput, countryInput].forEach(input => {
        input.addEventListener('change', updateCoordinates);
    });
});
</script>
@endpush
