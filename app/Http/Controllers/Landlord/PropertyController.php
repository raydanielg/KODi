<?php

namespace App\Http\Controllers\Landlord;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        return view('landlord.properties.index');
    }

    public function create()
    {
        return view('landlord.properties.create');
    }

    public function store(Request $request)
    {
        return redirect()->route('landlord.properties.index');
    }

    public function show($id)
    {
        return view('landlord.properties.show', compact('id'));
    }

    public function edit($id)
    {
        return view('landlord.properties.edit', compact('id'));
    }

    public function update(Request $request, $id)
    {
        return redirect()->route('landlord.properties.index');
    }

    public function destroy($id)
    {
        return redirect()->route('landlord.properties.index');
    }
}
