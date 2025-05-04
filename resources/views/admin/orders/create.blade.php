@extends('layouts.admin')

@section('title', 'Créer une commande')

@section('content')
    <div class="container-fluid px-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Créer une commande</h1>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Tableau de bord</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Commandes</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Créer</li>
                    </ol>
                </nav>
            </div>
            <div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Retour
                </a>
            </div>
        </div>

        <form action="{{ route('admin.orders.store') }}" method="POST">
            @csrf
            <div class="row">
                <div class="col-lg-4">
                    <!-- Informations client -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-user me-2"></i>Informations client
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="user_id" class="form-label">Client</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                    <select class="form-select @error('user_id') is-invalid @enderror"
                                            id="user_id" name="user_id" required>
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('user_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Statut de la commande -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-truck me-2"></i>Statut de la commande
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <label for="status" class="form-label">Statut</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="fas fa-info-circle"></i></span>
                                    <select class="form-select @error('status') is-invalid @enderror"
                                            id="status" name="status" required>
                                        <option value="pending">En attente</option>
                                        <option value="processing">En cours de traitement</option>
                                        <option value="shipped">Expédiée</option>
                                        <option value="delivered">Livrée</option>
                                        <option value="cancelled">Annulée</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <!-- Produits de la commande -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-shopping-cart me-2"></i>Produits de la commande
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="productsTable">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Prix unitaire</th>
                                            <th>Total</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <select class="form-select product-select" name="products[0][id]" required>
                                                    <option value="">Sélectionnez un produit</option>
                                                    @foreach($products as $product)
                                                        <option value="{{ $product->id }}"
                                                                data-price="{{ $product->price }}">
                                                            {{ $product->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control quantity"
                                                       name="products[0][quantity]" min="1" value="1" required>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control price" readonly>
                                            </td>
                                            <td>
                                                <input type="number" class="form-control total" readonly>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-danger remove-row">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <td colspan="5">
                                                <button type="button" class="btn btn-success" id="addProduct">
                                                    <i class="fas fa-plus me-2"></i>Ajouter un produit
                                                </button>
                                            </td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Informations de livraison -->
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">
                                <i class="fas fa-truck me-2"></i>Informations de livraison
                            </h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_address" class="form-label">Adresse de livraison</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-home"></i></span>
                                        <input type="text" class="form-control @error('shipping_address') is-invalid @enderror"
                                               id="shipping_address" name="shipping_address" required>
                                        @error('shipping_address')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_city" class="form-label">Ville</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                        <input type="text" class="form-control @error('shipping_city') is-invalid @enderror"
                                               id="shipping_city" name="shipping_city" required>
                                        @error('shipping_city')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_postal_code" class="form-label">Code postal</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-mail-bulk"></i></span>
                                        <input type="text" class="form-control @error('shipping_postal_code') is-invalid @enderror"
                                               id="shipping_postal_code" name="shipping_postal_code" required>
                                        @error('shipping_postal_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="shipping_country" class="form-label">Pays</label>
                                    <div class="input-group">
                                        <span class="input-group-text"><i class="fas fa-globe"></i></span>
                                        <input type="text" class="form-control @error('shipping_country') is-invalid @enderror"
                                               id="shipping_country" name="shipping_country" required>
                                        @error('shipping_country')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Créer la commande
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const productsTable = document.getElementById('productsTable');
                const addProductBtn = document.getElementById('addProduct');
                let rowCount = 1;

                // Fonction pour calculer le total d'une ligne
                function calculateRowTotal(row) {
                    const quantity = parseFloat(row.querySelector('.quantity').value) || 0;
                    const price = parseFloat(row.querySelector('.price').value) || 0;
                    const total = quantity * price;
                    row.querySelector('.total').value = total.toFixed(2);
                }

                // Fonction pour mettre à jour le prix lors de la sélection d'un produit
                function updatePrice(select) {
                    const row = select.closest('tr');
                    const priceInput = row.querySelector('.price');
                    const selectedOption = select.options[select.selectedIndex];
                    const price = selectedOption.getAttribute('data-price') || 0;
                    priceInput.value = price;
                    calculateRowTotal(row);
                }

                // Ajouter un nouveau produit
                addProductBtn.addEventListener('click', function() {
                    const newRow = productsTable.querySelector('tbody tr').cloneNode(true);
                    const newSelect = newRow.querySelector('.product-select');
                    const newQuantity = newRow.querySelector('.quantity');
                    const newPrice = newRow.querySelector('.price');
                    const newTotal = newRow.querySelector('.total');

                    // Mettre à jour les noms des champs
                    newSelect.name = `products[${rowCount}][id]`;
                    newQuantity.name = `products[${rowCount}][quantity]`;
                    newPrice.name = `products[${rowCount}][price]`;
                    newTotal.name = `products[${rowCount}][total]`;

                    // Réinitialiser les valeurs
                    newSelect.selectedIndex = 0;
                    newQuantity.value = 1;
                    newPrice.value = '';
                    newTotal.value = '';

                    productsTable.querySelector('tbody').appendChild(newRow);
                    rowCount++;
                });

                // Supprimer une ligne
                productsTable.addEventListener('click', function(e) {
                    if (e.target.closest('.remove-row')) {
                        const row = e.target.closest('tr');
                        if (productsTable.querySelector('tbody tr').length > 1) {
                            row.remove();
                        }
                    }
                });

                // Mettre à jour les prix et totaux
                productsTable.addEventListener('change', function(e) {
                    if (e.target.classList.contains('product-select')) {
                        updatePrice(e.target);
                    } else if (e.target.classList.contains('quantity')) {
                        calculateRowTotal(e.target.closest('tr'));
                    }
                });
            });
        </script>
    @endpush
@endsection
