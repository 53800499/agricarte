@extends('layouts.app')

@section('title', 'Finaliser la commande - AgriCarte')
@push('styles')
    <style>
        .producer-hero {
        background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/slide1.jpg') }}');
        background-size: cover;
        background-position: center;
        min-height: 300px;
        display: flex;
        align-items: center;
        color: white;
        margin-top: -76px;
    }
    </style>
@endpush
@section('content')
<!-- Hero Section -->
    <section class="producer-hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8 mx-auto text-center">
                    <h1 class="display-4 fw-bold mb-4">🛒 Finaliser votre commande</h1>
                    <p class="lead">Vérifiez vos informations avant de confirmer</p>
                </div>
            </div>
        </div>
    </section>
<div class="container py-5">

    <div class="row g-5">
        <!-- Colonne panier -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white fw-semibold fs-5">
                    🧾 Récapitulatif de la commande
                </div>
                <div class="card-body">
                    @foreach($products as $item)
                        <div class="d-flex justify-content-between align-items-center border-bottom pb-3 mb-3">
                            <div class="d-flex align-items-center gap-3">
                                <img src="{{ asset('storage/products/' . $item['product']->image) }}" alt="{{ $item['product']->name }}"
                                     class="rounded" style="width: 60px; height: 60px; object-fit: cover;">
                                <div>
                                    <h6 class="mb-0">{{ $item['product']->name }}</h6>
                                    <small class="text-muted">{{ $item['quantity'] }} × {{ number_format($item['product']->price, 0, ',', ' ') }} FCFA</small>
                                </div>
                            </div>
                            <div class="text-end fw-bold text-success">
                                {{ number_format($item['subtotal'], 0, ',', ' ') }} FCFA
                            </div>
                        </div>
                    @endforeach

                    <div class="d-flex justify-content-between pt-3 border-top">
                        <span class="fw-semibold fs-5">Total :</span>
                        <span class="fw-bold fs-4 text-success">{{ number_format($total, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne formulaire -->
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white fw-semibold fs-5">
                    🚚 Informations de livraison & paiement
                </div>
                <div class="card-body">
                    <form action="{{ route('checkout.store') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Adresse de livraison</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" class="form-control @error('shipping_address') is-invalid @enderror" required>{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Adresse de facturation</label>
                            <textarea name="billing_address" id="billing_address" rows="3" class="form-control @error('billing_address') is-invalid @enderror" required>{{ old('billing_address') }}</textarea>
                            @error('billing_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="payment_method" class="form-label">Méthode de paiement</label>
                            <select name="payment_method" id="payment_method" class="form-select @error('payment_method') is-invalid @enderror" required>
                                <option value="">-- Sélectionnez --</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>💵 Paiement à la livraison</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>💳 Carte bancaire</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>🏦 Virement bancaire</option>
                            </select>
                            @error('payment_method')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-success w-100 py-3 fw-bold">
                            ✅ Confirmer la commande
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
