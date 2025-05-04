@extends('layouts.admin')

@section('title', 'Détails de la commande #' . str_pad($order->id, 6, '0', STR_PAD_LEFT))

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Détails de la commande #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h1>
            <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>

        <div class="row g-4">
            <div class="col-12">
                <div class="card shadow mb-4">
                    <div class="card-header py-3 d-flex justify-content-between align-items-center">
                        <h6 class="m-0 font-weight-bold text-primary">Informations de la commande</h6>
                        <div>
                            <form action="{{ route('orders.update', $order) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PUT')
                                <select name="status" class="form-select" onchange="this.form.submit()">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>En attente</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>En cours de traitement</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Terminée</option>
                                    <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Annulée</option>
                                </select>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <h6 class="font-weight-bold">Informations client</h6>
                                <p class="mb-1">{{ $order->user->name }}</p>
                                <p class="mb-1">{{ $order->user->email }}</p>
                                @if($order->user->phone)
                                    <p class="mb-1">{{ $order->user->phone }}</p>
                                @endif
                            </div>

                            <div class="col-md-4">
                                <h6 class="font-weight-bold">Adresse de livraison</h6>
                                <p class="mb-1">{{ $order->shipping_address }}</p>
                            </div>

                            <div class="col-md-4">
                                <h6 class="font-weight-bold">Adresse de facturation</h6>
                                <p class="mb-1">{{ $order->billing_address }}</p>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-md-4">
                                <h6 class="font-weight-bold">Méthode de paiement</h6>
                                <p class="mb-1">{{ ucfirst($order->payment_method) }}</p>
                                <p class="mb-1">
                                    Statut :
                                    <span class="badge
                                        @switch($order->payment_status)
                                            @case('completed')
                                                bg-success
                                                @break
                                            @case('pending')
                                                bg-warning
                                                @break
                                            @case('failed')
                                                bg-danger
                                                @break
                                            @default
                                                bg-secondary
                                        @endswitch
                                    ">
                                        {{ ucfirst($order->payment_status) }}
                                    </span>
                                </p>
                            </div>

                            <div class="col-md-4">
                                <h6 class="font-weight-bold">Dates</h6>
                                <p class="mb-1">Créée le : {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                <p class="mb-1">Dernière mise à jour : {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                            </div>

                            <div class="col-md-4">
                                <h6 class="font-weight-bold">Actions</h6>
                                <form action="{{ route('orders.destroy', $order) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">
                                        <i class="fas fa-trash"></i> Supprimer la commande
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Produits commandés</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Prix unitaire</th>
                                        <th>Quantité</th>
                                        <th>Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($order->products as $product)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if($product->image)
                                                        <img src="{{ asset('storage/' . $product->image) }}"
                                                            alt="{{ $product->name }}"
                                                            class="img-thumbnail mr-3"
                                                            style="width: 50px; height: 50px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <div class="font-weight-bold">{{ $product->name }}</div>
                                                        <small class="text-muted">{{ $product->pivot->quantity }} x {{ number_format($product->pivot->price, 2, ',', ' ') }} €</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ number_format($product->pivot->price, 2, ',', ' ') }} €</td>
                                            <td>{{ $product->pivot->quantity }}</td>
                                            <td>{{ number_format($product->pivot->price * $product->pivot->quantity, 2, ',', ' ') }} €</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="3" class="text-right font-weight-bold">Total TTC</td>
                                        <td class="font-weight-bold">{{ number_format($order->total, 2, ',', ' ') }} €</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
