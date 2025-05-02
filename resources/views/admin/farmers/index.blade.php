@extends('layouts.admin')

@section('title', 'Gestion des Agriculteurs')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Agriculteurs</h1>
        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addFarmerModal">
            <i class="fas fa-plus me-2"></i>Ajouter un agriculteur
        </button>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('farmers.index') }}" method="GET" class="row g-3">
                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-text"><i class="fas fa-search"></i></span>
                        <input type="text" class="form-control" name="search" placeholder="Rechercher..." value="{{ request('search') }}">
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

    <!-- Liste des agriculteurs -->
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Photo</th>
                            <th>Nom</th>
                            <th>Email</th>
                            <th>Téléphone</th>
                            <th>Adresse</th>
                            <th>Statut</th>
                            <th>Produits</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($farmers as $farmer)
                            <tr>
                                <td>
                                    <img src="{{ $farmer->profile_image ? asset('storage/' . $farmer->profile_image) : asset('images/logo.jpg') }}" 
                                         alt="Photo de {{ $farmer->name }}" 
                                         class="rounded-circle"
                                         width="40" height="40">
                                </td>
                                <td>{{ $farmer->name }}</td>
                                <td>{{ $farmer->email }}</td>
                                <td>{{ $farmer->phone }}</td>
                                <td>{{ Str::limit($farmer->address, 30) }}</td>
                                <td>
                                    <span class="badge bg-{{ $farmer->is_active ? 'success' : 'danger' }}">
                                        {{ $farmer->is_active ? 'Actif' : 'Inactif' }}
                                    </span>
                                </td>
                                <td>{{ $farmer->products_count }} produits</td>
                                <td>
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#editFarmerModal" 
                                                data-farmer="{{ $farmer->id }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <a href="{{ route('farmers.show', $farmer->id) }}" 
                                           class="btn btn-sm btn-outline-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button type="button" class="btn btn-sm btn-outline-danger" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#deleteFarmerModal" 
                                                data-farmer="{{ $farmer->id }}">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="d-flex flex-column align-items-center">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5 class="text-muted">Aucun agriculteur trouvé</h5>
                                        <p class="text-muted">Commencez par ajouter un nouvel agriculteur</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-between align-items-center mt-4">
                <div>
                    Affichage de {{ $farmers->firstItem() ?? 0 }} à {{ $farmers->lastItem() ?? 0 }} sur {{ $farmers->total() ?? 0 }} agriculteurs
                </div>
                {{ $farmers->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Ajout Agriculteur -->
<div class="modal fade" id="addFarmerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Ajouter un agriculteur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('farmers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom complet</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adresse</label>
                            <input type="text" class="form-control" name="address" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control" name="latitude" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control" name="longitude" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Photo de profil</label>
                            <input type="file" class="form-control" name="profile_image" accept="image/*">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                                <label class="form-check-label">Compte actif</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success">Ajouter</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Édition Agriculteur -->
<div class="modal fade" id="editFarmerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'agriculteur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="editFarmerForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nom complet</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" name="phone" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" class="form-control" name="password">
                            <small class="text-muted">Laissez vide pour conserver l'actuel</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adresse</label>
                            <input type="text" class="form-control" name="address" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control" name="latitude" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control" name="longitude" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Photo de profil</label>
                            <input type="file" class="form-control" name="profile_image" accept="image/*">
                            <small class="text-muted">Laissez vide pour conserver l'image actuelle</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" value="1">
                                <label class="form-check-label">Compte actif</label>
                            </div>
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

<!-- Modal Suppression Agriculteur -->
<div class="modal fade" id="deleteFarmerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmer la suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cet agriculteur ?</p>
                <p class="text-danger"><small>Cette action est irréversible.</small></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                <form id="deleteFarmerForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Gérer l'édition d'un agriculteur
    const editFarmerModal = document.getElementById('editFarmerModal');
    if (editFarmerModal) {
        editFarmerModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const farmerId = button.getAttribute('data-farmer');
            const form = this.querySelector('#editFarmerForm');
            
            // Mettre à jour l'action du formulaire
            form.action = `/admin/farmers/${farmerId}`;
            
            // Charger les données de l'agriculteur
            fetch(`/admin/farmers/${farmerId}`)
                .then(response => response.json())
                .then(data => {
                    form.querySelector('[name="name"]').value = data.name;
                    form.querySelector('[name="email"]').value = data.email;
                    form.querySelector('[name="phone"]').value = data.phone;
                    form.querySelector('[name="address"]').value = data.address;
                    form.querySelector('[name="latitude"]').value = data.latitude;
                    form.querySelector('[name="longitude"]').value = data.longitude;
                    form.querySelector('[name="description"]').value = data.description;
                    form.querySelector('[name="is_active"]').checked = data.is_active;
                });
        });
    }

    // Gérer la suppression d'un agriculteur
    const deleteFarmerModal = document.getElementById('deleteFarmerModal');
    if (deleteFarmerModal) {
        deleteFarmerModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const farmerId = button.getAttribute('data-farmer');
            const form = this.querySelector('#deleteFarmerForm');
            form.action = `/admin/farmers/${farmerId}`;
        });
    }

    // Géolocalisation automatique
    const addressInput = document.querySelector('[name="address"]');
    if (addressInput) {
        addressInput.addEventListener('blur', function() {
            if (this.value) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.value)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.length > 0) {
                            const form = this.closest('form');
                            form.querySelector('[name="latitude"]').value = data[0].lat;
                            form.querySelector('[name="longitude"]').value = data[0].lon;
                        }
                    });
            }
        });
    }
});
</script>
@endpush
