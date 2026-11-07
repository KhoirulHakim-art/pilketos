<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Calon - Pilketos Mazra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 font-sans p-6">

    <div class="max-w-6xl mx-auto">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold text-emerald-800">👤 Manajemen Calon Ketua OSIS</h1>
            <a href="{{ route('admin.dashboard') }}" class="text-sm bg-slate-200 hover:bg-slate-300 text-slate-800 font-medium px-4 py-2 rounded-lg transition">
                ← Kembali ke Dashboard Admin
            </a>
        </div>

        @if(session('success'))
            <div class="bg-emerald-100 border border-emerald-400 text-emerald-700 px-4 py-3 rounded mb-6 text-sm">
                ✅ {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6 text-xs">
                <strong>Gagal Menyimpan! Periksa kembali isian Anda:</strong>
                <ul class="list-disc pl-4 mt-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Form Input Calon Manual -->
            <div class="bg-white p-6 rounded-xl shadow-sm border h-fit">
                <h2 class="text-lg font-bold mb-4 text-gray-700">Tambah Calon Baru</h2>
                
                <form action="{{ route('admin.candidates.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold mb-1">Nomor Urut</label>
                        <input type="number" name="nomor_urut" value="{{ old('nomor_urut') }}" class="w-full border p-2 text-sm rounded-lg" placeholder="Contoh: 1" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1">Nama Calon</label>
                        <input type="text" name="nama" value="{{ old('nama') }}" class="w-full border p-2 text-sm rounded-lg" placeholder="Nama Lengkap Calon" required>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1">Visi</label>
                        <textarea name="visi" rows="3" class="w-full border p-2 text-sm rounded-lg" placeholder="Tuliskan Visi" required>{{ old('visi') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1">Misi</label>
                        <textarea name="misi" rows="4" class="w-full border p-2 text-sm rounded-lg" placeholder="Tuliskan Misi (Gunakan enter untuk poin baru)" required>{{ old('misi') }}</textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold mb-1">Foto Calon (Opsional)</label>
                        <input type="file" name="foto" accept="image/*" class="w-full text-xs border p-2 rounded-lg bg-gray-50">
                    </div>

                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold py-2.5 rounded-lg text-sm shadow transition">
                        ➕ Simpan Calon
                    </button>
                </form>
            </div>

            <!-- Daftar Calon Terdaftar -->
            <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border">
                <h2 class="text-lg font-bold mb-4 text-gray-700">Daftar Calon Ketua OSIS Terdaftar</h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($candidates as $candidate)
                    <div class="border rounded-xl p-4 flex flex-col justify-between bg-slate-50 relative shadow-sm">
                        <form action="{{ route('admin.candidates.destroy', $candidate->id) }}" method="POST" onsubmit="return confirm('Hapus calon ini?')" class="absolute top-3 right-3">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 bg-red-100 hover:bg-red-200 text-xs px-2 py-1 rounded font-medium">Hapus</button>
                        </form>

                        <div>
                            <span class="bg-emerald-600 text-white text-xs font-bold px-2.5 py-1 rounded-full">#{{ $candidate->nomor_urut }}</span>
                            
                            @if($candidate->foto)
                                <img src="{{ asset('storage/' . $candidate->foto) }}" class="w-24 h-24 object-cover rounded-lg mx-auto my-3 border">
                            @else
                                <div class="w-24 h-24 bg-gray-200 rounded-lg mx-auto my-3 flex items-center justify-center text-xs text-gray-500 font-medium">Tanpa Foto</div>
                            @endif

                            <h3 class="font-bold text-base text-center mb-2">{{ $candidate->nama }}</h3>
                            
                            <div class="text-xs space-y-2 text-gray-600 border-t pt-2">
                                <p><strong>Visi:</strong> {{ $candidate->visi }}</p>
                                <p><strong>Misi:</strong> <span class="whitespace-pre-line">{{ $candidate->misi }}</span></p>
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 text-center text-gray-400 py-12 text-sm">
                        Belum ada calon yang diinputkan. Silakan isi form di samping.
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</body>
</html>