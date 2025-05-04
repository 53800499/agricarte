@extends('layouts.admin')

@section('title', 'Détails du Produit')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Détails du Produit</h1>
            <div class="d-flex gap-2">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                    <i class="fas fa-edit"></i> Modifier
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </div>

        <div class="row">
            <!-- Informations principales -->
            <div class="col-md-8">
                <div class="card shadow mb-4">
                    <div class="card-body">
                        <div class="row">
                            <!-- Image -->
                            <div class="col-md-4">
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}"
                                         class="img-fluid rounded"
                                         alt="{{ $product->name }}"
                                         style="max-height: 300px; width: 100%; object-fit: cover;">
                                @else
                                    <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                         style="height: 300px;">
                                        <i class="fas fa-image fa-3x text-white"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Informations -->
                            <div class="col-md-8">
                                <h2 class="mb-3">{{ $product->name }}</h2>
                                <div class="mb-3">
                                    <span class="badge {{ $product->is_available ? 'bg-success' : 'bg-danger' }}">
                                        {{ $product->is_available ? 'Actif' : 'Inactif' }}
                                    </span>
                                </div>
                                <div class="mb-3">
                                    <h4 class="text-primary">{{ number_format($product->price, 2) }} €</h4>
                                </div>
                                <div class="mb-3">
                                    <p class="text-muted">{{ $product->description }}</p>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p><strong>Catégorie:</strong> {{ $product->category->name }}</p>
                                        <p><strong>Stock disponible:</strong> {{ $product->stock_quantity }} {{ $product->unit }}</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p><strong>Unité:</strong> {{ $product->unit }}</p>
                                        @if(auth()->user()->role === 'admin')
                                            <p><strong>Producteur:</strong> {{ $product->farmer->name }}</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Statistiques -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Statistiques</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-primary">{{ $product->orders->count() }}</h4>
                                    <p class="text-muted">Commandes</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-success">{{ $product->stock_quantity }}</h4>
                                    <p class="text-muted">En stock</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <h4 class="text-info">{{ $product->views ?? 0 }}</h4>
                                    <p class="text-muted">Vues</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Informations complémentaires -->
            <div class="col-md-4">
                <!-- Dernières commandes -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Dernières commandes</h6>
                    </div>
                    <div class="card-body">
                        @if($product->orders->count() > 0)
                            <div class="list-group">
                                @foreach($product->orders->take(5) as $order)
                                    <div class="list-group-item">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Commande #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h6>
                                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-muted mb-0">Aucune commande pour ce produit</p>
                        @endif
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Actions rapides</h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Modifier le produit
                            </a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger w-100"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                    <i class="fas fa-trash"></i> Supprimer le produit
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
