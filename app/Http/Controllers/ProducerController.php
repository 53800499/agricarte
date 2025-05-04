<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ProducerController extends Controller
{
    public function show(User $producer)
    {
        // Vérifier que l'utilisateur est bien un producteur
        if ($producer->role !== 'farmer') {
            abort(404);
        }

        // Charger les relations nécessaires
        $producer->load(['products.category']);

        return view('producers.show', compact('producer'));
    }
}