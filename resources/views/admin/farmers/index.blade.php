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
                                        <button type="button" class="btn btn-sm btn-outline-info"
                                                data-bs-toggle="modal"
                                                data-bs-target="#viewFarmerModal"
                                                data-farmer="{{ $farmer->id }}">
                                            <i class="fas fa-eye"></i>
                                        </button>
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

<!-- Modal Détails Agriculteur -->
<div class="modal fade" id="viewFarmerModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Détails de l'agriculteur</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-4">
                    <img id="viewProfileImage" src="" alt="Photo de profil" class="rounded-circle" width="120" height="120">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Nom complet</label>
                        <p id="viewName" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Email</label>
                        <p id="viewEmail" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Téléphone</label>
                        <p id="viewPhone" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Statut</label>
                        <p id="viewStatus" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Adresse</label>
                        <p id="viewAddress" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Latitude</label>
                        <p id="viewLatitude" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Longitude</label>
                        <p id="viewLongitude" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Description</label>
                        <p id="viewDescription" class="form-control-plaintext"></p>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-bold">Nombre de produits</label>
                        <p id="viewProductsCount" class="form-control-plaintext"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Modification Agriculteur -->
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
                            <input type="text" class="form-control" name="name" id="editName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" id="editEmail" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" class="form-control" name="phone" id="editPhone" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="password" placeholder="Laisser vide pour ne pas changer">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Adresse</label>
                            <input type="text" class="form-control" name="address" id="editAddress" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Latitude</label>
                            <input type="number" step="any" class="form-control" name="latitude" id="editLatitude" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Longitude</label>
                            <input type="number" step="any" class="form-control" name="longitude" id="editLongitude" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Photo de profil</label>
                            <input type="file" class="form-control" name="profile_image" accept="image/*">
                            <small class="text-muted">Laisser vide pour conserver l'image actuelle</small>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="editDescription" rows="3"></textarea>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="is_active" id="editIsActive" value="1">
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
    // Gestion du modal de visualisation
    const viewFarmerModal = document.getElementById('viewFarmerModal');
    if (viewFarmerModal) {
        viewFarmerModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const farmerId = button.getAttribute('data-farmer');

            // Afficher l'indicateur de chargement
            const modalBody = this.querySelector('.modal-body');
            modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Chargement...</span></div></div>';

            // Récupérer les données du farmer
            fetch(`/admin/farmers/${farmerId}`)
                .then(response => response.json())
                .then(data => {
                    // Mettre à jour les champs du modal
                    document.getElementById('viewProfileImage').src = data.profile_image ? `/storage/${data.profile_image}` : '/images/logo.jpg';
                    document.getElementById('viewName').textContent = data.name;
                    document.getElementById('viewEmail').textContent = data.email;
                    document.getElementById('viewPhone').textContent = data.phone;
                    document.getElementById('viewStatus').textContent = data.is_active ? 'Actif' : 'Inactif';
                    document.getElementById('viewAddress').textContent = data.address;
                    document.getElementById('viewLatitude').textContent = data.latitude;
                    document.getElementById('viewLongitude').textContent = data.longitude;
                    document.getElementById('viewDescription').textContent = data.description;
                    document.getElementById('viewProductsCount').textContent = data.products_count + ' produits';
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors du chargement des données.');
                });
        });
    }

    // Gestion du modal de modification
    const editFarmerModal = document.getElementById('editFarmerModal');
    if (editFarmerModal) {
        editFarmerModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const farmerId = button.getAttribute('data-farmer');
            const form = this.querySelector('form');

            // Mettre à jour l'action du formulaire
            form.action = `/admin/farmers/${farmerId}`;

            // Afficher l'indicateur de chargement
            const modalBody = this.querySelector('.modal-body');
            modalBody.innerHTML = '<div class="text-center"><div class="spinner-border" role="status"><span class="visually-hidden">Chargement...</span></div></div>';

            // Récupérer les données du farmer
            fetch(`/admin/farmers/${farmerId}`)
                .then(response => response.json())
                .then(data => {
                    // Mettre à jour les champs du formulaire
                    document.getElementById('editName').value = data.name;
                    document.getElementById('editEmail').value = data.email;
                    document.getElementById('editPhone').value = data.phone;
                    document.getElementById('editAddress').value = data.address;
                    document.getElementById('editLatitude').value = data.latitude;
                    document.getElementById('editLongitude').value = data.longitude;
                    document.getElementById('editDescription').value = data.description;
                    document.getElementById('editIsActive').checked = data.is_active;
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue lors du chargement des données.');
                });
        });
    }

    // Gestion de la géolocalisation automatique
    const addressInput = document.getElementById('editAddress');
    if (addressInput) {
        addressInput.addEventListener('change', function() {
            const address = this.value;
            if (address) {
                fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data[0]) {
                            document.getElementById('editLatitude').value = data[0].lat;
                            document.getElementById('editLongitude').value = data[0].lon;
                        }
                    })
                    .catch(error => console.error('Erreur de géocodage:', error));
            }
        });
    }
});
</script>
@endpush
