<?php

namespace App\Http\Controllers;

use App\Models\Candidate;
use App\Models\Setting;
use App\Models\Student;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    // 1. Dashboard Utama Admin (Real-time & Voice Announcer)
    public function dashboard()
    {
        $candidates = Candidate::orderBy('nomor_urut')->get();
        $totalDpt = Student::count();
        $totalVoted = Student::where('has_voted', true)->count();
        $totalUnvoted = $totalDpt - $totalVoted;
        
        // Siswa yang belum memilih (untuk dipantau)
        $unvotedStudents = Student::where('has_voted', false)->orderBy('kelas')->orderBy('nama')->get();
        
        // Ambil riwayat suara masuk secara kronologis (untuk mode pembacaan dramatis)
        // Kita gabungkan dengan data nama siswa dan paslon yang dipilih
        $votesLog = Vote::with(['student', 'candidate'])
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function($vote) {
                return [
                    'id' => $vote->id,
                    'student_name' => $vote->student->nama ?? 'Siswa',
                    'student_class' => $vote->student->kelas ?? '-',
                    'candidate_number' => $vote->candidate->nomor_urut ?? 0,
                    'candidate_name' => $vote->candidate->nama_ketua ?? '-',
                    'time' => $vote->created_at->format('H:i:s')
                ];
            });

        $setting = Setting::where('key', 'voting_status')->first();
        $votingStatus = $setting ? $setting->value : 'closed';

        return view('admin.dashboard', compact(
            'candidates', 'totalDpt', 'totalVoted', 'totalUnvoted', 
            'unvotedStudents', 'votesLog', 'votingStatus'
        ));
    }

    // 2. Toggle Buka / Tutup Voting
    public function toggleStatus(Request $request)
    {
        $status = $request->status; // 'open' atau 'closed'
        Setting::updateOrCreate(
            ['key' => 'voting_status'],
            ['value' => $status]
        );

        return back()->with('success', 'Status pemilihan berhasil diubah menjadi: ' . strtoupper($status));
    }

    // 3. Reset Voting (Hapus semua suara & kembalikan status siswa jadi belum memilih)
    public function resetVoting()
    {
        DB::transaction(function () {
            Vote::truncate();
            Student::query()->update([
                'has_voted' => false,
                'voted_at' => null
            ]);
            Candidate::query()->update([
                'total_votes' => 0
            ]);
        });

        return back()->with('success', 'Data pemilihan berhasil direset! Semua suara dikosongkan untuk uji coba/pemilihan baru.');
    }
    
    // 4. Ambil Data Suara yang Belum Diumumkan (untuk mode pembacaan dramatis)
    public function getUnannouncedVotes()
    {
        $votes = \App\Models\Vote::with('candidate')
                    ->where('is_announced', false)
                    ->orderBy('created_at', 'asc')
                    ->get()
                    ->map(function($vote) {
                        return [
                            'id' => $vote->id,
                            'candidate_name' => $vote->candidate->nama ?? 'Calon',
                            'candidate_number' => $vote->candidate->nomor_urut ?? '',
                        ];
                    });

        return response()->json($votes);
    }

    // 5. Tandai Suara Telah Diumumkan / Dibacakan oleh Voice Announcer
    public function markAnnounced($id)
    {
        $vote = \App\Models\Vote::find($id);
        if ($vote) {
            $vote->is_announced = true;
            $vote->save();
        }

        return response()->json(['success' => true]);
    }
}