@extends('layouts.admin')

@section('title', 'Modifier le Produit')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Modifier le Produit</h1>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('products.update', $product) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <!-- Informations de base -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Informations du produit</h5>

                            <div class="mb-3">
                                <label for="name" class="form-label">Nom du produit</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror"
                                          id="description" name="description" rows="3" required>{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="category_id" class="form-label">Catégorie</label>
                                <select class="form-select @error('category_id') is-invalid @enderror"
                                        id="category_id" name="category_id" required>
                                    <option value="">Sélectionnez une catégorie</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}"
                                                {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            @if(auth()->user()->role === 'admin')
                            <div class="mb-3">
                                <label for="farmer_id" class="form-label">Producteur</label>
                                <select class="form-select @error('farmer_id') is-invalid @enderror"
                                        id="farmer_id" name="farmer_id" required>
                                    <option value="">Sélectionnez un producteur</option>
                                    @foreach($farmers as $farmer)
                                        <option value="{{ $farmer->id }}"
                                                {{ old('farmer_id', $product->farmer_id) == $farmer->id ? 'selected' : '' }}>
                                            {{ $farmer->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('farmer_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            @endif
                        </div>

                        <!-- Prix et stock -->
                        <div class="col-md-6">
                            <h5 class="mb-3">Prix et stock</h5>

                            <div class="mb-3">
                                <label for="price" class="form-label">Prix unitaire (€)</label>
                                <input type="number" step="0.01" min="0"
                                       class="form-control @error('price') is-invalid @enderror"
                                       id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="stock" class="form-label">Stock disponible</label>
                                <input type="number" min="0"
                                       class="form-control @error('stock') is-invalid @enderror"
                                       id="stock" name="stock" value="{{ old('stock', $product->stock) }}" required>
                                @error('stock')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="unit" class="form-label">Unité de mesure</label>
                                <select class="form-select @error('unit') is-invalid @enderror"
                                        id="unit" name="unit" required>
                                    <option value="">Sélectionnez une unité</option>
                                    <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Kilogramme (kg)</option>
                                    <option value="g" {{ old('unit', $product->unit) == 'g' ? 'selected' : '' }}>Gramme (g)</option>
                                    <option value="l" {{ old('unit', $product->unit) == 'l' ? 'selected' : '' }}>Litre (l)</option>
                                    <option value="cl" {{ old('unit', $product->unit) == 'cl' ? 'selected' : '' }}>Centilitre (cl)</option>
                                    <option value="pièce" {{ old('unit', $product->unit) == 'pièce' ? 'selected' : '' }}>Pièce</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                           {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Produit actif</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="mt-4">
                        <h5 class="mb-3">Images du produit</h5>

                        <!-- Images actuelles -->
                        @if($product->images->count() > 0)
                            <div class="mb-3">
                                <label class="form-label">Images actuelles</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($product->images as $image)
                                        <div class="position-relative">
                                            <img src="{{ asset('storage/' . $image->path) }}"
                                                 alt="Image du produit"
                                                 class="img-thumbnail"
                                                 style="width: 100px; height: 100px; object-fit: cover;">
                                            <button type="button"
                                                    class="btn btn-danger btn-sm position-absolute top-0 end-0"
                                                    onclick="deleteImage({{ $image->id }})">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Nouvelle image -->
                        <div class="mb-3">
                            <label for="images" class="form-label">Ajouter des images</label>
                            <input type="file" class="form-control @error('images') is-invalid @enderror"
                                   id="images" name="images[]" accept="image/*" multiple>
                            <small class="text-muted">Formats acceptés : JPG, PNG, GIF. Taille maximale : 2MB par image.</small>
                            @error('images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function deleteImage(imageId) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
                fetch(`/admin/products/images/${imageId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert('Une erreur est survenue lors de la suppression de l\'image.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de la suppression de l\'image.');
                });
            }
        }
    </script>
    @endpush
@endsection
