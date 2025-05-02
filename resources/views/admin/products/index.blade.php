@extends('layouts.admin')

@section('title', 'Gestion des produits')

@section('content')
    <div class="container-fluid">
        @foreach (['success', 'info', 'warning', 'danger'] as $msg)
            @if(session($msg))
                <div class="alert alert-{{ $msg }} alert-dismissible fade show" role="alert">
                    {{ session($msg) }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        @endforeach

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Gestion des produits</h1>
            <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#productFormModal">
                <i class="fas fa-plus me-2"></i>Ajouter un produit
            </button>
        </div>

        <!-- Filtres -->
        <div class="card shadow-sm mb-4">
            <div class="card-body">
                <form action="{{ route('products.index') }}" method="GET" class="row g-3">
                    <div class="col-md-3">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" name="search" placeholder="Rechercher..."
                                value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="status">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" name="sort">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récents</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus anciens</option>
                            <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Nom</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Agriculteur</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (!$products->isEmpty())
                                @foreach ($products as $product)
                                    <tr>
                                        <td>
                                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/logo.jpg') }}"
                                                alt="Photo de {{ $product->name }}" class="rounded-circle" width="40"
                                                height="40">
                                            {{-- @if ($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 50px;">
                                @else 
                                    <img src="{{ asset('images/products/tomates.jpg') }}" alt="No image" class="img-thumbnail" style="max-width: 50px;">
                                @endif --}}
                                        </td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ Str::limit($product->description, 50) }}</td>
                                        <td>{{ number_format($product->price, 2) }}€</td>
                                        <td>{{ $product->user->name }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-primary me-2"
                                                onclick="editProduct({{ $product->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $product->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">Aucun produit trouvé</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Formulaire Produit -->
    <div class="modal fade" id="productFormModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="productFormTitle">Ajouter un produit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="productForm" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_method" id="productMethod" value="POST">
                    <input type="hidden" name="product_id" id="productId">
                    <input type="hidden" name="user_id" value="{{ auth()->id() }}">

                    <div class="modal-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="name" class="form-label">Nom</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description"
                                rows="3" required>{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="price" class="form-label">Prix</label>
                            <div class="input-group">
                                <input type="number" step="0.01"
                                    class="form-control @error('price') is-invalid @enderror" id="price"
                                    name="price" value="{{ old('price') }}" required>
                                <span class="input-group-text">€</span>
                                @error('price')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="stock_quantity" class="form-label">Quantité en stock</label>
                            <input type="number" step="0.01"
                                class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity"
                                name="stock_quantity" value="{{ old('stock_quantity') }}">
                            @error('stock_quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="unit" class="form-label">Unité</label>
                            <input type="text" class="form-control @error('unit') is-invalid @enderror" id="unit"
                                name="unit" value="{{ old('unit', 'kg') }}" required>
                            @error('unit')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Catégorie</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id"
                                name="category_id" required>
                                <option value="">Choisissez une catégorie</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}"
                                        {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="image" class="form-label">Image principale</label>
                            <input type="file" class="form-control @error('image') is-invalid @enderror"
                                id="image" name="image" accept="image/*">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="additional_images" class="form-label">Images supplémentaires</label>
                            <input type="file" class="form-control @error('additional_images.*') is-invalid @enderror"
                                id="additional_images" name="additional_images[]" multiple accept="image/*">
                            @error('additional_images.*')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="is_available" name="is_available"
                                {{ old('is_available', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_available">Disponible</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="is_organic" name="is_organic"
                                {{ old('is_organic') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_organic">Bio</label>
                        </div>

                        <div class="form-check mb-2">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">À la une</label>
                        </div>
                    </div>


                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-success">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <!-- Modal de Confirmation de Suppression -->
    <div class="modal fade" id="deleteConfirmModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirmer la suppression</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    Êtes-vous sûr de vouloir supprimer ce produit ? Cette action est irréversible.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form id="deleteForm" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function editProduct(id) {
            fetch(`/api/products/${id}`)
                .then(response => response.json())
                .then(product => {
                    document.getElementById('productFormTitle').textContent = 'Modifier le produit';
                    document.getElementById('productMethod').value = 'PUT';
                    document.getElementById('productId').value = product.id;
                    document.getElementById('productForm').action = `/admin/products/${product.id}`;

                    document.getElementById('name').value = product.name;
                    document.getElementById('description').value = product.description;
                    document.getElementById('price').value = product.price;
                    document.getElementById('farmer_id').value = product.farmer_id;

                    const currentImage = document.getElementById('currentImage');
                    if (product.image) {
                        currentImage.querySelector('img').src = `/storage/${product.image}`;
                        currentImage.classList.remove('d-none');
                    } else {
                        currentImage.classList.add('d-none');
                    }

                    const modal = new bootstrap.Modal(document.getElementById('productFormModal'));
                    modal.show();
                });
        }

        function confirmDelete(id) {
            document.getElementById('deleteForm').action = `/admin/products/${id}`;
            const modal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
            modal.show();
        }

        // Réinitialiser le formulaire quand on ouvre le modal d'ajout
        document.querySelector('[data-bs-target="#productFormModal"]').addEventListener('click', function() {
            document.getElementById('productForm').reset();
            document.getElementById('productFormTitle').textContent = 'Ajouter un produit';
            document.getElementById('productMethod').value = 'POST';
            document.getElementById('productId').value = '';
            document.getElementById('productForm').action = "{{ route('products.store') }}";
            document.getElementById('currentImage').classList.add('d-none');
        });

        // Gérer la soumission du formulaire
        document.getElementById('productForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch(this.action, {
                    method: formData.get('_method') || 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Une erreur est survenue. Veuillez réessayer.');
                    }
                });
        });
    </script>
@endsection
