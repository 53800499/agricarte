@extends('layouts.admin')

@section('title', 'Modifier le Produit')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Modifier le Produit</h1>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary btn-icon-split">
                <span class="icon text-white-50">
                    <i class="fas fa-arrow-left"></i>
                </span>
                <span class="text">Retour</span>
            </a>
        </div>

        <div class="card shadow-lg border-0 rounded-lg animate__animated animate__fadeIn">
            <div class="card-header bg-gradient-primary text-white py-3">
                <h5 class="m-0 font-weight-bold">Modifier les détails du produit</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="row g-4">
                        <!-- Informations de base -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-info-circle me-2"></i>Informations du produit
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Nom du produit</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-box"></i>
                                            </span>
                                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                                   id="name" name="name" value="{{ old('name', $product->name) }}" required>
                                            @error('name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Description</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-align-left"></i>
                                            </span>
                                            <textarea class="form-control @error('description') is-invalid @enderror"
                                                      id="description" name="description" rows="3" required>{{ old('description', $product->description) }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="category_id" class="form-label">Catégorie</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-tags"></i>
                                            </span>
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
                                    </div>

                                    @if(auth()->user()->role === 'admin')
                                    <div class="mb-3">
                                        <label for="user_id" class="form-label">Producteur</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-user-tie"></i>
                                            </span>
                                            <select class="form-select @error('user_id') is-invalid @enderror"
                                                    id="user_id" name="user_id" required>
                                                <option value="">Sélectionnez un producteur</option>
                                                @foreach($farmers as $farmer)
                                                    <option value="{{ $farmer->id }}"
                                                            {{ old('user_id', $product->user_id) == $farmer->id ? 'selected' : '' }}>
                                                        {{ $farmer->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            {{-- @error('user_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror --}}
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Prix et stock -->
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="card-header bg-light">
                                    <h6 class="m-0 font-weight-bold text-primary">
                                        <i class="fas fa-chart-line me-2"></i>Prix et stock
                                    </h6>
                                </div>
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label for="price" class="form-label">Prix unitaire (€)</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-euro-sign"></i>
                                            </span>
                                            <input type="number" step="0.01" min="0"
                                                   class="form-control @error('price') is-invalid @enderror"
                                                   id="price" name="price" value="{{ old('price', $product->price) }}" required>
                                            @error('price')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="stock_quantity" class="form-label">Stock disponible</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-boxes"></i>
                                            </span>
                                            <input type="number" min="0"
                                                   class="form-control @error('stock_quantity') is-invalid @enderror"
                                                   id="stock_quantity" name="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required>
                                            @error('stock_quantity')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="unit" class="form-label">Unité de mesure</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-light">
                                                <i class="fas fa-balance-scale"></i>
                                            </span>
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
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_available" name="is_available"
                                                   {{ old('is_available', $product->is_available) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_available">
                                                @if($product->is_available)
                                                    <i class="fas fa-check-circle me-2"></i>
                                                @endif 
                                                @if(!$product->is_available)
                                                    <i class="fas fa-circle me-2"></i>
                                                @endif
                                                Produit disponible
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                                   {{ old('is_featured', $product->is_featured) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_featured">
                                                @if($product->is_featured)
                                                    <i class="fas fa-check-circle me-2"></i>
                                                @endif 
                                                @if(!$product->is_featured)
                                                    <i class="fas fa-circle me-2"></i>
                                                @endif
                                                Produit en vente
                                            </label>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="is_organic" name="is_organic"
                                                   {{ old('is_organic', $product->is_organic) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="is_organic">
                                                @if($product->is_organic)
                                                    <i class="fas fa-check-circle me-2"></i>
                                                @endif 
                                                @if(!$product->is_organic)
                                                    <i class="fas fa-circle me-2"></i>
                                                @endif 
                                                Produit bio
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="card mt-4 border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-images me-2"></i>Images du produit
                            </h6>
                        </div>
                        <div class="card-body">
                            <!-- Image principale actuelle -->
                            <div class="mb-4">
                                <label class="form-label">Image principale actuelle</label>
                                <div class="d-flex align-items-center">
                                    @if($product->image)
                                        <div class="position-relative me-3">
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                 alt="Image principale actuelle"
                                                 class="img-thumbnail rounded-lg shadow-sm"
                                                 style="width: 150px; height: 150px; object-fit: cover;">
                                            <button type="button" class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle"
                                                    onclick="deleteMainImage()" style="width: 30px; height: 30px; padding: 0;">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    @else
                                        <div class="text-muted">
                                            <i class="fas fa-image me-2"></i>Aucune image principale
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Nouvelle image principale -->
                            <div class="mb-4">
                                <label for="image" class="form-label">Nouvelle image principale</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-camera"></i>
                                    </span>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                           id="image" name="image" accept="image/*">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Formats acceptés : JPG, PNG, GIF. Taille maximale : 10MB.
                                </small>
                            </div>

                            <!-- Images supplémentaires actuelles -->
                            @php
                                $productImages = json_decode($product->additional_images ?? '[]', true);
                            @endphp
                            @if(count($productImages) > 0)
                                <div class="mb-4">
                                    <label class="form-label">Images supplémentaires actuelles</label>
                                    <div class="d-flex flex-wrap gap-3">
                                        @foreach($productImages as $image)
                                            <div class="position-relative">
                                                <img src="{{ asset('storage/products/' . $image) }}"
                                                     alt="Image supplémentaire"
                                                     class="img-thumbnail rounded-lg shadow-sm"
                                                     style="width: 100px; height: 100px; object-fit: cover;">
                                                <button type="button"
                                                        class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 rounded-circle"
                                                        onclick="deleteImage('{{ $image }}')" style="width: 30px; height: 30px; padding: 0;">
                                                    <i class="fas fa-times"></i>
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Nouvelles images supplémentaires -->
                            <div class="mb-3">
                                <label for="additional_images" class="form-label">Ajouter des images supplémentaires</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light">
                                        <i class="fas fa-images"></i>
                                    </span>
                                    <input type="file" class="form-control @error('additional_images') is-invalid @enderror"
                                           id="additional_images" name="additional_images[]" accept="image/*" multiple>
                                    @error('additional_images')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>Formats acceptés : JPG, PNG, GIF. Taille maximale : 10MB par image.
                                </small>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 text-end">
                        <button type="submit" class="btn btn-primary btn-lg">
                            <i class="fas fa-save me-2"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('styles')
    <style>
        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
        }
        .form-control:focus, .form-select:focus {
            border-color: #4e73df;
            box-shadow: 0 0 0 0.2rem rgba(78, 115, 223, 0.25);
        }
        .btn-primary {
            background: linear-gradient(45deg, #4e73df, #224abe);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            background: linear-gradient(45deg, #224abe, #4e73df);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(78, 115, 223, 0.4);
        }
        .btn-danger {
            background: linear-gradient(45deg, #e74a3b, #be2617);
            border: none;
            transition: all 0.3s ease;
        }
        .btn-danger:hover {
            background: linear-gradient(45deg, #be2617, #e74a3b);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(231, 74, 59, 0.4);
        }
        .img-thumbnail {
            transition: transform 0.3s ease;
        }
        .img-thumbnail:hover {
            transform: scale(1.05);
        }
        .input-group-text {
            transition: background-color 0.3s ease;
        }
        .input-group:focus-within .input-group-text {
            background-color: #e3e6f0;
        }
    </style>
    @endpush

    @push('scripts')
    <script>
        function deleteMainImage() {
            if (confirm('Êtes-vous sûr de vouloir supprimer l\'image principale ?')) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'remove_main_image';
                input.value = '1';
                document.querySelector('form').appendChild(input);

                const imageContainer = document.querySelector('.position-relative');
                imageContainer.classList.add('animate__animated', 'animate__fadeOut');

                setTimeout(() => {
                    imageContainer.remove();
                    const message = document.createElement('div');
                    message.className = 'text-muted animate__animated animate__fadeIn';
                    message.innerHTML = '<i class="fas fa-info-circle me-2"></i>Image principale supprimée';
                    document.querySelector('.d-flex').appendChild(message);
                }, 500);
            }
        }

        function deleteImage(imageName) {
            if (confirm('Êtes-vous sûr de vouloir supprimer cette image ?')) {
                const imageContainer = event.target.closest('.position-relative');
                imageContainer.classList.add('animate__animated', 'animate__fadeOut');

                fetch(`/admin/products/images/${imageName}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        setTimeout(() => {
                            imageContainer.remove();
                        }, 500);
                    } else {
                        alert('Une erreur est survenue lors de la suppression de l\'image.');
                        imageContainer.classList.remove('animate__fadeOut');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Une erreur est survenue lors de la suppression de l\'image.');
                    imageContainer.classList.remove('animate__fadeOut');
                });
            }
        }

        // Validation des formulaires
        (function () {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
    @endpush
@endsection
