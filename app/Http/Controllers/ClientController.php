<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->get();
        return view('clients.index', compact('clients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required',
            'telephone' => 'nullable',
            'email' => 'nullable|email',
            'adresse' => 'nullable'
        ]);

        Client::create($request->all());
        return back()->with('success', 'Client ajouté avec succès');
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nom' => 'required',
            'telephone' => 'nullable',
            'email' => 'nullable|email',
            'adresse' => 'nullable'
        ]);

        $client->update($request->all());
        return back()->with('success', 'Client modifié avec succès');
    }

    public function destroy($id)
    {
        $client = Client::findOrFail($id);
        $client->delete();

        return redirect()->route('clients.index')->with('success', 'Client supprimé avec succès.');
    }
} 