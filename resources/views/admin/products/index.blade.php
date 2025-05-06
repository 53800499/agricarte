@extends('layouts.admin')

@section('title', 'Gestion des Produits')

@section('content')
    <div class="container-fluid px-4">
        @foreach (['success', 'info', 'warning', 'danger'] as $msg)
            @if (session($msg))
                <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                    {{ session($msg) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endforeach

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Gestion des Produits</h1>
            <a href="{{ Auth()->user()->role=='admin' ? route('admin.products.create') : route('farmer.products.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un produit
            </a>
        </div>

        <!-- Filtres -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ Auth()->user()->role=='admin' ? route('admin.products.index') : route('farmer.products.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Nom, description...">
                    </div>

                    <div class="col-md-3">
                        <label for="category" class="form-label">Catégorie</label>
                        <select class="form-select" id="category" name="category">
                            <option value="">Toutes les catégories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                        {{ request('category') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if(auth()->user()->role === 'admin')
                    <div class="col-md-3">
                        <label for="farmer" class="form-label">Producteur</label>
                        <select class="form-select" id="farmer" name="farmer">
                            <option value="">Tous les producteurs</option>
                            @foreach($farmers as $farmer)
                                <option value="{{ $farmer->id }}"
                                        {{ request('farmer') == $farmer->id ? 'selected' : '' }}>
                                    {{ $farmer->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <div class="col-md-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                        <a href="{{ Auth()->user()->role=='admin' ? route('admin.products.index') : route('farmer.products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Réinitialiser
                        </a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des produits -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Catégorie</th>
                                @if(auth()->user()->role === 'admin')
                                    <th>Producteur</th>
                                @endif
                                <th>Prix</th>
                                <th>Stock</th>
                                <th>Vente</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products as $product)
                                <tr>
                                    <td>
                                        @if($product->image)
                                            <img src="{{ asset('storage/products/' . $product->image) }}"
                                                 alt="{{ $product->name }}"
                                                 class="img-thumbnail"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-image text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $product->name }}</td>
                                    <td>{{ $product->category->name }}</td>
                                    @if(auth()->user()->role === 'admin')
                                        <td>{{ $product->user->name }}</td>
                                    @endif
                                    <td>{{ number_format($product->price, 2, ',', ' ') }} FCFA</td>
                                    <td>{{ $product->stock_quantity }} {{ $product->unit }}</td>
                                    <td>
                                        <span class="badge {{ $product->is_featured ? 'bg-success' : 'bg-danger' }}">
                                            {{ $product->is_featured ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ Auth()->user()->role=='admin' ? route('admin.products.show', $product) : route('farmer.products.show', $product) }}"
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ Auth()->user()->role=='admin' ? route('admin.products.edit', $product) : route('farmer.products.edit', $product) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ Auth()->user()->role=='admin' ? route('admin.products.destroy', $product) : route('farmer.products.destroy', $product) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ auth()->user()->role === 'admin' ? '8' : '7' }}" class="text-center">
                                        Aucun produit trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection


