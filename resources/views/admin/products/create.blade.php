@extends('layouts.admin')

@section('title', 'Créer un produit')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Créer un produit</h1>
                {{-- <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Produits</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Créer</li>
                    </ol>
                </nav> --}}
            </div>
            <div>
                <a href="{{ Auth()->user()->role=='admin' ? route('admin.products.index') : route('farmer.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        <form action="{{ Auth()->user()->role=='admin' ? route('admin.products.store') : route('farmer.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_id" value="{{ Auth()->user()->id }}">
            <div class="row">
                @foreach (['success', 'info', 'warning', 'danger'] as $msg)
                    @if (session($msg))
                        <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                            {{ session($msg) }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                @endforeach
                <div class="col-lg-4">
                    <!-- Image principale -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-image me-2"></i>Image principale
                            </h6>
                        </div>
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <div class="position-relative d-inline-block">
                                    <div class="rounded bg-light d-flex align-items-center justify-content-center mx-auto"
                                        style="width: 200px; height: 200px;">
                                        <i class="fas fa-image fa-3x text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                            <div class="mb-3">
                                <input type="file" class="form-control @error('image') is-invalid @enderror"
                                       id="image" name="image" accept="image/*" required>
                                @error('image')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Statut du produit -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-toggle-on me-2"></i>Statut du produit
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Disponibilité</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_available" name="is_available" checked>
                                    <label class="form-check-label" for="is_available">Produit disponible</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Vente</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" checked>
                                    <label class="form-check-label" for="is_featured">Mettre en vente</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bio</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_organic" name="is_organic" checked>
                                    <label class="form-check-label" for="is_organic">Le produit est bio</label>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="category_id" class="form-label">Catégorie</label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Informations du produit -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-info-circle me-2"></i>Informations du produit
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nom du produit</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                                               id="name" name="name" value="{{ old('name') }}" required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label">Prix</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-euro-sign"></i></span>
                                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                               id="price" name="price" value="{{ old('price') }}" required>
                                        @error('price')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="stock_quantity" class="form-label">Quantité en stock</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-box"></i></span>
                                        <input type="number" class="form-control @error('stock_quantity') is-invalid @enderror"
                                               id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity') }}" required>
                                        @error('stock_quantity')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="unit" class="form-label">Unité</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-balance-scale"></i></span>
                                        <select class="form-select @error('unit') is-invalid @enderror"
                                                id="unit" name="unit" required>
                                            <option value="kg">Kilogramme (kg)</option>
                                            <option value="g">Gramme (g)</option>
                                            <option value="l">Litre (l)</option>
                                            <option value="ml">Millilitre (ml)</option>
                                            <option value="piece">Pièce</option>
                                        </select>
                                        @error('unit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 mb-3">
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror"
                                              id="description" name="description" rows="4">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-12 mb-3">
                                    @if(auth()->user()->role === 'admin')
                                        <label for="user_id" class="form-label">Producteur</label>
                                        <select class="form-select @error('user_id') is-invalid @enderror"
                                                id="user_id" name="user_id" required>
                                            <option value="">Sélectionnez un producteur</option>
                                            @foreach($farmers as $farmer)
                                                <option value="{{ $farmer->id }}"
                                                        {{ old('user_id') == $farmer->id ? 'selected' : '' }}>
                                                    {{ $farmer->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Images supplémentaires -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-images me-2"></i>Images supplémentaires
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <input type="file" class="form-control @error('additional_images') is-invalid @enderror"
                                       id="additional_images" name="additional_images[]" accept="image/*" multiple>
                                @error('additional_images')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Créer le produit
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
