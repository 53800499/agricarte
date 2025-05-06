@extends('layouts.admin')

@section('title', isset($order) ? 'Modifier la Commande - AgriCarte' : 'Nouvelle Commande - AgriCarte')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ isset($order) ? 'Modifier la Commande #' . $order->id : 'Nouvelle Commande' }}</h1>
        <a href="{{ Auth()->user()->role=='admin' ? route('admin.orders.index') : route('farmer.orders.index') }}" class="btn btn-secondary btn-icon-split">
            <span class="icon text-white-50">
                <i class="fas fa-arrow-left"></i>
            </span>
            <span class="text">Retour</span>
        </a>
    </div>

    <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeIn">
        <div class="card-header bg-gradient-primary text-white py-3">
            <h5 class="m-0 font-weight-bold">{{ isset($order) ? 'Modifier les détails de la commande' : 'Créer une nouvelle commande' }}</h5>
        </div>
        <div class="card-body">
            <form action="{{ isset($order) ? route('orders.update', $order) : route('orders.store') }}" method="POST" class="needs-validation" novalidate>
                @csrf
                @if(isset($order))
                    @method('PUT')
                @endif

                <div class="row g-4">
                    <!-- Informations client -->
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-user me-2"></i>Informations client
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="user_id" class="form-label">Client</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-users"></i>
                                        </span>
                                        <select name="user_id" id="user_id" required class="form-select @error('user_id') is-invalid @enderror">
                                            <option value="">Sélectionner un client</option>
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}" {{ (isset($order) && $order->user_id == $user->id) ? 'selected' : '' }}>
                                                    {{ $user->name }} ({{ $user->email }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('user_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statut et paiement -->
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-cog me-2"></i>Statut et paiement
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($order))
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Statut</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-tasks"></i>
                                            </span>
                                            <select name="status" id="status" required class="form-select @error('status') is-invalid @enderror">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>En traitement</option>
                                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                            </select>
                                            {{-- @error('status')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror --}}
                                        </div>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="payment_method" class="form-label">Méthode de paiement</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-credit-card"></i>
                                        </span>
                                        <select name="payment_method" id="payment_method" required class="form-select @error('payment_method') is-invalid @enderror">
                                            <option value="cash" {{ (isset($order) && $order->payment_method == 'cash') ? 'selected' : '' }}>Espèces</option>
                                            <option value="card" {{ (isset($order) && $order->payment_method == 'card') ? 'selected' : '' }}>Carte bancaire</option>
                                            <option value="transfer" {{ (isset($order) && $order->payment_method == 'transfer') ? 'selected' : '' }}>Virement</option>
                                        </select>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Adresses -->
                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-truck me-2"></i>Adresse de livraison
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="shipping_address" class="form-label">Adresse</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <textarea name="shipping_address" id="shipping_address" rows="3" required
                                                class="form-control @error('shipping_address') is-invalid @enderror">{{ isset($order) ? $order->shipping_address : old('shipping_address') }}</textarea>
                                        @error('shipping_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card h-100 border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-file-invoice me-2"></i>Adresse de facturation
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="billing_address" class="form-label">Adresse</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-map-marker-alt"></i>
                                        </span>
                                        <textarea name="billing_address" id="billing_address" rows="3" required
                                                class="form-control @error('billing_address') is-invalid @enderror">{{ isset($order) ? $order->billing_address : old('billing_address') }}</textarea>
                                        @error('billing_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Produits -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-shopping-cart me-2"></i>Produits
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="space-y-4" id="products-container">
                                    @if(isset($order))
                                        @foreach($order->products as $index => $product)
                                            <div class="product-item card border-0 shadow-sm mb-3">
                                                <div class="card-body">
                                                    <div class="row g-3">
                                                        <div class="col-md-5">
                                                            <label class="form-label">Produit</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light">
                                                                    <i class="fas fa-box"></i>
                                                                </span>
                                                                <select name="products[{{ $index }}][id]" required class="form-select">
                                                                    @foreach($products as $p)
                                                                        <option value="{{ $p->id }}" {{ $product->id == $p->id ? 'selected' : '' }}>
                                                                            {{ $p->name }} ({{ number_format($p->price, 2) }} FCFA)
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-5">
                                                            <label class="form-label">Quantité</label>
                                                            <div class="input-group">
                                                                <span class="input-group-text bg-light">
                                                                    <i class="fas fa-sort-numeric-up"></i>
                                                                </span>
                                                                <input type="number" name="products[{{ $index }}][quantity]" min="1" required
                                                                       value="{{ $product->pivot->quantity }}"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-2 d-flex align-items-end">
                                                            <button type="button" class="btn btn-danger w-100 remove-product">
                                                                <i class="fas fa-trash-alt me-2"></i>Supprimer
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="product-item card border-0 shadow-sm mb-3">
                                            <div class="card-body">
                                                <div class="row g-3">
                                                    <div class="col-md-5">
                                                        <label class="form-label">Produit</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light">
                                                                <i class="fas fa-box"></i>
                                                            </span>
                                                            <select name="products[0][id]" required class="form-select">
                                                                <option value="">Sélectionner un produit</option>
                                                                @foreach($products as $product)
                                                                    <option value="{{ $product->id }}">
                                                                        {{ $product->name }} ({{ number_format($product->price, 2) }} FCFA)
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-5">
                                                        <label class="form-label">Quantité</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text bg-light">
                                                                <i class="fas fa-sort-numeric-up"></i>
                                                            </span>
                                                            <input type="number" name="products[0][quantity]" min="1" required
                                                                   class="form-control">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 d-flex align-items-end">
                                                        <button type="button" class="btn btn-danger w-100 remove-product">
                                                            <i class="fas fa-trash-alt me-2"></i>Supprimer
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="mt-4">
                                    <button type="button" id="add-product" class="btn btn-success">
                                        <i class="fas fa-plus me-2"></i>Ajouter un produit
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notes -->
                    <div class="col-12">
                        <div class="card border-0 shadow-sm">
                            <div class="card-header bg-light">
                                <h6 class="m-0 font-weight-bold text-primary">
                                    <i class="fas fa-sticky-note me-2"></i>Notes
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="notes" class="form-label">Notes</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-pencil-alt"></i>
                                        </span>
                                        <textarea name="notes" id="notes" rows="3"
                                                class="form-control @error('notes') is-invalid @enderror">{{ isset($order) ? $order->notes : old('notes') }}</textarea>
                                        @error('notes')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-primary btn-icon-split">
                        <span class="icon text-white-50">
                            <i class="fas fa-save"></i>
                        </span>
                        <span class="text">{{ isset($order) ? 'Mettre à jour' : 'Créer' }} la commande</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsContainer = document.getElementById('products-container');
        const addProductButton = document.getElementById('add-product');
        let productCount = productsContainer.children.length;

        // Ajouter un produit
        addProductButton.addEventListener('click', function() {
            const template = `
                <div class="product-item card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-5">
                                <label class="form-label">Produit</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-box"></i>
                                    </span>
                                    <select name="products[${productCount}][id]" required class="form-select">
                                        <option value="">Sélectionner un produit</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }} ({{ number_format($product->price, 2) }} FCFA)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <label class="form-label">Quantité</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-sort-numeric-up"></i>
                                    </span>
                                    <input type="number" name="products[${productCount}][quantity]" min="1" required
                                           class="form-control">
                                </div>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="button" class="btn btn-danger w-100 remove-product">
                                    <i class="fas fa-trash-alt me-2"></i>Supprimer
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            productsContainer.insertAdjacentHTML('beforeend', template);
            productCount++;
        });

        // Supprimer un produit
        productsContainer.addEventListener('click', function(e) {
            if (e.target.closest('.remove-product')) {
                if (productsContainer.children.length > 1) {
                    const productItem = e.target.closest('.product-item');
                    productItem.style.opacity = '0';
                    setTimeout(() => {
                        productItem.remove();
                    }, 300);
                }
            }
        });
    });
</script>
@endpush
@endsection
