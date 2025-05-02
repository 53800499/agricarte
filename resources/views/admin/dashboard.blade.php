@extends('layouts.admin')

@section('title', 'Tableau de bord')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4">Tableau de bord</h1>

        <!-- Statistics Cards -->
        <div class="row mb-4">


            <div class="col-xl-4 col-md-8 mb-4">
                <div class="card bg-primary h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Totals produits</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['products_count'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-8 mb-4">
                <div class="card bg-success h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Agriculteurs</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['farmers_count'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-8 mb-4">
                <div class="card bg-warning h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-white text-uppercase mb-1">
                                    Produits</div>
                                <div class="h5 mb-0 font-weight-bold text-white">{{ $stats['products_count'] }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-box fa-2x text-white"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <!-- Recent Farmers -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Agriculteurs récents</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Email</th>
                                        <th>Date d'inscription</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stats['recent_farmers'] as $farmer)
                                        <tr>
                                            <td>{{ $farmer->firstname }} {{ $farmer->lastname }}</td>
                                            <td>{{ $farmer->email }}</td>
                                            <td>{{ $farmer->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Products -->
            <div class="col-xl-6 col-lg-6">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Produits récents</h6>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered" width="100%" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th>Nom</th>
                                        <th>Prix</th>
                                        <th>Agriculteur</th>
                                        <th>Date d'ajout</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($stats['recent_products'] as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ number_format($product->price, 2) }} €</td>
                                            <td>{{ $product->user->firstname }} {{ $product->user->lastname }}</td>
                                            <td>{{ $product->created_at->format('d/m/Y') }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
