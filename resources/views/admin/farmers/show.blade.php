@extends('layouts.admin')

@section('title', 'Détails de l\'agriculteur')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Détails de l'agriculteur</h3>
                        <div class="btn-group">
                            <a href="{{ route('admin.farmers.edit', $farmer) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <form action="{{ route('admin.farmers.destroy', $farmer) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet agriculteur ?')">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informations personnelles</h5>
                            <table class="table">
                                <tr>
                                    <th>Nom complet</th>
                                    <td>{{ $farmer->firstname }} {{ $farmer->lastname }}</td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td>{{ $farmer->email }}</td>
                                </tr>
                                <tr>
                                    <th>Téléphone</th>
                                    <td>{{ $farmer->phone ?? 'Non renseigné' }}</td>
                                </tr>
                                <tr>
                                    <th>Date d'inscription</th>
                                    <td>{{ $farmer->created_at->format('d/m/Y') }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Statistiques</h5>
                            <table class="table">
                                <tr>
                                    <th>Produits publiés</th>
                                    <td>{{ $farmer->products->count() }}</td>
                                </tr>
                                <tr>
                                    <th>Dernière connexion</th>
                                    <td>{{ $farmer->last_login_at ? $farmer->last_login_at->format('d/m/Y H:i') : 'Jamais' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h5>Produits</h5>
                        @if($farmer->products->count() > 0)
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Nom</th>
                                            <th>Catégorie</th>
                                            <th>Prix</th>
                                            <th>Quantité</th>
                                            <th>Date de publication</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($farmer->products as $product)
                                            <tr>
                                                <td>{{ $product->name }}</td>
                                                <td>{{ $product->category->name }}</td>
                                                <td>{{ $product->price }}€/{{ $product->unit }}</td>
                                                <td>{{ $product->quantity }}</td>
                                                <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="alert alert-info">
                                Cet agriculteur n'a pas encore publié de produits.
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.farmers.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Retour à la liste
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
