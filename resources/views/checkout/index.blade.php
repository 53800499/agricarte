@extends('layouts.app')

@section('title', 'Finaliser la commande - AgriCarte')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <h1 class="text-3xl font-bold text-gray-800 mb-8">Finaliser votre commande</h1>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Récapitulatif du panier -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Votre commande</h2>
                <div class="space-y-4">
                    @foreach($products as $item)
                        <div class="flex items-center justify-between border-b pb-4">
                            <div class="flex items-center space-x-4">
                                <img src="{{ asset('storage/' . $item['product']->image) }}" 
                                     alt="{{ $item['product']->name }}"
                                     class="w-16 h-16 object-cover rounded">
                                <div>
                                    <h3 class="font-medium text-gray-800">{{ $item['product']->name }}</h3>
                                    <p class="text-sm text-gray-600">{{ $item['quantity'] }} x {{ number_format($item['product']->price, 2) }} FCFA</p>
                                </div>
                            </div>
                            <p class="font-medium">{{ number_format($item['subtotal'], 2) }} FCFA</p>
                        </div>
                    @endforeach
                </div>
                <div class="mt-6 pt-4 border-t">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold">Total</span>
                        <span class="text-xl font-bold text-green-600">{{ number_format($total, 2) }} FCFA</span>
                    </div>
                </div>
            </div>

            <!-- Formulaire de commande -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations de livraison</h2>
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label for="shipping_address" class="block text-sm font-medium text-gray-700">Adresse de livraison</label>
                            <textarea name="shipping_address" id="shipping_address" rows="3" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">{{ old('shipping_address') }}</textarea>
                            @error('shipping_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="billing_address" class="block text-sm font-medium text-gray-700">Adresse de facturation</label>
                            <textarea name="billing_address" id="billing_address" rows="3" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">{{ old('billing_address') }}</textarea>
                            @error('billing_address')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Méthode de paiement</label>
                            <select name="payment_method" id="payment_method" required
                                    class="mt-1 block w-full rounded-lg border-gray-300 shadow-sm focus:border-green-500 focus:ring focus:ring-green-200">
                                <option value="">Sélectionnez une méthode</option>
                                <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>Paiement à la livraison</option>
                                <option value="card" {{ old('payment_method') == 'card' ? 'selected' : '' }}>Carte bancaire</option>
                                <option value="transfer" {{ old('payment_method') == 'transfer' ? 'selected' : '' }}>Virement bancaire</option>
                            </select>
                            @error('payment_method')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg hover:bg-green-700 transition duration-200">
                            Confirmer la commande
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection 