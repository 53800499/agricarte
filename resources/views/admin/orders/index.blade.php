@extends('layouts.admin')

@section('title', 'Gestion des Commandes - AgriCarte')

@section('content')
<div class="container-fluid py-4">
    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Commandes du jour</h6>
                            <h3 class="mb-0">{{ $todayOrders ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-shopping-cart fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Commandes en cours</h6>
                            <h3 class="mb-0">{{ $processingOrders ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-clock fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-info text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Chiffre d'affaires</h6>
                            <h3 class="mb-0">{{ number_format($monthlyRevenue ?? 0, 2) }} FCFA</h3>
                        </div>
                        <i class="fas fa-chart-line fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">Commandes en attente</h6>
                            <h3 class="mb-0">{{ $pendingOrders ?? 0 }}</h3>
                        </div>
                        <i class="fas fa-hourglass-half fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Liste des Commandes</h5>
            {{-- <a href="{{ Auth()->user()->role=='admin' ? route('admin.orders.create') : route('farmer.orders.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Nouvelle Commande
            </a> --}}
        </div>
        <div class="card-body">
            <!-- Filtres -->
            <form action="{{ route('orders.index') }}" method="GET" class="mb-4">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Recherche</label>
                        <input type="text" name="search" value="{{ request('search') }}"
                               class="form-control" placeholder="ID, client, email...">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Statut</label>
                        <select name="status" class="form-select">
                            <option value="">Tous</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>En attente</option>
                            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>En traitement</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Expédiée</option>
                            <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Livrée</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date début</label>
                        <input type="date" name="start_date" value="{{ request('start_date') }}"
                               class="form-control">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Date fin</label>
                        <input type="date" name="end_date" value="{{ request('end_date') }}"
                               class="form-control">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Tri</label>
                        <select name="sort" class="form-select">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Plus récent</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Plus ancien</option>
                            <option value="total_asc" {{ request('sort') == 'total_asc' ? 'selected' : '' }}>Montant croissant</option>
                            <option value="total_desc" {{ request('sort') == 'total_desc' ? 'selected' : '' }}>Montant décroissant</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-filter me-2"></i>Filtrer
                        </button>
                        <a href="{{ route('orders.index') }}" class="btn btn-secondary">
                            <i class="fas fa-redo me-2"></i>Réinitialiser
                        </a>
                    </div>
                </div>
            </form>

            <!-- Tableau des commandes -->
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Client</th>
                            <th>Statut</th>
                            <th>Montant</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $order)
                            <tr>
                                <td>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                <td>
                                    <div>{{ $order->user->name }}</div>
                                    <small class="text-muted">{{ $order->user->email }}</small>
                                </td>
                                <td>
                                    <span class="badge
                                        @if($order->status == 'pending') bg-warning
                                        @elseif($order->status == 'processing') bg-info
                                        @elseif($order->status == 'shipped') bg-primary
                                        @elseif($order->status == 'delivered') bg-success
                                        @else bg-danger @endif">
                                        <i class="fas
                                            @if($order->status == 'pending') fa-clock
                                            @elseif($order->status == 'processing') fa-cog
                                            @elseif($order->status == 'shipped') fa-truck
                                            @elseif($order->status == 'delivered') fa-check
                                            @else fa-times @endif me-1">
                                        </i>
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ number_format($order->total_amount, 2) }} FCFA</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ Auth()->user()->role=='admin' ? route('admin.orders.show', $order) : route('farmer.orders.show', $order) }}" class="btn btn-sm btn-outline-info" title="Voir">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ Auth()->user()->role=='admin' ? route('admin.orders.edit', $order) : route('farmer.orders.edit', $order) }}" class="btn btn-sm btn-outline-primary" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ Auth()->user()->role=='admin' ? route('admin.orders.destroy', $order) : route('farmer.orders.destroy', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger" title="Supprimer"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette commande ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="fas fa-inbox fa-3x mb-3"></i>
                                        <p>Aucune commande trouvée</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="d-flex justify-content-between align-items-center mt-4">
                <div class="text-muted">
                    Affichage de {{ ($orders->currentPage() - 1) * $orders->perPage() + 1 }} à {{ min($orders->currentPage() * $orders->perPage(), $orders->total()) }} sur {{ $orders->total() }} commandes
                </div>
                <div>
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
