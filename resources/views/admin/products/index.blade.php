@extends('layouts.admin')

@section('title', 'Gestion des produits')

@section('content')
    <div class="container-fluid">
        @foreach (['success', 'info', 'warning', 'danger'] as $msg)
            @if (session($msg))
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
                                            {{-- <button class="btn btn-sm btn-primary me-2"
                                                onclick="editProduct({{ $product->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-sm btn-danger"
                                                onclick="confirmDelete({{ $product->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button> --}}
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-outline-info"
                                                    data-bs-toggle="modal" data-bs-target="#viewProductModal"
                                                    data-farmer="{{ $product->id }}">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-bs-toggle="modal" data-bs-target="#editProductModal"
                                                    data-farmer="{{ $product->id }}">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger"
                                                    onclick="confirmDelete({{ $product->id }})">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
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

                        <div class="row">
                            <div class="mb-3 col-md-4">
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

                            <div class="mb-3 col-md-4">
                                <label for="stock_quantity" class="form-label">Quantité en stock</label>
                                <input type="number" step="0.01"
                                    class="form-control @error('stock_quantity') is-invalid @enderror" id="stock_quantity"
                                    name="stock_quantity" value="{{ old('stock_quantity') }}" required>
                                @error('stock_quantity')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="unit" class="form-label">Unité</label>
                                <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                    id="unit" name="unit" value="{{ old('unit', 'kg') }}" required>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="category_id" class="form-label">Catégorie</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                                <option value="">Sélectionnez une catégorie</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
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
                                id="image" name="image" accept="image/*" required>
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="additional_images" class="form-label">Images supplémentaires</label>
                            <input type="file" class="form-control @error('additional_images') is-invalid @enderror"
                                id="additional_images" name="additional_images[]" accept="image/*" multiple>
                            @error('additional_images')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_available" name="is_available"
                                    value="1" {{ old('is_available', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_available">Disponible</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_organic" name="is_organic"
                                    value="1" {{ old('is_organic') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_organic">Bio</label>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="is_featured" name="is_featured"
                                    value="1" {{ old('is_featured') ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_featured">Mettre en avant</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Édition Produit -->
    <div class="modal fade" id="editProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Modifier le produit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="editProductForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="edit_description" class="form-label">Description</label>
                            <textarea class="form-control" id="edit_description" name="description" rows="3" required></textarea>
                        </div>

                        <div class="row">
                            <div class="mb-3 col-md-4">
                                <label for="edit_price" class="form-label">Prix</label>
                                <div class="input-group">
                                    <input type="number" step="0.01" class="form-control" id="edit_price" name="price" required>
                                    <span class="input-group-text">€</span>
                                </div>
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="edit_stock_quantity" class="form-label">Quantité en stock</label>
                                <input type="number" step="0.01" class="form-control" id="edit_stock_quantity" name="stock_quantity">
                            </div>

                            <div class="mb-3 col-md-4">
                                <label for="edit_unit" class="form-label">Unité</label>
                                <input type="text" class="form-control" id="edit_unit" name="unit" value="kg" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="edit_category_id" class="form-label">Catégorie</label>
                            <select class="form-select" id="edit_category_id" name="category_id" required>
                                <option value="">Choisissez une catégorie</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="edit_image" class="form-label">Image principale</label>
                            <input type="file" class="form-control" id="edit_image" name="image" accept="image/*">
                            <small class="text-muted">Laissez vide pour conserver l'image actuelle</small>
                        </div>

                        <div class="mb-3">
                            <label for="edit_additional_images" class="form-label">Images supplémentaires</label>
                            <input type="file" class="form-control" id="edit_additional_images" name="additional_images[]" multiple accept="image/*">
                        </div>

                        <div class="row">
                            <div class="form-check mb-2 col-md-4">
                                <input class="form-check-input" type="checkbox" id="edit_is_available" name="is_available" checked>
                                <label class="form-check-label" for="edit_is_available">Disponible</label>
                            </div>

                            <div class="form-check mb-2 col-md-4">
                                <input class="form-check-input" type="checkbox" id="edit_is_organic" name="is_organic">
                                <label class="form-check-label" for="edit_is_organic">Bio</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Détails Agriculteur -->
    <div class="modal fade" id="viewProductModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Détails du produit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Chargement...</span>
                        </div>
                    </div>
                </div>
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
                    <p>Êtes-vous sûr de vouloir supprimer cet produit ?</p>
                    <p class="text-danger"><small>Cette action est irréversible.</small></p>
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

        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du modal de modification
            const editProductModal = document.getElementById('editProductModal');
            if (editProductModal) {
                editProductModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const productId = button.getAttribute('data-farmer');
                    console.log('Product ID:', productId); // Debug log

                    // Mettre à jour l'action du formulaire
                    const form = document.getElementById('editProductForm');
                    form.action = `/admin/products/${productId}`;
                    console.log('Form action:', form.action); // Debug log

                    // Afficher l'indicateur de chargement
                    const modalBody = this.querySelector('.modal-body');
                    const originalContent = modalBody.innerHTML;
                    modalBody.innerHTML = `
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Chargement...</span>
                            </div>
                            <p class="mt-2">Chargement des données du produit...</p>
                        </div>
                    `;

                    // Récupérer les données du produit
                    fetch(`/admin/products/${productId}`)
                        .then(response => {
                            console.log('Response status:', response.status); // Debug log
                            if (!response.ok) {
                                throw new Error('Erreur lors de la récupération des données');
                            }
                            return response.json();
                        })
                        .then(data => {
                            console.log('Product data:', data); // Debug log

                            // Mettre à jour les champs du formulaire
                            const fields = {
                                'edit_name': data.name,
                                'edit_description': data.description,
                                'edit_price': data.price,
                                'edit_stock_quantity': data.stock_quantity,
                                'edit_unit': data.unit,
                                'edit_category_id': data.category_id,
                                'edit_is_available': data.is_available,
                                'edit_is_organic': data.is_organic
                            };

                            // Remplir les champs
                            Object.entries(fields).forEach(([id, value]) => {
                                const element = document.getElementById(id);
                                if (element) {
                                    if (element.type === 'checkbox') {
                                        element.checked = value;
                                    } else {
                                        element.value = value;
                                    }
                                    console.log(`Field ${id} set to:`, value); // Debug log
                                } else {
                                    console.warn(`Element not found: ${id}`); // Debug log
                                }
                            });

                            // Restaurer le contenu original du modal
                            modalBody.innerHTML = originalContent;
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            modalBody.innerHTML = `
                                <div class="alert alert-danger">
                                    <h5 class="alert-heading">Erreur</h5>
                                    <p>Une erreur est survenue lors du chargement des données du produit.</p>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                                </div>
                            `;
                        });
                });
            }

            // Gestion du formulaire de modification
            const editForm = document.getElementById('editProductForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    console.log('Form data:', Object.fromEntries(formData)); // Debug log

                    // Afficher un indicateur de chargement
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Envoi en cours...';

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        processData: false,
                        contentType: false
                    })
                    .then(response => {
                        console.log('Response status:', response.status); // Debug log
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success response:', data); // Debug log
                        if (data.success) {
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        let errorMessage = 'Une erreur est survenue lors de la modification du produit';
                        if (error.errors) {
                            errorMessage = Object.values(error.errors).flat().join('\n');
                        } else if (error.message) {
                            errorMessage = error.message;
                        }
                        alert(errorMessage);
                    })
                    .finally(() => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                    });
                });
            }

            // Gestion du formulaire d'ajout
            const form = document.getElementById('productForm');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(form);

                    // Vérifier si une image a été sélectionnée
                    const imageInput = document.getElementById('image');
                    if (!imageInput.files[0]) {
                        alert('Veuillez sélectionner une image principale');
                        return;
                    }

                    // Vérifier si une catégorie a été sélectionnée
                    const categorySelect = document.getElementById('category_id');
                    if (!categorySelect.value) {
                        alert('Veuillez sélectionner une catégorie');
                        return;
                    }

                    // Afficher un indicateur de chargement
                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Envoi en cours...';

                    fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        processData: false,
                        contentType: false
                    })
                    .then(response => {
                        if (!response.ok) {
                            return response.json().then(err => Promise.reject(err));
                        }
                        return response.json();
                    })
                    .then(data => {
                        if (data.success) {
                            window.location.reload();
                        } else {
                            throw new Error(data.message || 'Une erreur est survenue');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        let errorMessage = 'Une erreur est survenue lors de la création du produit';
                        if (error.errors) {
                            errorMessage = Object.values(error.errors).flat().join('\n');
                        } else if (error.message) {
                            errorMessage = error.message;
                        }
                        alert(errorMessage);
                    })
                    .finally(() => {
                        submitButton.disabled = false;
                        submitButton.innerHTML = originalButtonText;
                    });
                });
            }
        });
    </script>
@endsection


