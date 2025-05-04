@extends('layouts.app')

@section('title', 'Modifier la Commande - AgriCarte')

@section('content')
@include('admin.orders.form', ['order' => $order])
@endsection
