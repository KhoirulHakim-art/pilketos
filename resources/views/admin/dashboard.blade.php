<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pilketos Mazra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Latar belakang gradient dinamis agar efek kaca (glassmorphism) menonjol */
        body {
            background-color: #090d16;
            background-image: 
                radial-gradient(at 10% 15%, rgba(16, 185, 129, 0.22) 0px, transparent 45%),
                radial-gradient(at 88% 20%, rgba(56, 189, 248, 0.20) 0px, transparent 45%),
                radial-gradient(at 50% 85%, rgba(139, 92, 246, 0.18) 0px, transparent 50%);
            background-attachment: fixed;
        }

        /* Utilitas Kaca (Glass Panel) */
        .glass-panel {
            background: rgba(255, 255, 255, 0.04);
            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
            border: 1px solid rgba(255, 255, 255, 0.12);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.35);
        }

        .glass-card-inner {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* Custom Scrollbar untuk daftar siswa */
        ::-webkit-scrollbar {
            width: 6px;
        }
        ::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.15);
            border-radius: 10px;
        }
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.28);
        }
    </style>
</head>
<body class="text-slate-100 font-sans min-h-screen p-6 relative overflow-x-hidden">

    <!-- Ornamen Cahaya Ambient di Belakang -->
    <div class="fixed top-[-10%] left-[-5%] w-96 h-96 bg-emerald-500/20 rounded-full blur-[120px] pointer-events-none -z-10"></div>
    <div class="fixed bottom-[-10%] right-[-5%] w-96 h-96 bg-cyan-500/20 rounded-full blur-[120px] pointer-events-none -z-10"></div>

    <div class="max-w-7xl mx-auto">
        
        <!-- Header & Navigasi -->
        <div class="glass-panel flex flex-col md:flex-row justify-between items-start md:items-center p-6 rounded-3xl mb-6 gap-4">
            <div>
                <span class="text-[11px] font-bold text-emerald-300 uppercase tracking-widest bg-emerald-500/15 px-3.5 py-1 rounded-full border border-emerald-400/30 backdrop-blur-md">
                    ✨ Admin Control Panel
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-white via-slate-100 to-emerald-200 mt-2.5">
                    📊 Live Count & Monitoring Pilketos 2026/2027
                </h1>
                <p class="text-xs text-slate-300/80 mt-0.5">SMA Mazra'atul Ulum Paciran • Auto Voice Announcer & Real-time Sync</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-2.5">
                <a href="{{ route('admin.students.index') }}" class="bg-white/10 hover:bg-white/20 border border-white/15 backdrop-blur-md text-white text-xs px-4 py-2.5 rounded-xl font-medium transition duration-200">
                    📋 Kelola DPT & QR Kartu
                </a>
                <a href="{{ route('admin.candidates.index') }}" class="bg-white/10 hover:bg-white/20 border border-white/15 backdrop-blur-md text-white text-xs px-4 py-2.5 rounded-xl font-medium transition duration-200">
                    🙋‍♂️ Kelola Calon
                </a>

                <!-- Form Ganti Status Voting (Buka/Tutup) -->
                <form action="{{ route('admin.voting.status') }}" method="POST" class="inline">
                    @csrf
                    @if($votingStatus == 'open')
                        <input type="hidden" name="status" value="closed">
                        <button type="submit" class="bg-rose-500/80 hover:bg-rose-500 border border-rose-400/40 backdrop-blur-md text-white text-xs px-4 py-2.5 rounded-xl font-bold transition shadow-lg shadow-rose-950/50">
                            🔴 Tutup Voting
                        </button>
                    @else
                        <input type="hidden" name="status" value="open">
                        <button type="submit" class="bg-emerald-500/80 hover:bg-emerald-500 border border-emerald-400/40 backdrop-blur-md text-white text-xs px-4 py-2.5 rounded-xl font-bold transition shadow-lg shadow-emerald-950/50 animate-pulse">
                            🟢 Buka Voting Sekarang
                        </button>
                    @endif
                </form>

                <!-- Tombol Reset Data Voting -->
                <form action="{{ route('admin.voting.reset') }}" method="POST" onsubmit="return confirm('PERINGATAN KERAS: Yakin ingin mereset seluruh hasil suara? Aksi ini akan menghapus semua riwayat suara masuk dan mengembalikan status semua siswa menjadi belum memilih.')" class="inline">
                    @csrf
                    <button type="submit" class="bg-amber-500/20 hover:bg-amber-500/35 border border-amber-400/30 backdrop-blur-md text-amber-200 text-xs px-4 py-2.5 rounded-xl font-bold transition">
                        🔄 Reset Vote
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-500/15 border border-emerald-400/30 backdrop-blur-md text-emerald-200 px-5 py-3.5 rounded-2xl mb-6 text-sm font-medium shadow-lg">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Statistik Kartu Ringkasan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-6">
            <div class="glass-panel p-6 rounded-3xl relative overflow-hidden group hover:border-white/25 transition">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-white/5 rounded-full blur-xl group-hover:bg-white/10 transition"></div>
                <p class="text-xs text-slate-300/80 uppercase tracking-wider font-bold">Total DPT Terdaftar</p>
                <h3 class="text-4xl font-black text-white mt-2">{{ $totalDpt }} <span class="text-xs text-slate-400 font-normal">Siswa</span></h3>
            </div>

            <div class="glass-panel p-6 rounded-3xl relative overflow-hidden group hover:border-emerald-400/30 transition">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-emerald-500/15 rounded-full blur-xl group-hover:bg-emerald-500/25 transition"></div>
                <p class="text-xs text-emerald-300 uppercase tracking-wider font-bold">Sudah Menggunakan Hak Suara</p>
                <h3 class="text-4xl font-black text-emerald-400 mt-2">
                    {{ $totalVoted }} 
                    <span class="text-xs text-emerald-200/70 font-medium bg-emerald-500/15 px-2.5 py-0.5 rounded-full border border-emerald-400/20 ml-1">
                        {{ $totalDpt > 0 ? round(($totalVoted/$totalDpt)*100, 1) : 0 }}%
                    </span>
                </h3>
            </div>

            <div class="glass-panel p-6 rounded-3xl relative overflow-hidden group hover:border-amber-400/30 transition">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-amber-500/15 rounded-full blur-xl group-hover:bg-amber-500/25 transition"></div>
                <p class="text-xs text-amber-300 uppercase tracking-wider font-bold">Belum Memilih</p>
                <h3 class="text-4xl font-black text-amber-400 mt-2">{{ $totalUnvoted }} <span class="text-xs text-slate-400 font-normal">Siswa</span></h3>
            </div>
        </div>

        <!-- FITUR UTAMA: Live Voice Announcer & Grafik -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            
            <!-- Kolom Kiri & Tengah: Grafik Perolehan Suara & Live Announcer -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Status Bar Live Announcer Otomatis -->
                <div class="glass-panel bg-gradient-to-r from-emerald-500/15 via-teal-500/10 to-transparent border-emerald-400/30 p-5 rounded-3xl flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <span class="text-[10px] bg-emerald-400/20 text-emerald-300 border border-emerald-400/30 font-bold px-2.5 py-0.5 rounded-full uppercase tracking-wider">
                            Auto Announcer
                        </span>
                        <h2 class="text-sm font-bold text-white mt-1.5">🎙️ Sistem Pembaca Suara Masuk Otomatis Aktif</h2>
                        <p class="text-xs text-slate-300/75 mt-0.5">Menyebutkan nama calon secara langsung dan memperbarui grafik otomatis.</p>
                    </div>
                    <div id="announcerStatus" class="text-xs text-emerald-200 font-mono bg-black/30 backdrop-blur-md px-4 py-2 rounded-2xl border border-white/10 shrink-0">
                        Status: Menunggu suara masuk...
                    </div>
                </div>

                <!-- Grafik Perolehan Suara Calon -->
                <div class="glass-panel p-6 rounded-3xl">
                    <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        🏆 Perolehan Suara Sementara
                    </h2>

                    <div class="space-y-4">
                        @foreach($candidates as $candidate)
                        @php
                            $totalVotes = \App\Models\Vote::where('candidate_id', $candidate->id)->count();
                            $percentage = $totalDpt > 0 ? ($totalVotes / $totalDpt) * 100 : 0;
                        @endphp

                        <!-- Baris Kandidat: Glass Card Inner -->
                        <div class="glass-card-inner flex items-center gap-4 p-4 rounded-2xl hover:bg-white/[0.06] transition duration-200">
                            
                            <!-- Foto Kecil Calon -->
                            <div class="shrink-0">
                                @if($candidate->foto)
                                    <img src="{{ asset('storage/' . $candidate->foto) }}" class="w-14 h-14 rounded-2xl object-cover border border-white/20 shadow-md">
                                @else
                                    <div class="w-14 h-14 bg-white/10 rounded-2xl flex items-center justify-center text-[10px] text-slate-300 border border-white/15">
                                        Foto
                                    </div>
                                @endif
                            </div>

                            <!-- Bagian Kanan (Nama, Progress Bar, & Jumlah Suara) -->
                            <div class="flex-1">
                                <div class="flex justify-between items-center mb-2 text-sm">
                                    <div class="flex items-center gap-2">
                                        <span class="bg-white/10 text-emerald-300 border border-white/15 text-xs font-extrabold px-2.5 py-0.5 rounded-lg">
                                            #{{ $candidate->nomor_urut }}
                                        </span>
                                        <span class="font-bold text-white text-base">
                                            {{ $candidate->nama }}
                                        </span>
                                    </div>
                                    <div class="text-right">
                                        <span class="font-extrabold text-emerald-300">{{ $totalVotes }} Suara</span>
                                        <span class="text-xs text-slate-300/75 ml-1">({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                </div>

                                <!-- Batang Grafik Progress Bar Kaca -->
                                <div class="w-full bg-black/30 rounded-full h-3.5 overflow-hidden border border-white/10 p-0.5">
                                    <div class="bg-gradient-to-r from-emerald-400 via-teal-400 to-cyan-400 h-full rounded-full transition-all duration-700 shadow-[0_0_12px_rgba(52,211,153,0.5)]" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Daftar Siswa Yang Belum Memilih -->
            <div class="glass-panel p-6 rounded-3xl flex flex-col h-[540px]">
                <div class="flex justify-between items-center mb-4 gap-2">
                    <h2 class="text-base font-bold text-white">⚠️ Belum Memilih (<span id="countUnvoted">{{ $unvotedStudents->count() }}</span>)</h2>
                    <input type="text" id="searchUnvoted" placeholder="Cari nama/kelas..." class="bg-black/25 border border-white/15 backdrop-blur-md text-xs px-3.5 py-2 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:border-emerald-400/60 w-36 transition">
                </div>
                
                <div class="overflow-y-auto flex-1 pr-1.5 space-y-2.5 rounded-2xl p-2 bg-black/20 border border-white/5" id="listUnvoted">
                    @forelse($unvotedStudents as $student)
                    <div class="unvoted-item glass-card-inner text-xs p-3 rounded-2xl flex justify-between items-center hover:bg-white/[0.07] transition">
                        <div>
                            <strong class="text-white block student-name font-semibold">{{ $student->nama }}</strong>
                            <span class="text-slate-300/70 text-[10px] student-class">NIS: {{ $student->nis }} • {{ $student->kelas }}</span>
                        </div>
                        <span class="bg-amber-500/15 text-amber-300 border border-amber-400/30 text-[10px] px-2.5 py-0.5 rounded-full font-semibold">Belum</span>
                    </div>
                    @empty
                    <div class="text-center py-14 text-slate-300/70 text-xs">
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
                    document.getElementById('announcerStatus').innerText = "Status: Selesai, memperbarui grafik...";
                    setTimeout(() => {
                        location.reload();
                    }, 1000);
                    return;
                }

                let vote = votes[index];
                
                // Hanya menyebutkan nama calon tanpa nomor urut
                let shortText = vote.candidate_name;

                document.getElementById('announcerStatus').innerText = `📢 Membacakan: ${shortText}`;

                let utterance = new SpeechSynthesisUtterance(shortText);
                utterance.lang = 'id-ID';
                utterance.rate = 0.95;

                utterance.onend = function() {
                    fetch(`{{ url('/admin/api/mark-announced') }}/${vote.id}`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json'
                        }
                    }).then(() => {
                        index++;
                        setTimeout(readNext, 800);
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