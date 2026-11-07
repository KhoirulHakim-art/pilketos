<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Terima Kasih - Pilketos Mazra</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full bg-slate-800 border border-slate-700 p-8 rounded-3xl text-center shadow-2xl">
        <div class="w-16 h-16 bg-emerald-950 text-emerald-400 border border-emerald-800 rounded-2xl flex items-center justify-center text-3xl mx-auto mb-4">
            🎉
        </div>
        <h1 class="text-2xl font-bold text-white mb-2">Suara Berhasil Direkam!</h1>
        <p class="text-sm text-slate-300 mb-6">
            Terima kasih <strong class="text-emerald-400">{{ session('voted_name') ?? 'Siswa' }}</strong> telah menggunakan hak suara Anda dengan bijak dalam Pemilihan Ketua OSIS SMA Mazra'atul Ulum Periode 2026/2027.
        </p>
        <a href="{{ route('voting.scanner') }}" class="inline-block bg-emerald-600 hover:bg-emerald-500 text-white font-medium px-6 py-3 rounded-xl text-sm transition">
            Kembali ke Beranda / Sesi Pemilih Berikutnya
        </a>
    </div>
</body>
</html>