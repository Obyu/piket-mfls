<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Team;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    public function index()
    {
        $teams = Team::latest()->get();
        return view('admin.teams.index', compact('teams'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name'
        ]);

        Team::create($request->all());

        return redirect()->route('admin.teams.index')->with('success', 'Kelompok berhasil ditambahkan.');
    }

    public function update(Request $request, Team $team)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:teams,name,' . $team->id
        ]);

        $team->update($request->all());

        return redirect()->route('admin.teams.index')->with('success', 'Nama kelompok berhasil diperbarui.');
    }

    public function destroy(Team $team)
    {
        $team->delete();
        return redirect()->route('admin.teams.index')->with('success', 'Kelompok berhasil dihapus.');
    }
}