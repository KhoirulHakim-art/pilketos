<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class StudentController extends Controller
{
    // 1. Download Template CSV/Panduan Format
    public function downloadTemplate(): StreamedResponse
    {
        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=template_dpt_mazra.csv",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['nis', 'nama', 'kelas', 'qr_token'];

        $callback = function() use ($columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            fputcsv($file, ['1001', 'Ahmad Contoh', 'XII IPA 1', 'TOKEN-KARTU-001']);
            fputcsv($file, ['1002', 'Siti Aminah', 'XII IPA 2', 'TOKEN-KARTU-002']);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    // 2. Proses Simpan Data dari Hasil Copy-Paste Excel atau File Upload
    public function import(Request $request)
    {
        $request->validate([
            'raw_data' => 'required|string',
        ]);

        $rows = explode("\n", trim($request->raw_data));
        $importedCount = 0;
        $skippedHeader = false;

        foreach ($rows as $row) {
            // Bersihkan spasi berlebih dan pisahkan berdasarkan Tab (hasil copy paste Excel) atau koma
            $row = trim($row);
            if (empty($row)) continue;

            // Deteksi pemisah tab (\t) atau koma (,)
            $cols = str_contains($row, "\t") ? explode("\t", $row) : str_getcsv($row);

            if (count($cols) >= 3) {
                $nis = trim($cols[0]);
                $nama = trim($cols[1]);
                $kelas = trim($cols[2]);
                // Jika kolom ke-4 (token) diisi manual, gunakan. Jika kosong, buat otomatis dari NIS.
                $qrToken = isset($cols[3]) && !empty(trim($cols[3])) ? trim($cols[3]) : 'MAZRA-' . $nis;

                // Lewatkan jika baris pertama adalah judul kolom (header)
                if (strtolower($nis) === 'nis' || strtolower($nis) === 'nisn') {
                    continue;
                }

                Student::updateOrCreate(
                    ['nis' => $nis],
                    [
                        'nama' => $nama,
                        'kelas' => $kelas,
                        'qr_token' => $qrToken
                    ]
                );
                $importedCount++;
            }
        }

        return back()->with('success', "Berhasil menyimpan $importedCount data pemilih!");
    }

    // 3. Halaman Daftar Siswa & Cetak Kartu QR Code
    public function index()
    {
        $students = Student::orderBy('kelas')->orderBy('nama')->get();
        return view('admin.students.index', compact('students'));
    }
    // 4. Hapus Satu Data Siswa
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return back()->with('success', 'Data siswa berhasil dihapus!');
    }

    // 5. Kosongkan Seluruh Data DPT
    public function clearAll()
    {
        Student::truncate();

        return back()->with('success', 'Semua data DPT berhasil dikosongkan!');
    }
}