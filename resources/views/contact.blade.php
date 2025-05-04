@extends('layouts.app')

@section('title', 'Contact - AgriCarte')

@push('styles')
<style>
    .contact-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.7), rgba(0, 0, 0, 0.7)), url('/images/slide1.jpg');
        background-size: cover;
        background-position: center;
        min-height: 300px;
        display: flex;
        align-items: center;
        color: white;
        margin-top: -76px;
    }

    .contact-card {
        border: none;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        transition: transform 0.3s ease;
    }

    .contact-card:hover {
        transform: translateY(-5px);
    }

    .contact-icon {
        width: 50px;
        height: 50px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(40, 167, 69, 0.1);
        border-radius: 50%;
        margin-right: 1rem;
    }

    .contact-info {
        display: flex;
        align-items: center;
        margin-bottom: 1.5rem;
    }

    .contact-info i {
        font-size: 1.5rem;
        color: var(--primary-color);
    }

    .contact-form {
        background: white;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        padding: 2rem;
    }

    .form-control {
        border-radius: 10px;
        padding: 0.8rem 1rem;
        border: 1px solid #e0e0e0;
    }

    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
    }

    .btn-submit {
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 0.8rem 2rem;
        font-weight: 600;
        transition: all 0.3s ease;
    }

    .btn-submit:hover {
        background: #2d8a3e;
        transform: translateY(-2px);
    }

    .opening-hours {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 1.5rem;
        margin-top: 1.5rem;
    }

    .opening-hours h4 {
        color: var(--primary-color);
        margin-bottom: 1rem;
    }

    .opening-hours p {
        margin-bottom: 0.5rem;
        color: #6c757d;
    }

    @media (max-width: 768px) {
        .contact-hero {
            min-height: 200px;
        }

        .contact-info {
            flex-direction: column;
            text-align: center;
        }

        .contact-icon {
            margin-right: 0;
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')
    <!-- Hero Section -->
    <section class="contact-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4 animate__animated animate__fadeInDown">Contactez-nous</h1>
                    <p class="lead animate__animated animate__fadeInUp">Nous sommes à votre écoute pour toute question ou demande d'information</p>
                </div>
            </div>
        </div>
    </section>

    @if (session('success'))
        <div class="container mt-4">
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        </div>
    @endif

    <div class="container py-5">
        <div class="row g-4">
            <!-- Contact Information -->
            <div class="col-lg-4">
                <div class="contact-card p-4">
                    <div class="contact-info animate__animated animate__fadeInLeft">
                        <div class="contact-icon">
                            <i class="fas fa-map-marker-alt"></i>
                        </div>
                        <div>
                            <h4>Adresse</h4>
                            <p class="mb-0">123 Avenue de la République<br>13001 Marseille, France</p>
                        </div>
                    </div>

                    <div class="contact-info animate__animated animate__fadeInLeft" style="animation-delay: 0.2s">
                        <div class="contact-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h4>Email</h4>
                            <p class="mb-0">contact@agricarte.fr</p>
                        </div>
                    </div>

                    <div class="contact-info animate__animated animate__fadeInLeft" style="animation-delay: 0.4s">
                        <div class="contact-icon">
                            <i class="fas fa-phone"></i>
                        </div>
                        <div>
                            <h4>Téléphone</h4>
                            <p class="mb-0">+33 4 91 XX XX XX</p>
                        </div>
                    </div>

                    <div class="opening-hours animate__animated animate__fadeInLeft" style="animation-delay: 0.6s">
                        <h4>Heures d'ouverture</h4>
                        <p><i class="far fa-clock me-2"></i>Lundi - Vendredi: 9h00 - 18h00</p>
                        <p><i class="far fa-clock me-2"></i>Samedi: 9h00 - 12h00</p>
                        <p><i class="far fa-clock me-2"></i>Dimanche: Fermé</p>
                    </div>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="contact-form animate__animated animate__fadeInRight">
                    <form action="{{ route('contact.submit') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nom complet</label>
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
                            <textarea class="form-control @error('message') is-invalid @enderror" id="message"
                                name="message" rows="5" required>{{ old('message') }}</textarea>
                            @error('message')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="text-end">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-paper-plane me-2"></i>Envoyer le message
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
