<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Candidate;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Setting Awal (Voting ditutup dulu default-nya)
        Setting::updateOrCreate(
            ['key' => 'voting_status'],
            ['value' => 'closed']
        );

        // 2. Buat Dummy Kandidat Ketua OSIS (Misal 2 Paslon)
        Candidate::truncate();
        Candidate::create([
            'nomor_urut' => 1,
            'nama_ketua' => 'Ahmad Fauzi',
            'nama_wakil' => 'Siti Aminah',
            'visi' => 'Menjadikan OSIS SMA Mazra\'atul Ulum yang aktif, religius, dan berteknologi.',
            'misi' => "1. Mengadakan kegiatan seni dan olahraga rutin.\n2. Optimalisasi mading digital sekolah.",
            'foto' => 'kandidat1.jpg'
        ]);

        Candidate::create([
            'nomor_urut' => 2,
            'nama_ketua' => 'M. Rizki Ramadhan',
            'nama_wakil' => 'Dewi Lestari',
            'visi' => 'Mewujudkan lingkungan sekolah yang kreatif, disiplin, dan aspiratif.',
            'misi' => "1. Wadah aspirasi siswa transparan.\n2. Program English & Coding Club.",
            'foto' => 'kandidat2.jpg'
        ]);

        // 3. Buat Beberapa Contoh Data Siswa (DPT Uji Coba)
        Student::truncate();
        $sampleStudents = [
            ['nis' => '1001', 'nama' => 'Budi Santoso', 'kelas' => 'XII IPA 1'],
            ['nis' => '1002', 'nama' => 'Ani Rahmawati', 'kelas' => 'XII IPA 2'],
            ['nis' => '1003', 'nama' => 'Dimas Prayoga', 'kelas' => 'XI IPS 1'],
        ];

        foreach ($sampleStudents as $stu) {
            Student::create([
                'nis' => $stu['nis'],
                'nama' => $stu['nama'],
                'kelas' => $stu['kelas'],
                'qr_token' => 'MAZRA-' . $stu['nis'] . '-' . strtoupper(Str::random(5)), // Contoh token QR unik
                'has_voted' => false
            ]);
        }
    }
}