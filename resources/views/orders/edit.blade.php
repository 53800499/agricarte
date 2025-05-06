@extends('layouts.app')

@section('title', 'Modifier la Commande - AgriCarte')

@section('content')
@include('orders.form', ['order' => $order])
@endsection
