<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pilketos Mazra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Efek Glassmorphism Kustom */
        .glass-panel {
            background: rgba(15, 23, 42, 0.65);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }
        .glass-card {
            background: rgba(30, 41, 59, 0.5);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }
        /* Custom Scrollbar */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(15, 23, 42, 0.4);
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 9999px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-slate-950 text-slate-100 font-sans min-h-screen p-6 relative overflow-x-hidden">

    <!-- Background Glow Ornaments -->
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-10 right-1/4 w-96 h-96 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto relative z-10">
        
        <!-- Header & Navigasi -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center glass-panel p-6 rounded-3xl mb-6 gap-4">
            <div>
                <span class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest bg-emerald-950/80 px-3 py-1 rounded-full border border-emerald-800/60 shadow-inner">
                    Admin Control Panel
                </span>
                <h1 class="text-2xl font-extrabold text-white mt-2 tracking-tight">📊 Live Count & Monitoring Pilketos 2026/2027</h1>
                <p class="text-xs text-slate-400">SMA Mazra'atul Ulum Paciran (Glassmorphism Aesthetic Edition)</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.students.index') }}" class="bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 text-xs px-4 py-2.5 rounded-xl font-medium border border-slate-700/60 transition shadow-sm">
                    📋 Kelola DPT & QR Kartu
                </a>
                <a href="{{ route('admin.candidates.index') }}" class="bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 text-xs px-4 py-2.5 rounded-xl font-medium border border-slate-700/60 transition shadow-sm">
                    🙋‍♂️ Kelola Calon
                </a>

                <!-- Form Ganti Status Voting (Buka/Tutup) -->
                <form action="{{ route('admin.voting.status') }}" method="POST" class="inline">
                    @csrf
                    @if($votingStatus == 'open')
                        <input type="hidden" name="status" value="closed">
                        <button type="submit" class="bg-rose-500/20 hover:bg-rose-500/30 text-rose-300 border border-rose-500/40 text-xs px-4 py-2.5 rounded-xl font-bold transition shadow-lg">
                            🔴 Tutup Voting
                        </button>
                    @else
                        <input type="hidden" name="status" value="open">
                        <button type="submit" class="bg-emerald-500/20 hover:bg-emerald-500/30 text-emerald-300 border border-emerald-500/40 text-xs px-4 py-2.5 rounded-xl font-bold transition shadow-lg animate-pulse">
                            🟢 Buka Voting Sekarang
                        </button>
                    @endif
                </form>

                <!-- Tombol Reset Data Voting -->
                <form action="{{ route('admin.voting.reset') }}" method="POST" onsubmit="return confirm('PERINGATAN KERAS: Yakin ingin mereset seluruh hasil suara? Aksi ini akan menghapus semua riwayat suara masuk dan mengembalikan status semua siswa menjadi belum memilih.')" class="inline">
                    @csrf
                    <button type="submit" class="bg-amber-500/15 hover:bg-amber-500/25 text-amber-300 border border-amber-500/30 text-xs px-4 py-2.5 rounded-xl font-bold transition">
                        🔄 Reset / Buat Vote Baru
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="glass-panel border-emerald-800/50 text-emerald-200 px-4 py-3 rounded-2xl mb-6 text-sm font-medium flex items-center gap-2">
                <span>✅</span> {{ session('success') }}
            </div>
        @endif

        <!-- Statistik Kartu Ringkasan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="glass-card p-5 rounded-2xl shadow-sm relative overflow-hidden group hover:border-slate-600/50 transition">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-slate-700/10 rounded-full blur-xl group-hover:bg-slate-500/20 transition"></div>
                <p class="text-[11px] text-slate-400 uppercase font-bold tracking-wider">Total DPT Terdaftar</p>
                <h3 class="text-3xl font-black text-white mt-1.5">{{ $totalDpt }} <span class="text-xs text-slate-400 font-normal">Siswa</span></h3>
            </div>
            <div class="glass-card p-5 rounded-2xl shadow-sm relative overflow-hidden group hover:border-emerald-500/40 transition">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-emerald-500/10 rounded-full blur-xl group-hover:bg-emerald-500/20 transition"></div>
                <p class="text-[11px] text-emerald-400 uppercase font-bold tracking-wider">Sudah Menggunakan Hak Suara</p>
                <h3 class="text-3xl font-black text-emerald-300 mt-1.5">{{ $totalVoted }} <span class="text-xs text-slate-400 font-normal">Siswa ({{ $totalDpt > 0 ? round(($totalVoted/$totalDpt)*100, 1) : 0 }}%)</span></h3>
            </div>
            <div class="glass-card p-5 rounded-2xl shadow-sm relative overflow-hidden group hover:border-amber-500/40 transition">
                <div class="absolute -right-4 -bottom-4 w-20 h-20 bg-amber-500/10 rounded-full blur-xl group-hover:bg-amber-500/20 transition"></div>
                <p class="text-[11px] text-amber-400 uppercase font-bold tracking-wider">Belum Memilih</p>
                <h3 class="text-3xl font-black text-amber-300 mt-1.5">{{ $totalUnvoted }} <span class="text-xs text-slate-400 font-normal">Siswa</span></h3>
            </div>
        </div>

        <!-- FITUR UTAMA: Live Voice Announcer & Grafik -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            
            <!-- Kolom Kiri & Tengah: Grafik Perolehan Suara & Live Announcer -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Status Bar Live Announcer Otomatis -->
                <div class="bg-gradient-to-r from-emerald-950/80 via-slate-900/80 to-slate-900/80 backdrop-blur-xl border border-emerald-500/30 p-4 rounded-2xl shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] bg-emerald-500 text-slate-950 font-extrabold px-2 py-0.5 rounded uppercase tracking-wider shadow">Auto Announcer</span>
                        <h2 class="text-sm font-bold text-white mt-1">🎙️ Sistem Pembaca Suara Masuk Otomatis Aktif</h2>
                        <p class="text-xs text-slate-400">Suara nama calon dibacakan otomatis, grafik diperbarui secara real-time.</p>
                    </div>
                    <div id="announcerStatus" class="text-xs text-emerald-300 font-mono bg-emerald-950/90 px-3 py-1.5 rounded-xl border border-emerald-800/80 shadow-inner">
                        Status: Menunggu suara masuk...
                    </div>
                </div>

                <!-- Grafik Perolehan Suara Calon -->
                <div class="glass-panel p-6 rounded-3xl shadow-lg">
                    <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        🏆 Perolehan Suara Sementara
                    </h2>

                    <div class="space-y-4">
                        @foreach($candidates as $candidate)
                        @php
                            $totalVotes = \App\Models\Vote::where('candidate_id', $candidate->id)->count();
                            $percentage = $totalDpt > 0 ? ($totalVotes / $totalDpt) * 100 : 0;
                        @endphp

                        <!-- Baris Kandidat -->
                        <div class="flex items-center gap-4 bg-slate-900/60 p-4 rounded-2xl border border-slate-700/40 hover:border-slate-600/60 transition">
                            
                            <!-- Foto Kecil Calon -->
                            <div class="shrink-0">
                                @if($candidate->foto)
                                    <img src="{{ asset('storage/' . $candidate->foto) }}" class="w-12 h-12 rounded-xl object-cover border border-slate-700 shadow-md">
                                @else
                                    <div class="w-12 h-12 bg-slate-800 rounded-xl flex items-center justify-center text-[10px] text-slate-400 border border-slate-700">
                                        Foto
                                    </div>
                                @endif
                            </div>

                            <!-- Bagian Kanan (Nama, Progress Bar, & Jumlah Suara) -->
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-1.5 text-sm">
                                    <span class="font-bold text-white tracking-wide">
                                        Calon {{ $candidate->nomor_urut }} - {{ $candidate->nama }}
                                    </span>
                                    <div class="text-right">
                                        <span class="font-extrabold text-emerald-400">{{ $totalVotes }} Suara</span>
                                        <span class="text-xs text-slate-400 ml-1 font-mono">({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                </div>

                                <!-- Batang Grafik Progress Bar -->
                                <div class="w-full bg-slate-950/80 rounded-full h-3 overflow-hidden border border-slate-800 p-0.5 shadow-inner">
                                    <div class="bg-gradient-to-r from-emerald-600 to-emerald-400 h-full rounded-full transition-all duration-700 shadow-sm shadow-emerald-500/50" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Daftar Siswa Yang Belum Memilih -->
            <div class="glass-panel p-6 rounded-3xl shadow-xl flex flex-col h-[520px]">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-base font-bold text-white">⚠️ Belum Memilih (<span id="countUnvoted">{{ $unvotedStudents->count() }}</span>)</h2>
                    <input type="text" id="searchUnvoted" placeholder="Cari nama/kelas..." class="bg-slate-900/80 border border-slate-700/60 text-xs px-3 py-1.5 rounded-xl text-white placeholder-slate-500 focus:outline-none focus:border-emerald-500 w-36 shadow-inner">
                </div>
                
                <div class="overflow-y-auto flex-1 pr-1 space-y-2 border border-slate-700/40 rounded-2xl p-2 bg-slate-950/40 shadow-inner" id="listUnvoted">
                    @forelse($unvotedStudents as $student)
                    <div class="unvoted-item text-xs bg-slate-900/60 p-3 rounded-xl border border-slate-800/80 flex justify-between items-center hover:bg-slate-800/50 transition">
                        <div>
                            <strong class="text-white block student-name font-semibold">{{ $student->nama }}</strong>
                            <span class="text-slate-400 text-[10px] student-class font-mono">NIS: {{ $student->nis }} | {{ $student->kelas }}</span>
                        </div>
                        <span class="bg-amber-950/60 text-amber-400 border border-amber-800/50 text-[9px] px-2 py-0.5 rounded-md font-semibold tracking-wide">Belum</span>
                    </div>
                    @empty
                    <div class="text-center py-12 text-slate-500 text-xs">
                        🎉 Luar biasa! Semua DPT telah menggunakan hak suaranya.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

    <!-- JavaScript Auto-Polling & Voice Announcer -->
    <script>
        let speechSynth = window.speechSynthesis;
        let isChecking = false;

        // Cek suara baru setiap 4 detik secara otomatis di background
        setInterval(pollNewVotes, 4000);

        function pollNewVotes() {
            if (isChecking) return;
            isChecking = true;

            fetch('{{ url("/admin/api/unannounced-votes") }}')
                .then(response => response.json())
                .then(votes => {
                    if (votes && votes.length > 0) {
                        playShortAnnouncer(votes);
                    } else {
                        isChecking = false;
                    }
                })
                .catch(err => {
                    console.error('Error polling votes:', err);
                    isChecking = false;
                });
        }

        function playShortAnnouncer(votes) {
            if (!speechSynth) {
                isChecking = false;
                return;
            }

            let index = 0;

            function readNext() {
                if (index >= votes.length) {
                    // Setelah semua antrean suara selesai dibacakan, refresh halaman agar grafik & statistik naik
                    document.getElementById('announcerStatus').innerText = "Status: Selesai, memperbarui grafik...";
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    return;
                }

                let vote = votes[index];
                
                // HANYA MENYEBUTKAN NAMA CALON SAJA TANPA NOMOR URUT
                let shortText = vote.candidate_name;

                document.getElementById('announcerStatus').innerText = `📢 Membacakan: ${shortText}`;

                let utterance = new SpeechSynthesisUtterance(shortText);
                utterance.lang = 'id-ID';
                utterance.rate = 1.25; // Kecepatan natural

                utterance.onend = function() {
                    // Tandai di database bahwa suara ini sudah dibacakan
                    fetch(`{{ url('/admin/api/mark-announced') }}/${vote.id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(() => {
                        index++;
                        setTimeout(readNext, 800); // Jeda singkat antar suara
                    });
                };

                speechSynth.speak(utterance);
            }

            readNext();
        }

        // Fitur Filter Pencarian Siswa Belum Memilih
        document.getElementById('searchUnvoted').addEventListener('input', function(e) {
            let keyword = e.target.value.toLowerCase();
            let items = document.querySelectorAll('.unvoted-item');
            
            items.forEach(item => {
                let name = item.querySelector('.student-name').innerText.toLowerCase();
                let info = item.querySelector('.student-class').innerText.toLowerCase();
                if (name.includes(keyword) || info.includes(keyword)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>