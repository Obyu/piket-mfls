<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index()
    {
        // Menampilkan data terbaru di atas
        $leads = Lead::latest()->get();
        return view('staff.leads.index', compact('leads'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'email' => 'nullable|email',
            'major_interest' => 'nullable|string|max:255',
            'status' => 'required|in:new,followed_up,interested,not_interested,registered',
            'notes' => 'nullable|string',
        ]);

        Lead::create($request->all());

        return redirect()->route('staff.leads.index')->with('success', 'Data calon pendaftar berhasil ditambahkan.');
    }
    public function update(Request $request, Lead $lead)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'email' => 'nullable|email',
            'major_interest' => 'nullable|string|max:255',
            'status' => 'required|in:new,followed_up,interested,not_interested,registered',
            'notes' => 'nullable|string',
        ]);

        $lead->update($request->all());

        return redirect()->route('staff.leads.index')->with('success', 'Status follow-up berhasil diperbarui.');
    }
    public function destroy(Lead $lead)
    {
        $lead->delete();
        return redirect()->route('staff.leads.index')->with('success', 'Data lead berhasil dihapus.');
    }
}