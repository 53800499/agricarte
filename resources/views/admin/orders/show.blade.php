@extends('layouts.app')

@section('title', 'Détails de la Commande - AgriCarte')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">Détails de la Commande #{{ $order->id }}</h1>
        <div class="flex space-x-4">
            <a href="{{ route('orders.edit', $order) }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                Modifier
            </a>
            <a href="{{ route('orders.index') }}" class="bg-gray-200 text-gray-800 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
                Retour
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Informations de la commande -->
        <div class="md:col-span-2">
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations de la commande</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Date de commande</p>
                        <p class="font-medium">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Statut</p>
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                            @if($order->status == 'pending') bg-yellow-100 text-yellow-800
                            @elseif($order->status == 'processing') bg-blue-100 text-blue-800
                            @elseif($order->status == 'shipped') bg-indigo-100 text-indigo-800
                            @elseif($order->status == 'delivered') bg-green-100 text-green-800
                            @else bg-red-100 text-red-800 @endif">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Méthode de paiement</p>
                        <p class="font-medium">{{ ucfirst($order->payment_method) }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Montant total</p>
                        <p class="font-medium text-lg">{{ number_format($order->total_amount, 2) }} FCFA</p>
                    </div>
                </div>
            </div>

            <!-- Produits commandés -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Produits commandés</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Prix unitaire</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($order->products as $product)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $product->category->name }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($product->pivot->price, 2) }} FCFA
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ $product->pivot->quantity }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        {{ number_format($product->pivot->price * $product->pivot->quantity, 2) }} FCFA
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Informations du client -->
        <div>
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Informations du client</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Nom</p>
                        <p class="font-medium">{{ $order->user->name }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-medium">{{ $order->user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Téléphone</p>
                        <p class="font-medium">{{ $order->user->phone ?? 'Non renseigné' }}</p>
                    </div>
                </div>
            </div>

            <!-- Adresses -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Adresses</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Adresse de livraison</p>
                        <p class="font-medium">{{ $order->shipping_address }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Adresse de facturation</p>
                        <p class="font-medium">{{ $order->billing_address }}</p>
                    </div>
                </div>
            </div>

            <!-- Notes -->
            @if($order->notes)
                <div class="bg-white rounded-lg shadow-md p-6 mt-8">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Notes</h2>
                    <p class="text-gray-700">{{ $order->notes }}</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
