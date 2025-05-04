@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="container py-5">
    <div class="row">
        <div class="col-md-6">
            <div class="product-image mb-4">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}"
                         alt="{{ $product->name }}"
                         class="img-fluid rounded shadow">
                @else
                    <img src="{{ asset('images/default-product.jpg') }}"
                         alt="{{ $product->name }}"
                         class="img-fluid rounded shadow">
                @endif
            </div>
        </div>
        <div class="col-md-6">
            <div class="product-details">
                <h1 class="mb-3">{{ $product->name }}</h1>
                <div class="mb-4">
                    <span class="badge bg-success">{{ $product->category->name }}</span>
                    @if($product->is_organic)
                        <span class="badge bg-info">Bio</span>
                    @endif
                </div>
                <p class="text-muted mb-4">{{ $product->description }}</p>

                <div class="price-section mb-4">
                    <h3 class="text-success">{{ number_format($product->price, 2) }}€</h3>
                    <small class="text-muted">/ {{ $product->unit }}</small>
                </div>

                <div class="producer-info mb-4">
                    <h5>Producteur</h5>
                    <div class="d-flex align-items-center">
                        <img src="{{ $product->user->profile_image ? asset('storage/' . $product->user->profile_image) : asset('images/default-avatar.png') }}"
                             alt="{{ $product->user->name }}"
                             class="rounded-circle me-2"
                             style="width: 40px; height: 40px; object-fit: cover;">
                        <div>
                            <h6 class="mb-0">{{ $product->user->name }}</h6>
                            <small class="text-muted">{{ $product->user->address }}</small>
                        </div>
                    </div>
                </div>

                <div class="stock-info mb-4">
                    <h5>Disponibilité</h5>
                    @if($product->stock_quantity > 0)
                        <p class="text-success">
                            <i class="fas fa-check-circle me-2"></i>
                            En stock ({{ $product->stock_quantity }} {{ $product->unit }})
                        </p>
                    @else
                        <p class="text-danger">
                            <i class="fas fa-times-circle me-2"></i>
                            Rupture de stock
                        </p>
                    @endif
                </div>

                <div class="actions">
                    <form action="{{ route('cart.add', $product) }}" method="POST" class="d-inline">
                        @csrf
                        <div class="input-group mb-3" style="max-width: 200px;">
                            <input type="number"
                                   name="quantity"
                                   class="form-control"
                                   value="1"
                                   min="1"
                                   max="{{ $product->stock_quantity }}"
                                   {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                            <button type="submit"
                                    class="btn btn-success"
                                    {{ $product->stock_quantity <= 0 ? 'disabled' : '' }}>
                                <i class="fas fa-shopping-cart me-2"></i>Ajouter au panier
                            </button>
                        </div>
                    </form>

                    <button class="btn btn-outline-danger toggle-favorite"
                            data-product-id="{{ $product->id }}">
                        <i class="fas fa-heart me-2"></i>
                        <span>Ajouter aux favoris</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gestion des favoris
    const favoriteButton = document.querySelector('.toggle-favorite');
    if (favoriteButton) {
        favoriteButton.addEventListener('click', function() {
            const productId = this.dataset.productId;
            fetch(`/favorites/${productId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const heartIcon = this.querySelector('i');
                const textSpan = this.querySelector('span');

                if (data.status === 'added') {
                    heartIcon.classList.add('text-danger');
                    textSpan.textContent = 'Retirer des favoris';
                    this.classList.add('btn-danger');
                    this.classList.remove('btn-outline-danger');
                } else {
                    heartIcon.classList.remove('text-danger');
                    textSpan.textContent = 'Ajouter aux favoris';
                    this.classList.remove('btn-danger');
                    this.classList.add('btn-outline-danger');
                }
            });
        });
    }
});
</script>
@endpush
