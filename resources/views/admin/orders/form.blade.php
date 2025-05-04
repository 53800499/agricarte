@extends('layouts.app')

@section('title', isset($order) ? 'Modifier la Commande - AgriCarte' : 'Nouvelle Commande - AgriCarte')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">
            {{ isset($order) ? 'Modifier la Commande #' . $order->id : 'Nouvelle Commande' }}
        </h1>
        <a href="{{ route('orders.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
            Retour
        </a>
    </div>

    <form action="{{ isset($order) ? route('orders.update', $order) : route('orders.store') }}" method="POST" class="space-y-8">
        @csrf
        @if(isset($order))
            @method('PUT')
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Informations client -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations client</h2>
                <div class="space-y-4">
                    <div>
                        <label for="user_id" class="block text-sm font-medium text-gray-700">Client</label>
                        <select name="user_id" id="user_id" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                            <option value="">Sélectionner un client</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ (isset($order) && $order->user_id == $user->id) ? 'selected' : '' }}>
                                    {{ $user->name }} ({{ $user->email }})
                                </option>
                            @endforeach
                        </select>
                        @error('user_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Statut et paiement -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Statut et paiement</h2>
                <div class="space-y-4">
                    @if(isset($order))
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Statut</label>
                            <select name="status" id="status" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>En attente</option>
                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>En traitement</option>
                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Livrée</option>
                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                            </select>
                            {{-- @error('status')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror --}}
                        </div>
                    @endif

                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700">Méthode de paiement</label>
                        <select name="payment_method" id="payment_method" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                            <option value="cash" {{ (isset($order) && $order->payment_method == 'cash') ? 'selected' : '' }}>Espèces</option>
                            <option value="card" {{ (isset($order) && $order->payment_method == 'card') ? 'selected' : '' }}>Carte bancaire</option>
                            <option value="transfer" {{ (isset($order) && $order->payment_method == 'transfer') ? 'selected' : '' }}>Virement</option>
                        </select>
                        @error('payment_method')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Adresses -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Adresse de livraison</h2>
                <div class="space-y-4">
                    <div>
                        <label for="shipping_address" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <textarea name="shipping_address" id="shipping_address" rows="3" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">{{ isset($order) ? $order->shipping_address : old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Adresse de facturation</h2>
                <div class="space-y-4">
                    <div>
                        <label for="billing_address" class="block text-sm font-medium text-gray-700">Adresse</label>
                        <textarea name="billing_address" id="billing_address" rows="3" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">{{ isset($order) ? $order->billing_address : old('billing_address') }}</textarea>
                        @error('billing_address')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Produits -->
            <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Produits</h2>
                <div class="space-y-4" id="products-container">
                    @if(isset($order))
                        @foreach($order->products as $index => $product)
                            <div class="product-item grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border rounded-lg">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Produit</label>
                                    <select name="products[{{ $index }}][id]" required
                                            class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}" {{ $product->id == $p->id ? 'selected' : '' }}>
                                                {{ $p->name }} ({{ number_format($p->price, 2) }} FCFA)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Quantité</label>
                                    <input type="number" name="products[{{ $index }}][quantity]" min="1" required
                                           value="{{ $product->pivot->quantity }}"
                                           class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                                </div>
                                <div class="flex items-end">
                                    <button type="button" class="remove-product bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="product-item grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border rounded-lg">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Produit</label>
                                <select name="products[0][id]" required
                                        class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                                    <option value="">Sélectionner un produit</option>
                                    @foreach($products as $product)
                                        <option value="{{ $product->id }}">
                                            {{ $product->name }} ({{ number_format($product->price, 2) }} FCFA)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Quantité</label>
                                <input type="number" name="products[0][quantity]" min="1" required
                                       class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                            </div>
                            <div class="flex items-end">
                                <button type="button" class="remove-product bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                                    Supprimer
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
                <div class="mt-4">
                    <button type="button" id="add-product" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
                        Ajouter un produit
                    </button>
                </div>
            </div>

            <!-- Notes -->
            <div class="md:col-span-2 bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Notes</h2>
                <div class="space-y-4">
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                        <textarea name="notes" id="notes" rows="3"
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">{{ isset($order) ? $order->notes : old('notes') }}</textarea>
                        @error('notes')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition">
                {{ isset($order) ? 'Mettre à jour' : 'Créer' }} la commande
            </button>
        </div>
    </form>
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
                <div class="product-item grid grid-cols-1 md:grid-cols-3 gap-4 p-4 border rounded-lg">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Produit</label>
                        <select name="products[${productCount}][id]" required
                                class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                            <option value="">Sélectionner un produit</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }} ({{ number_format($product->price, 2) }} FCFA)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Quantité</label>
                        <input type="number" name="products[${productCount}][quantity]" min="1" required
                               class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                    </div>
                    <div class="flex items-end">
                        <button type="button" class="remove-product bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition">
                            Supprimer
                        </button>
                    </div>
                </div>
            `;
            productsContainer.insertAdjacentHTML('beforeend', template);
            productCount++;
        });

        // Supprimer un produit
        productsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-product')) {
                if (productsContainer.children.length > 1) {
                    e.target.closest('.product-item').remove();
                }
            }
        });
    });
</script>
@endpush
@endsection
