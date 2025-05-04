@extends('layouts.admin')

@section('title', 'Nouvel Agriculteur - AgriCarte')

@section('content')
<div class="container-fluid py-4">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Nouvel Agriculteur</h5>
            <a href="{{ route('admin.farmers.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour
            </a>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.farmers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <!-- Informations personnelles -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Informations personnelles</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nom complet</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                           id="name" name="name" value="{{ old('name') }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                           id="email" name="email" value="{{ old('email') }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                           id="phone" name="phone" value="{{ old('phone') }}" required>
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password" class="form-label">Mot de passe</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror"
                                           id="password" name="password" required>
                                    @error('password')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                                    <input type="password" class="form-control"
                                           id="password_confirmation" name="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Informations professionnelles -->
                    <div class="col-md-6">
                        <div class="card mb-4">
                            <div class="card-header">
                                <h6 class="mb-0">Informations professionnelles</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Adresse</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="latitude" class="form-label">Latitude</label>
                                            <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror"
                                                   id="latitude" name="latitude" value="{{ old('latitude') }}" required>
                                            @error('latitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="longitude" class="form-label">Longitude</label>
                                            <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror"
                                                   id="longitude" name="longitude" value="{{ old('longitude') }}" required>
                                            @error('longitude')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="3">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="profile_image" class="form-label">Photo de profil</label>
                                    <input type="file" class="form-control @error('profile_image') is-invalid @enderror"
                                           id="profile_image" name="profile_image" accept="image/*">
                                    @error('profile_image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_active">
                                            Compte actif
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Enregistrer
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
