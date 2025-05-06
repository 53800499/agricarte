@extends('layouts.admin')

@section('title', 'Gestion des Producteurs')

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
            <h1 class="h3 mb-0 text-gray-800">Gestion des Producteurs</h1>
            <a href="{{ route('admin.farmers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un producteur
            </a>
        </div>

        <!-- Filtres -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <form action="{{ route('admin.farmers.index') }}" method="GET" class="row g-3">
                    <div class="col-md-4">
                        <label for="search" class="form-label">Recherche</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="{{ request('search') }}" placeholder="Nom, email, téléphone...">
                    </div>

                    <div class="col-md-3">
                        <label for="status" class="form-label">Statut</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tous les statuts</option>
                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Actif</option>
                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactif</option>
                        </select>
                    </div>

                    <div class="col-md-3">
                        <label for="sort" class="form-label">Trier par</label>
                        <select class="form-select" id="sort" name="sort">
                            <option value="latest" {{ request('sort') === 'latest' ? 'selected' : '' }}>Plus récents</option>
                            <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Plus anciens</option>
                            <option value="name" {{ request('sort') === 'name' ? 'selected' : '' }}>Nom (A-Z)</option>
                        </select>
                    </div>

                    <div class="col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-filter"></i> Filtrer
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Liste des producteur -->
        <div class="card shadow mb-4">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Photo</th>
                                <th>Nom</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Localisation</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($farmers as $farmer)
                                <tr>
                                    <td>
                                        @if($farmer->profile_image)
                                            <img src="{{ asset('storage/' . $farmer->profile_image) }}"
                                                 alt="{{ $farmer->name }}"
                                                 class="img-thumbnail"
                                                 style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-user text-white"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td>{{ $farmer->name }}</td>
                                    <td>{{ $farmer->email }}</td>
                                    <td>{{ $farmer->phone ?? 'Non renseigné' }}</td>
                                    <td>
                                        @if($farmer->latitude && $farmer->longitude)
                                            <a href="https://www.google.com/maps?q={{ $farmer->latitude }},{{ $farmer->longitude }}"
                                               target="_blank" class="text-primary">
                                                <i class="fas fa-map-marker-alt"></i> Voir sur la carte
                                            </a>
                                        @else
                                            Non renseigné
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge {{ $farmer->is_active ? 'bg-success' : 'bg-danger' }}">
                                            {{ $farmer->is_active ? 'Actif' : 'Inactif' }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.farmers.show', $farmer) }}"
                                               class="btn btn-sm btn-outline-info">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('admin.farmers.edit', $farmer) }}"
                                               class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.farmers.destroy', $farmer) }}"
                                                  method="POST"
                                                  class="d-inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet producteur ?');">
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
                                    <td colspan="7" class="text-center">
                                        Aucun producteur trouvé
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $farmers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
