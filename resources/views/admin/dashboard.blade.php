<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Pilketos Mazra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen p-6">

    <div class="max-w-7xl mx-auto">
        
        <!-- Header & Navigasi -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center bg-slate-800 border border-slate-700 p-6 rounded-2xl mb-6 shadow-xl gap-4">
            <div>
                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest bg-emerald-950 px-3 py-1 rounded-full border border-emerald-800">
                    Admin Control Panel
                </span>
                <h1 class="text-2xl font-bold text-white mt-2">📊 Live Count & Monitoring Pilketos 2026/2027</h1>
                <p class="text-xs text-slate-400">SMA Mazra'atul Ulum Paciran (Auto Voice Announcer & Real-time Sync)</p>
            </div>
            
            <div class="flex flex-wrap items-center gap-3">
                <a href="{{ route('admin.students.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white text-xs px-4 py-2.5 rounded-xl font-medium transition">
                    📋 Kelola DPT & QR Kartu
                </a>
                <a href="{{ route('admin.candidates.index') }}" class="bg-slate-700 hover:bg-slate-600 text-white text-xs px-4 py-2.5 rounded-xl font-medium transition">
                    🙋‍♂️ Kelola Calon
                </a>

                <!-- Form Ganti Status Voting (Buka/Tutup) -->
                <form action="{{ route('admin.voting.status') }}" method="POST" class="inline">
                    @csrf
                    @if($votingStatus == 'open')
                        <input type="hidden" name="status" value="closed">
                        <button type="submit" class="bg-rose-600 hover:bg-rose-500 text-white text-xs px-4 py-2.5 rounded-xl font-bold transition shadow-lg">
                            🔴 Tutup Voting
                        </button>
                    @else
                        <input type="hidden" name="status" value="open">
                        <button type="submit" class="bg-emerald-600 hover:bg-emerald-500 text-white text-xs px-4 py-2.5 rounded-xl font-bold transition shadow-lg animate-pulse">
                            🟢 Buka Voting Sekarang
                        </button>
                    @endif
                </form>

                <!-- Tombol Reset Data Voting -->
                <form action="{{ route('admin.voting.reset') }}" method="POST" onsubmit="return confirm('PERINGATAN KERAS: Yakin ingin mereset seluruh hasil suara? Aksi ini akan menghapus semua riwayat suara masuk dan mengembalikan status semua siswa menjadi belum memilih.')" class="inline">
                    @csrf
                    <button type="submit" class="bg-amber-700 hover:bg-amber-600 text-white text-xs px-4 py-2.5 rounded-xl font-bold transition">
                        🔄 Reset / Buat Vote Baru
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="bg-emerald-950 border border-emerald-800 text-emerald-200 px-4 py-3 rounded-xl mb-6 text-sm font-medium">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- Statistik Kartu Ringkasan -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-slate-800 border border-slate-700 p-5 rounded-2xl shadow-sm">
                <p class="text-xs text-slate-400 uppercase font-bold">Total DPT Terdaftar</p>
                <h3 class="text-3xl font-extrabold text-white mt-1">{{ $totalDpt }} <span class="text-xs text-slate-400 font-normal">Siswa</span></h3>
            </div>
            <div class="bg-slate-800 border border-slate-700 p-5 rounded-2xl shadow-sm">
                <p class="text-xs text-emerald-400 uppercase font-bold">Sudah Menggunakan Hak Suara</p>
                <h3 class="text-3xl font-extrabold text-emerald-400 mt-1">{{ $totalVoted }} <span class="text-xs text-slate-400 font-normal">Siswa ({{ $totalDpt > 0 ? round(($totalVoted/$totalDpt)*100, 1) : 0 }}%)</span></h3>
            </div>
            <div class="bg-slate-800 border border-slate-700 p-5 rounded-2xl shadow-sm">
                <p class="text-xs text-amber-400 uppercase font-bold">Belum Memilih</p>
                <h3 class="text-3xl font-extrabold text-amber-400 mt-1">{{ $totalUnvoted }} <span class="text-xs text-slate-400 font-normal">Siswa</span></h3>
            </div>
        </div>

        <!-- FITUR UTAMA: Live Voice Announcer & Grafik -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            
            <!-- Kolom Kiri & Tengah: Grafik Perolehan Suara & Live Announcer -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Status Bar Live Announcer Otomatis -->
                <div class="bg-gradient-to-r from-emerald-950 to-slate-800 border border-emerald-700/50 p-4 rounded-2xl shadow-xl flex items-center justify-between">
                    <div>
                        <span class="text-[10px] bg-emerald-500 text-slate-950 font-bold px-2 py-0.5 rounded uppercase tracking-wider">Auto Announcer</span>
                        <h2 class="text-sm font-bold text-white mt-1">🎙️ Sistem Pembaca Suara Masuk Otomatis Aktif</h2>
                        <p class="text-xs text-slate-400">Suara akan dibacakan singkat, dan grafik otomatis naik setelah pembacaan.</p>
                    </div>
                    <div id="announcerStatus" class="text-xs text-emerald-300 font-mono bg-emerald-950/80 px-3 py-1.5 rounded-xl border border-emerald-800">
                        Status: Menunggu suara masuk...
                    </div>
                </div>

                <!-- Grafik Perolehan Suara Calon -->
                <div class="bg-slate-800 border border-slate-700 p-6 rounded-2xl shadow-lg mb-6">
                    <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        🏆 Perolehan Suara Sementara
                    </h2>

                    <div class="space-y-6">
                        @foreach($candidates as $candidate)
                        @php
                            $totalVotes = \App\Models\Vote::where('candidate_id', $candidate->id)->count();
                            $percentage = $totalDpt > 0 ? ($totalVotes / $totalDpt) * 100 : 0;
                        @endphp

                        <!-- Baris Kandidat: Foto di Kiri, Konten di Kanan -->
                        <div class="flex items-center gap-4 bg-slate-900/40 p-4 rounded-xl border border-slate-700/50">
                            
                            <!-- Foto Kecil Calon (~1cm / w-12 h-12) -->
                            <div class="shrink-0">
                                @if($candidate->foto)
                                    <img src="{{ asset('storage/' . $candidate->foto) }}" class="w-12 h-12 rounded-lg object-cover border border-slate-600 shadow-sm">
                                @else
                                    <div class="w-12 h-12 bg-slate-700 rounded-lg flex items-center justify-center text-[10px] text-slate-400 border border-slate-600">
                                        Foto
                                    </div>
                                @endif
                            </div>

                            <!-- Bagian Kanan (Nama, Progress Bar, & Jumlah Suara) -->
                            <div class="flex-1">
                                <!-- Baris Atas: Nama Calon & Total Suara (Rata Kanan) -->
                                <div class="flex justify-between items-center mb-1.5 text-sm">
                                    <span class="font-bold text-white">
                                        Calon {{ $candidate->nomor_urut }} - {{ $candidate->nama }}
                                    </span>
                                    <div class="text-right">
                                        <span class="font-bold text-emerald-400">{{ $totalVotes }} Suara</span>
                                        <span class="text-xs text-slate-400 ml-1">({{ number_format($percentage, 1) }}%)</span>
                                    </div>
                                </div>

                                <!-- Batang Grafik Progress Bar di Samping Kanan Foto -->
                                <div class="w-full bg-slate-900 rounded-full h-3.5 overflow-hidden border border-slate-700/60 p-0.5">
                                    <div class="bg-emerald-500 h-full rounded-full transition-all duration-500 shadow-sm" style="width: {{ $percentage }}%"></div>
                                </div>
                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

            <!-- Kolom Kanan: Daftar Siswa Yang Belum Memilih -->
            <div class="bg-slate-800 border border-slate-700 p-6 rounded-2xl shadow-xl flex flex-col h-[520px]">
                <div class="flex justify-between items-center mb-3">
                    <h2 class="text-base font-bold text-white">⚠️ Belum Memilih (<span id="countUnvoted">{{ $unvotedStudents->count() }}</span>)</h2>
                    <input type="text" id="searchUnvoted" placeholder="Cari nama/kelas..." class="bg-slate-900 border border-slate-700 text-xs px-3 py-1.5 rounded-xl text-white focus:outline-none focus:border-emerald-500 w-36">
                </div>
                
                <div class="overflow-y-auto flex-1 pr-1 space-y-2 border border-slate-700/60 rounded-xl p-2 bg-slate-900/50" id="listUnvoted">
                    @forelse($unvotedStudents as $student)
                    <div class="unvoted-item text-xs bg-slate-800 p-2.5 rounded-xl border border-slate-700/60 flex justify-between items-center">
                        <div>
                            <strong class="text-white block student-name">{{ $student->nama }}</strong>
                            <span class="text-slate-400 text-[10px] student-class">NIS: {{ $student->nis }} | {{ $student->kelas }}</span>
                        </div>
                        <span class="bg-amber-950 text-amber-400 border border-amber-800/60 text-[9px] px-2 py-0.5 rounded-md font-semibold">Belum</span>
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
                
                // Kalimat dibuat singkat: hanya menyebutkan nomor urut dan nama calon
                let shortText = `Nomor ${vote.candidate_number}, ${vote.candidate_name}`;

                document.getElementById('announcerStatus').innerText = `📢 Membacakan: ${shortText}`;

                let utterance = new SpeechSynthesisUtterance(shortText);
                utterance.lang = 'id-ID';
                utterance.rate = 0.95; // Kecepatan natural

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