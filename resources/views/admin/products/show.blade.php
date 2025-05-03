@extends('layouts.admin')

@section('title', 'Détails du produit')

@section('content')
    <div class="container-fluid">
        <div class="card shadow">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <div class="text-center mb-4">
                            @if($product->image)
                                <img src="{{ asset('storage/products/' . $product->image) }}"
                                     alt="{{ $product->name }}"
                                     class="img-fluid rounded"
                                     style="max-height: 300px;">
                            @else
                                <img src="{{ asset('images/logo.jpg') }}"
                                     alt="Image par défaut"
                                     class="img-fluid rounded"
                                     style="max-height: 300px;">
                            @endif
                        </div>
                    </div>
                    <div class="col-md-8">
                        <h2 class="mb-3">{{ $product->name }}</h2>
                        <p class="text-muted mb-4">{{ $product->description }}</p>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <h5>Informations principales</h5>
                                <ul class="list-unstyled">
                                    <li><strong>Prix :</strong> {{ number_format($product->price, 2) }}€</li>
                                    <li><strong>Quantité en stock :</strong> {{ $product->stock_quantity }} {{ $product->unit }}</li>
                                    <li><strong>Catégorie :</strong> {{ $product->category->name }}</li>
                                    <li><strong>Agriculteur :</strong> {{ $product->user->name }}</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h5>Statut</h5>
                                <ul class="list-unstyled">
                                    <li>
                                        <strong>Disponibilité :</strong>
                                        @if($product->is_available)
                                            <span class="badge bg-success">Disponible</span>
                                        @else
                                            <span class="badge bg-danger">Non disponible</span>
                                        @endif
                                    </li>
                                    <li>
                                        <strong>Bio :</strong>
                                        @if($product->is_organic)
                                            <span class="badge bg-success">Oui</span>
                                        @else
                                            <span class="badge bg-secondary">Non</span>
                                        @endif
                                    </li>
                                    <li>
                                        <strong>Mis en avant :</strong>
                                        @if($product->is_featured)
                                            <span class="badge bg-primary">Oui</span>
                                        @else
                                            <span class="badge bg-secondary">Non</span>
                                        @endif
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit me-2"></i>Modifier
                            </a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                    <i class="fas fa-trash me-2"></i>Supprimer
                                </button>
                            </form>
                            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Retour
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
