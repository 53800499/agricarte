@extends('layouts.admin')

@section('title', "Profil du producteur")

@section('content')
    <div class="container-fluid">
        @foreach (['success', 'info', 'warning', 'danger'] as $msg)
            @if (session($msg))
                <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                    {{ session($msg) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endforeach
        <form action="{{ route('profile.update', $user) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0 text-gray-800">Mon profile</h1>
                <button class="btn btn-success" type="submit">
                    <i class="fas fa-save"></i> Modifier producteur
                </button>
            </div>
            <div class="row mb-4 gap-4">
                <div class="card shadow-sm p-4">

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <div class="justify-content-center text-center my-1">
                                <img src="{{ $user->profile_image ? asset('storage/profile_images/' . $user->profile_image) : asset('images/profile.jpg') }}"
                                    alt="Photo de {{ $user->name }}" class="rounded img-thumbnail" width="150" height="150">
                            </div>
                            <input type="file" class="form-control @error('profile_image') is-invalid @enderror"
                                id="profile_image" name="profile_image" accept="image/*">
                            <small class="text-muted">Selectionnez autre image pour metrre à jour l'ancienne'</small>
                            @error('profile_image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6 mt-4">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="name" class="form-label">Nom</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror"
                                        id="name" name="name" value="{{ old('name', $user->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="mb-3 col-md-6">
                                    <label for="phone" class="form-label">Téléphone</label>
                                    <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                        id="phone" name="phone" value="{{ old('phone', $user->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="row">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror"
                                        id="email" name="email" value="{{ old('email', $user->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label for="password" class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                id="password" name="password">
                            <small class="text-muted">Laisser vide pour ne pas changer le mot de passe</small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <label for="password_confirmation" class="form-label">Confirmer le mot de passe</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="address" class="form-label">Adresse</label>
                            <input type="text" step="any" class="form-control @error('address') is-invalid @enderror"
                                id="address" name="address" value="{{ old('address', $user->address) }}">
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="col-md-4 mb-3">
                            <label for="latitude" class="form-label">Latitude</label>
                            <input type="number" step="any"
                                class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude"
                                value="{{ old('latitude', $user->latitude) }}">
                            @error('latitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="longitude" class="form-label">Longitude</label>
                            <input type="number" step="any"
                                class="form-control @error('longitude') is-invalid @enderror" id="longitude"
                                name="longitude" value="{{ old('longitude', $user->longitude) }}">
                            @error('longitude')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                            rows="3" required>{{ old('description', $user->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </form>

    </div>
    </div>
@endsection
@section('scripts')
    <script>
        // Géolocalisation
        document.getElementById('address').addEventListener('blur', function() {
            // Utiliser l'API de géocodage pour obtenir les coordonnées
            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.value)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        document.getElementById('latitude').value = data[0].lat;
                        document.getElementById('longitude').value = data[0].lon;
                    }
                });
        });
    </script>
@endsection
