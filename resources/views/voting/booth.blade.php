<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bilik Suara - Pilketos Mazra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen p-4 flex flex-col justify-between">

    <div class="max-w-4xl mx-auto w-full pt-6">
        <!-- Header Info Pemilih -->
        <div class="bg-slate-800 border border-slate-700 p-4 rounded-2xl flex justify-between items-center mb-6 shadow-sm">
            <div>
                <span class="text-xs text-emerald-400 font-bold uppercase tracking-wider">Bilik Suara Sah</span>
                <h2 class="text-lg font-bold text-white">{{ $student->nama }}</h2>
                <p class="text-xs text-slate-400">NIS: {{ $student->nis }} | Kelas: {{ $student->kelas }}</p>
            </div>
            <div class="text-right">
                <span class="bg-emerald-950 text-emerald-400 border border-emerald-800 text-xs px-3 py-1.5 rounded-xl font-semibold">
                    Silakan Pilih 1 Calon
                </span>
            </div>
        </div>

        <h1 class="text-xl font-bold mb-6 text-center">Daftar Kandidat Ketua OSIS</h1>

        <!-- Form Pemilihan Kandidat -->
        <form action="{{ route('voting.submit') }}" method="POST" id="votingForm">
            @csrf
            <div class="grid grid-cols-1 gap-6 mb-6">
                @foreach($candidates as $candidate)
                <label class="cursor-pointer bg-slate-800 border-2 border-slate-700 hover:border-emerald-500 p-6 rounded-2xl transition-all relative group shadow-xl flex flex-col justify-between">
                    <!-- Radio Button Pilih -->
                    <input type="radio" name="candidate_id" value="{{ $candidate->id }}" class="absolute top-5 right-5 w-6 h-6 accent-emerald-500 z-10 cursor-pointer" required>
                    
                    <!-- Bagian Utama: Flex Row (Foto Potrait di Kiri, Teks di Kanan) -->
                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        
                        <!-- Kolom Foto Potrait Besar -->
                        <div class="w-full md:w-52 shrink-0 flex flex-col items-center">
                            @if($candidate->foto)
                                <img src="{{ asset('storage/' . $candidate->foto) }}" class="w-full h-64 object-cover rounded-xl border border-slate-600 shadow-md">
                            @else
                                <div class="w-full h-64 bg-slate-700 rounded-xl flex flex-col items-center justify-center text-xs text-slate-400 border border-slate-600">
                                    <svg class="w-12 h-12 mb-2 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    Tanpa Foto
                                </div>
                            @endif
                            
                            <!-- Nomor Urut Tanpa Tanda # -->
                            <div class="mt-3 w-full bg-emerald-950/90 border border-emerald-700/50 text-emerald-400 font-bold text-center py-2 rounded-xl text-sm shadow-inner">
                                Nomor Urut {{ $candidate->nomor_urut }}
                            </div>
                        </div>

                        <!-- Kolom Informasi (Nama, Visi, Misi di Sebelah Kanan) -->
                        <div class="flex-1 w-full">
                            <h3 class="font-bold text-2xl text-white mb-4 tracking-wide">{{ $candidate->nama }}</h3>
                            
                            <div class="space-y-3 text-xs text-slate-300 border-t border-slate-700/60 pt-4">
                                <div>
                                    <strong class="text-emerald-400 uppercase tracking-wider text-[10px] block mb-1">Visi:</strong>
                                    <p class="italic text-slate-200 bg-slate-900/50 p-2.5 rounded-lg border border-slate-700/50">"{{ $candidate->visi }}"</p>
                                </div>
                                <div>
                                    <strong class="text-emerald-400 uppercase tracking-wider text-[10px] block mb-1">Misi:</strong>
                                    <pre class="font-sans whitespace-pre-line text-slate-200 bg-slate-900/50 p-2.5 rounded-lg border border-slate-700/50">{{ $candidate->misi }}</pre>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Tombol Aksi Bawah -->
                    <div class="mt-5 pt-3 border-t border-slate-700/60 text-center">
                        <span class="text-xs text-slate-400 group-hover:text-emerald-400 font-bold transition flex items-center justify-center gap-1.5">
                            Pilih Kandidat Ini 
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </span>
                    </div>
                </label>
                @endforeach
            </div>

            <!-- Tombol Submit / Konfirmasi Pilihan -->
            <div class="text-center pb-8">
                <button type="submit" onclick="return confirm('Apakah Anda yakin dengan pilihan suara Anda? Pilihan tidak dapat diubah setelah dikirim.')" class="bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-8 py-3.5 rounded-xl shadow-lg transition duration-200 text-sm tracking-wide">
                    📥 Kirim & Kunci Pilihan Saya
                </button>
            </div>
        </form>
    </div>

    <div class="text-center pt-6 pb-4 text-xs text-slate-500">
        &copy; 2026 Pilketos Interaktif SMA Mazra'atul Ulum Paciran
    </div>
</body>
</html>