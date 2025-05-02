@extends('layouts.app')

@section('title', 'Contact - AgriCarte')

@section('content')
    <!-- Hero Section -->
    <section class="hero-section bg-light">
        <div class="container text-center hero-content">
            <h1 class="display-4 fw-bold mb-4">Contactez-nous</h1>
        </div>
    </section>
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="container py-5">
        <p class="text-center mb-4">
            Nous somme a votre écoute pour toute question ou demande d'information'
        </p>
        <div class="row gap-2">

            <div class="col-md-3 card mt-8 shadow-sm mr-2 justify-content-between">
                <div class="card-body p-4">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row align-items-center mb-2">
                                <div class="col-md-1 bg-transparent"><i class="fas fa-map-marker-alt text-success me-2"></i>
                                </div>
                                <div class="col-md-10">
                                    <h4>Adresse</h4>
                                    <div>123 Avenue de la République<br>13001
                                        Marseille, France</div>
                                </div>
                            </div>
                            <div class="row align-items-center mb-2">
                                <div class="col-md-1"><i class="fas fa-envelope text-success me-2"></i></div>
                                <div class="col-md-10">
                                    <h4>Envoyez-nous un mail</h4>
                                    <div>contact@agricarte.fr</div>
                                </div>
                            </div>
                            <div class="row align-items-center">
                                <div class="col-md-1"><i class="fas fa-phone text-success me-2"></i></div>
                                <div class="col-md-10">
                                    <h4>Appellez-nous</h4>
                                    <div>+33 4 91 XX XX XX</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h4 class="mt-4 mb-3">Heures d'ouverture</h4>
                    <p>Lundi - Vendredi: 9h00 - 18h00</p>
                    <p>Samedi: 9h00 - 12h00</p>
                    <p>Dimanche: Fermé</p>
                </div>
            </div>
            <div class="col-md-8 card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="name" name="name" value="{{ old('name') }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    id="email" name="email" value="{{ old('email') }}" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Sujet</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" id="subject"
                                name="subject" value="{{ old('subject') }}" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="message" class="form-label">Message</label>
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message" name="message" rows="5"
                                required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg">Envoyer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
