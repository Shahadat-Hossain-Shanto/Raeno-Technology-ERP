<?php

namespace App\Http\Controllers;

use App\Models\Transport;
use Illuminate\Http\Request;


class TransportController extends Controller
{
    public function index()
    {
        $transports = Transport::all();

        return view('transport.index', compact('transports'));
    }

    // Show create form
    public function create()
    {
        return view('transport.create');
    }

    // Store new
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'           => 'required|string|max:255',
        ]);

        Transport::create($validated);

        return redirect()->route('transports.index')
            ->with('success', 'Transport created successfully.');
    }

    // Show edit form
    public function edit(Transport $transport)
    {
        $transport = Transport::findOrFail($transport->id);
        return view('transport.edit', compact('transport'));
    }

    // Update existing
    public function update(Request $request, Transport $transport)
    {
        $validated = $request->validate([
            'name'            => 'required|string|max:255',
        ]);

        $transport->update($validated);

        return redirect()->route('transports.index')
            ->with('success', 'Transport updated successfully.');
    }

    // Delete gallery item
    public function destroy(Transport $transport)
    {

        $transport->delete();

        return redirect()->route('transports.index')->with('success', 'Transport deleted successfully.');
    }

    public function data()
    {
        $transports = Transport::select(['id', 'name'])->get();
        return response()->json(['data' => $transports]);
    }

}
