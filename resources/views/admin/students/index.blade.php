<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen DPT & QR Code - Pilketos Mazra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @media print {
            .no-print { display: none; }
            .print-card { break-inside: avoid; border: 1px solid #ccc; padding: 15px; margin-bottom: 10px; border-radius: 8px; }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800 font-sans">

    <div class="max-w-6xl mx-auto p-6">
        <div class="flex justify-between items-center mb-6 no-print">
            <h1 class="text-2xl font-bold text-emerald-800">📋 Manajemen DPT & Kartu QR Code Pemilih</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-sm bg-slate-200 hover:bg-slate-300 text-slate-800 font-medium px-4 py-2 rounded-lg transition"> Dashboard Admin </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded mb-4 no-print">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Input Data (Copy Paste Excel & Upload) -->
        <div class="bg-white p-6 rounded-xl shadow-sm border mb-8 no-print">
            <h2 class="text-lg font-semibold mb-2">Tambah / Update Data Pemilih Tetap (DPT)</h2>
            <p class="text-xs text-gray-500 mb-4">
                Tip: Anda bisa langsung **Copy baris data dari Excel** (Kolom: <b>NIS | Nama | Kelas | Token QR [Opsional]</b>) lalu **Paste** ke kotak di bawah ini secara massal!
            </p>

            <form action="{{ route('admin.students.import') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <textarea name="raw_data" rows="5" class="w-full text-sm font-mono border p-3 rounded-lg bg-gray-50 focus:ring-2 focus:ring-emerald-500 focus:outline-none" placeholder="Contoh Paste dari Excel:
1001	Ahmad Fauzi	XII IPA 1	TOKEN-SISWA-01
1002	Siti Aminah	XII IPA 2	TOKEN-SISWA-02" required></textarea>
                </div>
                <div class="flex justify-between items-center">
                    <a href="{{ route('admin.students.template') }}" class="text-xs text-sky-600 underline font-medium">
                        📥 Download Contoh Format Template CSV
                    </a>
                    <button type="submit" class="bg-emerald-600 text-white px-6 py-2 rounded-lg text-sm hover:bg-emerald-700 font-medium shadow-sm">
                        💾 Simpan / Perbarui Data DPT
                    </button>
                </div>
            </form>
        </div>

<!-- Tabel DPT & Tombol Aksi -->
        <div class="bg-white p-6 rounded-xl shadow-sm border">
            <div class="flex justify-between items-center mb-4 no-print">
                <h2 class="text-lg font-semibold">Daftar Pemilih Terdaftar (Total: {{ $students->count() }} Siswa)</h2>
                
                <div class="flex gap-2">
                    <!-- Tombol Kosongkan Semua -->
                    @if($students->count() > 0)
                    <form action="{{ route('admin.students.clear') }}" method="POST" onsubmit="return confirm('PERINGATAN: Yakin ingin menghapus SELURUH data DPT?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-100 text-red-700 px-3 py-2 rounded-lg text-sm hover:bg-red-200 font-medium">
                            🗑️ Kosongkan Semua
                        </button>
                    </form>
                    @endif

                    <!-- Tombol Cetak -->
                    <button onclick="window.print()" class="bg-gray-800 text-white px-4 py-2 rounded-lg text-sm hover:bg-black font-medium">
                        🖨️ Cetak Kartu Pelajar
                    </button>
                </div>
            </div>

            <!-- Tabel Data di Layar -->
            <div class="overflow-x-auto no-print">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-100 text-xs uppercase text-gray-600 border-b">
                            <th class="p-3">NIS</th>
                            <th class="p-3">Nama Siswa</th>
                            <th class="p-3">Kelas</th>
                            <th class="p-3">Token QR / Kode Unik</th>
                            <th class="p-3 text-center">Status Pilih</th>
                            <th class="p-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y text-sm">
                        @forelse($students as $student)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 font-medium">{{ $student->nis }}</td>
                            <td class="p-3">{{ $student->nama }}</td>
                            <td class="p-3">{{ $student->kelas }}</td>
                            <td class="p-3 font-mono text-xs text-emerald-700 font-bold">{{ $student->qr_token }}</td>
                            <td class="p-3 text-center">
                                @if($student->has_voted)
                                    <span class="bg-emerald-100 text-emerald-800 text-xs px-2.5 py-1 rounded-full font-semibold">Sudah Memilih</span>
                                @else
                                    <span class="bg-amber-100 text-amber-800 text-xs px-2.5 py-1 rounded-full font-semibold">Belum</span>
                                @endif
                            </td>
                            <td class="p-3 text-center">
                                <!-- Tombol Hapus Satuan -->
                                <form action="{{ route('admin.students.destroy', $student->id) }}" method="POST" onsubmit="return confirm('Hapus data siswa {{ $student->nama }}?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 p-1.5 rounded-lg text-xs font-medium" title="Hapus Siswa">
                                        ❌ Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="p-6 text-center text-gray-400">Belum ada data pemilih. Silakan paste data DPT di atas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <!-- Bagian Cetak Kartu Pelajar -->
            <div class="hidden print:grid grid-cols-2 gap-4">
                @foreach($students as $student)
                <div class="print-card border-2 border-dashed border-gray-400 p-4 rounded-xl flex justify-between items-center bg-white">
                    <div>
                        <span class="text-[10px] font-bold text-emerald-700 tracking-wider uppercase">SMA Mazra'atul Ulum Paciran</span>
                        <h3 class="font-bold text-base mt-1">{{ $student->nama }}</h3>
                        <p class="text-xs text-gray-600">NIS: {{ $student->nis }} | Kelas: {{ $student->kelas }}</p>
                        <p class="text-[9px] text-gray-500 mt-2 font-mono font-bold">QR Token: {{ $student->qr_token }}</p>
                    </div>
                    <div class="text-center">
                        <!-- QR Code men-generate token manual sesuai input di database -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ urlencode($student->qr_token) }}" alt="QR Code" class="w-20 h-20 mx-auto">
                        <span class="text-[9px] text-gray-500 block mt-1">Scan untuk Memilih</span>
                    </div>
                </div>
                @endforeach
            </div>

        </div>
    </div>

</body>
</html>