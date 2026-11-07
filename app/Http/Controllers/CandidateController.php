<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidateController extends Controller
{
    public function index()
    {
        $candidates = Candidate::orderBy('nomor_urut')->get();
        return view('admin.candidates.index', compact('candidates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor_urut' => 'required|numeric|unique:candidates,nomor_urut',
            'nama' => 'required|string|max:255',
            'visi' => 'required|string',
            'misi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('candidates', 'public');
        }

        Candidate::create([
            'nomor_urut' => $request->nomor_urut,
            'nama' => $request->nama,
            'visi' => $request->visi,
            'misi' => $request->misi,
            'foto' => $fotoPath,
        ]);

        return back()->with('success', 'Calon Ketua OSIS berhasil ditambahkan!');
    }

    public function destroy($id)
    {
        $candidate = Candidate::findOrFail($id);
        if ($candidate->foto) {
            Storage::disk('public')->delete($candidate->foto);
        }
        $candidate->delete();

        return back()->with('success', 'Data calon berhasil dihapus!');
    }
}