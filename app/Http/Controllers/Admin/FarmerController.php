<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FarmerController extends Controller
{
    public function index()
    {
        $farmers = User::where('role', 'farmer')->paginate(10);
        return view('admin.farmers.index', compact('farmers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $farmer = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'latitude' => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'role' => 'farmer',
        ]);

        return response()->json($farmer, 201);
    }

    public function show(User $farmer)
    {
        return $farmer;
    }

    public function update(Request $request, User $farmer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $farmer->id,
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
        ]);

        $farmer->update($validated);

        return response()->json($farmer);
    }

    public function destroy(User $farmer)
    {
        $farmer->delete();
        return response()->json(null, 204);
    }
}