<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $producers = [
            [
                'id' => 1,
                'name' => 'Ferme des Oliviers',
                'address' => '123 Chemin des Oliviers, 13100 Aix-en-Provence',
                'lat' => 43.529742,
                'lng' => 5.447427,
                'products' => ['Légumes', 'Fruits']
            ],
            [
                'id' => 2,
                'name' => 'Jardins de Provence',
                'address' => '45 Route de Marseille, 13080 Marseille',
                'lat' => 43.296482,
                'lng' => 5.369780,
                'products' => ['Légumes', 'Herbes aromatiques']
            ],
            [
                'id' => 3,
                'name' => 'Potager Bio',
                'address' => '78 Avenue des Champs, 13090 Aix-en-Provence',
                'lat' => 43.539742,
                'lng' => 5.457427,
                'products' => ['Légumes bio', 'Fruits bio']
            ],
            [
                'id' => 4,
                'name' => 'Vergers du Sud',
                'address' => '12 Chemin des Pommiers, 13540 Puyricard',
                'lat' => 43.589742,
                'lng' => 5.427427,
                'products' => ['Fruits', 'Jus de fruits']
            ]
        ];

        return view('map', compact('producers'));
    }
}
