<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan Kartu Pelajar - Pilketos Mazra</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Library Pemindai QR Code (html5-qrcode) -->
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
</head>
<body class="bg-slate-900 text-slate-100 font-sans min-h-screen flex flex-col justify-between p-4">

    <div class="max-w-md mx-auto w-full pt-8 text-center">
        <span class="text-xs font-bold tracking-widest text-emerald-400 uppercase bg-emerald-950 px-3 py-1 rounded-full border border-emerald-800">
            Pemilihan Ketua OSIS 2026/2027
        </span>
        <h1 class="text-2xl font-bold mt-3 text-white">SMA Mazra'atul Ulum Paciran</h1>
        <p class="text-xs text-slate-400 mt-1">Arahkan kamera HP ke QR Code pada kartu pelajar Anda.</p>
    </div>

    <div class="max-w-md mx-auto w-full my-6 bg-slate-800 p-4 rounded-2xl shadow-xl border border-slate-700">
        @if(session('error'))
            <div class="bg-rose-950 border border-rose-800 text-rose-200 px-4 py-3 rounded-xl mb-4 text-sm font-medium">
                ⚠️ {{ session('error') }}
            </div>
        @endif

        <!-- Area Kamera Scanner -->
        <div id="reader" class="overflow-hidden rounded-xl bg-slate-900 border border-slate-700"></div>

        <!-- Opsi Alternatif Input Manual Token dengan Proteksi Password -->
        <div class="mt-4 pt-4 border-t border-slate-700">
            <p class="text-xs text-slate-400 mb-2">Atau masukkan Token QR secara manual (Memerlukan Password):</p>
            <div class="flex gap-2">
                <input type="text" id="qr_token" placeholder="Contoh: MAZRA-1001" class="flex-1 bg-slate-900 border border-slate-700 text-sm px-3 py-2 rounded-xl text-white focus:outline-none focus:border-emerald-500 uppercase font-mono">
                <button type="button" onclick="submitManualTokenDirect()" class="bg-emerald-600 hover:bg-emerald-500 text-white text-sm px-4 py-2 rounded-xl font-medium transition cursor-pointer">
                    Masuk
                </button>
            </div>
        </div>
    </div>

    <div class="text-center pb-4 text-xs text-slate-500">
        &copy; 2026 Tim IT SMA Mazra'atul Ulum. All rights reserved.
    </div>

    <!-- Script JavaScript Scanner & Proteksi Password -->
    <script>
        function verifyToken(tokenText) {
            // Hentikan scanner jika aktif
            if (typeof html5QrcodeScanner !== 'undefined') {
                try {
                    html5QrcodeScanner.clear();
                } catch(e) {}
            }
            
            // Kirim token via AJAX ke route verify
            fetch("{{ route('voting.verify') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ qr_token: tokenText })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Token tidak valid!');
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan sistem saat memverifikasi QR.');
                location.reload();
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            verifyToken(decodedText);
        }

        function submitManualTokenDirect() {
            let tokenInput = document.getElementById('qr_token').value.trim();
            if (!tokenInput) {
                alert('Silakan masukkan token QR terlebih dahulu!');
                return;
            }

            // Meminta password sebelum memproses input manual
            let password = prompt("Masukkan password khusus untuk akses input manual:");
            
            if (password === null) {
                return; // Jika dibatalkan
            }

            if (password === "00000") {
                verifyToken(tokenInput);
            } else {
                alert("❌ Password salah! Akses input manual ditolak.");
            }
        }

        var html5QrcodeScanner = new Html5QrcodeScanner(
            "reader", { fps: 10, qrbox: 250 }, false);
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</body>
</html>