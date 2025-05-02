@extends('layouts.admin')

@section('title', 'Modifier un agriculteur')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h3 class="card-title">Modifier l'agriculteur</h3>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.farmers.update', $farmer) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="firstname" class="form-label">Prénom</label>
                                <input type="text" class="form-control @error('firstname') is-invalid @enderror"
                                       id="firstname" name="firstname" value="{{ old('firstname', $farmer->firstname) }}" required>
                                @error('firstname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="lastname" class="form-label">Nom</label>
                                <input type="text" class="form-control @error('lastname') is-invalid @enderror"
                                       id="lastname" name="lastname" value="{{ old('lastname', $farmer->lastname) }}" required>
                                @error('lastname')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                   id="email" name="email" value="{{ old('email', $farmer->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Téléphone</label>
                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                   id="phone" name="phone" value="{{ old('phone', $farmer->phone) }}">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
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
                                <input type="password" class="form-control"
                                       id="password_confirmation" name="password_confirmation">
                            </div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.farmers.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save"></i> Enregistrer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
