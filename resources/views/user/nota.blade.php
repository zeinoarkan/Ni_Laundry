<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pesanan #{{ $pesanan->id_pesanan }}</title>
    
    {{-- Panggil Tailwind CSS Anda --}}
    @vite('resources/css/app.css')
    {{-- Atau gunakan CDN jika belum setup Vite: <script src="https://cdn.tailwindcss.com"></script> --}}

    <style>
        /* CSS khusus saat dicetak/disimpan ke PDF */
        @media print {
            /* Sembunyikan elemen yang tidak perlu dicetak */
            .no-print {
                display: none !important;
            }
            /* Hilangkan margin default browser saat mencetak */
            @page { margin: 0; }
            body { margin: 1.6cm; }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased">

    {{-- Container Nota --}}
    <div class="max-w-2xl mx-auto my-10 bg-white p-8 md:p-12 shadow-lg rounded-2xl border border-slate-200 print:shadow-none print:border-none print:m-0 print:p-0 print:max-w-full">
        
        {{-- Header Nota --}}
        <div class="flex justify-between items-start border-b-2 border-dashed border-slate-200 pb-6 mb-6">
            <div>
                <h1 class="text-3xl font-black text-brand-600 tracking-tight">Ni Laundry</h1>
                <p class="text-slate-500 text-sm mt-1">Jl. Pangeran Diponegoro, Nogotirto, Sleman Regency, Special Region of Yogyakarta</p>
                <p class="text-slate-500 text-sm">Telp: 0821-4755-6964</p>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-slate-800">INVOICE</h2>
                <p class="text-slate-500 font-mono text-sm mt-1">#{{ str_pad($pesanan->id_pesanan, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        {{-- Info Pelanggan & Transaksi --}}
        <div class="grid grid-cols-2 gap-6 mb-8 text-sm">
            <div>
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Tanggal Pesanan</p>
                <p class="font-medium">{{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d F Y') }}</p>
            </div>
            <div class="text-right">
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Status & Pembayaran</p>
                <p class="font-medium text-brand-600">{{ $pesanan->status_pesanan }}</p>
                <p class="text-slate-500">{{ $pesanan->metode }}</p>
            </div>
        </div>

        {{-- Tabel Layanan --}}
        <div class="mb-8 overflow-hidden rounded-xl border border-slate-200">
            <table class="w-full text-left text-sm">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-4 py-3 font-bold text-slate-600">Layanan</th>
                        <th class="px-4 py-3 font-bold text-slate-600 text-center">Jumlah / Berat</th>
                        <th class="px-4 py-3 font-bold text-slate-600 text-right">Total Harga</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    <tr>
                        <td class="px-4 py-4 font-medium">{{ $pesanan->layanan->nama_layanan }}</td>
                        <td class="px-4 py-4 text-center">{{ $pesanan->berat }} {{ $pesanan->layanan->jenis == 'Kiloan' ? 'Kg' : 'Pcs' }}</td>
                        <td class="px-4 py-4 text-right font-medium">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Ringkasan Harga --}}
        @php
            $sudah_bayar = $pesanan->jumlah_bayar ?? 0;
            $sisa = $pesanan->total_harga - $sudah_bayar;
        @endphp
        <div class="flex justify-end mb-10 text-sm">
            <div class="w-full max-w-sm space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-500">Total Tagihan</span>
                    <span class="font-medium">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Sudah Dibayar</span>
                    <span class="font-medium">Rp {{ number_format($sudah_bayar, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between items-center pt-3 border-t border-slate-200">
                    <span class="font-bold text-slate-800">Sisa Tagihan</span>
                    <span class="font-bold text-xl {{ $sisa > 0 ? 'text-red-500' : 'text-emerald-500' }}">
                        Rp {{ number_format($sisa > 0 ? $sisa : 0, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="text-center text-slate-400 text-xs mt-12 pt-8 border-t border-slate-200">
            <p>Terima kasih telah mempercayakan cucian Anda kepada kami.</p>
        </div>

        {{-- Tombol Aksi (Hanya muncul di layar, hilang saat diprint) --}}
        <div class="mt-8 flex justify-center gap-4 no-print">
            <button onclick="window.print()" class="px-6 py-2.5 bg-brand-600 text-white font-bold rounded-xl shadow-lg hover:bg-brand-700 active:scale-95 transition-all">
                Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 active:scale-95 transition-all">
                Tutup Jendela
            </button>
        </div>

    </div>

    {{-- Script untuk auto-print saat halaman dibuka --}}
    <script>
        window.onload = function() {
            // Hilangkan komentar pada baris di bawah ini jika ingin dialog print otomatis muncul saat halaman dimuat
            window.print();
        }
    </script>
</body>
</html>