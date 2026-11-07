<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Vote;
use Illuminate\Http\Request;
use Carbon\Carbon;

class VotingController extends Controller
{
    // 1. Tampilkan Halaman Scanner QR Code Siswa
    public function scanner()
    {
        // Cek apakah status voting sedang dibuka oleh admin
        $setting = Setting::where('key', 'voting_status')->first();
        $votingStatus = $setting ? $setting->value : 'closed';

        return view('voting.scanner', compact('votingStatus'));
    }

    // 2. Proses Verifikasi Token QR dari Hasil Scan Kamera
    public function verify(Request $request)
    {
        $request->validate([
            'qr_token' => 'required|string',
        ]);

        $student = Student::where('qr_token', $request->qr_token)->first();

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'QR Code tidak terdaftar dalam DPT!'
            ], 404);
        }

        if ($student->has_voted) {
            return response()->json([
                'success' => false,
                'message' => 'Peringatan: Pemilih atas nama ' . $student->nama . ' (NIS: ' . $student->nis . ') sudah menggunakan hak suaranya!'
            ], 400);
        }

        // Simpan session sementara bahwa siswa ini sah masuk bilik suara
        session(['voting_student_id' => $student->id]);

        return response()->json([
            'success' => true,
            'redirect' => route('voting.booth')
        ]);
    }

    // 3. Tampilkan Bilik Suara (Pilihan Kandidat)
    public function booth()
    {
        $studentId = session('voting_student_id');
        if (!$studentId) {
            return redirect()->route('voting.scanner')->with('error', 'Silakan scan QR Code kartu pelajar terlebih dahulu.');
        }

        $student = Student::findOrFail($studentId);
        if ($student->has_voted) {
            session()->forget('voting_student_id');
            return redirect()->route('voting.scanner')->with('error', 'Anda sudah menggunakan hak suara.');
        }

        $candidates = Candidate::orderBy('nomor_urut')->get();

        return view('voting.booth', compact('student', 'candidates'));
    }

    // 4. Proses Submit Pilihan Suara
    public function submit(Request $request)
    {
        $request->validate([
            'candidate_id' => 'required|exists:candidates,id',
        ]);

        $studentId = session('voting_student_id');
        if (!session()->has('voting_student_id')) {
            return redirect()->route('voting.scanner')->with('error', 'Sesi pemilihan berakhir atau tidak valid.');
        }

        $student = Student::findOrFail($studentId);
        if ($student->has_voted) {
            return redirect()->route('voting.scanner')->with('error', 'Hak suara sudah digunakan.');
        }

        // Simpan data suara ke tabel votes
        Vote::create([
            'student_id' => $student->id,
            'candidate_id' => $request->candidate_id,
        ]);

        // Update total suara kandidat
        $candidate = Candidate::findOrFail($request->candidate_id);
        $candidate->increment('total_votes');

        // Update status siswa menjadi sudah memilih
        $student->update([
            'has_voted' => true,
            'voted_at' => Carbon::now(),
        ]);

        // Hapus session bilik suara
        session()->forget('voting_student_id');

        return redirect()->route('voting.success')->with('voted_name', $student->nama);
    }

    // 5. Halaman Sukses Memilih
    public function success()
    {
        return view('voting.success');
    }
}