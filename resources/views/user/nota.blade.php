<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota Pesanan #{{ $pesanan->id_pesanan }} - Ni Laundry</title>
    
    {{-- Favicon sesuai main.blade.php --}}
    <link rel="icon" href="{{ asset('img/logo.webp') }}" type="image/webp">
    
    {{-- Panggil Tailwind CSS via Vite --}}
    @vite(['resources/css/app.css'])

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            /* Paksa cetak warna background dan border */
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
            /* Margin aman agar tidak terpotong */
            @page { 
                margin: 1cm; 
            }
            body { 
                margin: 0; 
                padding: 0;
            }
        }
    </style>
</head>
<body class="bg-slate-100 text-slate-800 font-sans antialiased selection:bg-brand-500 selection:text-white">

    {{-- Container Nota --}}
    <div class="max-w-2xl mx-auto my-10 bg-white p-8 md:p-12 shadow-lg rounded-2xl border border-slate-200 print:shadow-none print:border-none print:max-w-full print:my-0">
        
        {{-- Header Nota --}}
        <div class="flex justify-between items-start border-b-2 border-dashed border-slate-200 pb-6 mb-6">
            <div class="flex gap-4 items-start">
                {{-- Logo dari asset --}}
                <img src="{{ asset('img/logo.webp') }}" alt="Logo Ni Laundry" class="w-12 h-12 object-contain">
                <div>
                    <h1 class="text-3xl font-black text-slate-900 tracking-tight">Ni Laundry<span class="text-fresh-500">.</span></h1>
                    <p class="text-slate-500 text-sm mt-1 max-w-xs leading-relaxed">Jl. Pangeran Diponegoro, Nogotirto, Sleman Regency, Special Region of Yogyakarta</p>
                    <p class="text-slate-500 text-sm font-medium mt-1">Telp: 0821-4755-6964</p>
                </div>
            </div>
            <div class="text-right">
                <h2 class="text-xl font-bold text-slate-800">INVOICE</h2>
                <p class="text-slate-500 font-mono text-sm mt-1">#{{ str_pad($pesanan->id_pesanan, 5, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        {{-- Info Pelanggan & Transaksi --}}
        <div class="grid grid-cols-2 gap-6 mb-8 text-sm bg-slate-50 p-4 rounded-xl border border-slate-100">
            <div>
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Tanggal Pesanan</p>
                <p class="font-medium text-slate-700">
                    <i class="ph-fill ph-calendar-blank text-brand-500 mr-1"></i> 
                    {{ \Carbon\Carbon::parse($pesanan->tanggal_pesan)->format('d F Y') }}
                </p>
            </div>
            <div class="text-right">
                <p class="text-slate-400 font-bold uppercase tracking-wider text-[10px] mb-1">Status & Pembayaran</p>
                <p class="font-bold text-brand-600">{{ $pesanan->status_pesanan }}</p>
                <p class="text-slate-500 font-medium">{{ $pesanan->metode }}</p>
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
                        <td class="px-4 py-4 font-medium text-slate-700">{{ $pesanan->layanan->nama_layanan }}</td>
                        <td class="px-4 py-4 text-center text-slate-600">{{ $pesanan->berat }} {{ $pesanan->layanan->jenis == 'Kiloan' ? 'Kg' : 'Pcs' }}</td>
                        <td class="px-4 py-4 text-right font-bold text-slate-700">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Ringkasan Harga --}}
        @php
            $sudah_bayar = $pesanan->jumlah_bayar ?? 0;
            $sisa = $pesanan->total_harga - $sudah_bayar;
        @endphp
        <div class="flex justify-end mb-10 text-sm break-inside-avoid">
            <div class="w-full max-w-sm space-y-3">
                <div class="flex justify-between">
                    <span class="text-slate-500">Total Tagihan</span>
                    <span class="font-medium text-slate-700">Rp {{ number_format($pesanan->total_harga, 0, ',', '.') }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Sudah Dibayar</span>
                    <span class="font-medium text-slate-700">Rp {{ number_format($sudah_bayar, 0, ',', '.') }}</span>
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
        <div class="text-center text-slate-400 text-xs mt-12 pt-8 border-t border-slate-200 break-inside-avoid">
            <p>Terima kasih telah mempercayakan cucian Anda kepada Ni Laundry.</p>
            <p class="mt-1">Nota ini sah dan dicetak otomatis oleh sistem.</p>
        </div>

        {{-- Tombol Aksi (Hilang saat diprint) --}}
        <div class="mt-8 flex justify-center gap-4 no-print">
            <button onclick="window.print()" class="px-6 py-2.5 bg-brand-600 text-white font-bold rounded-xl shadow-lg shadow-brand-500/20 hover:bg-brand-700 active:scale-95 transition-all flex items-center gap-2">
                <i class="ph-bold ph-printer text-lg"></i> Cetak / Simpan PDF
            </button>
            <button onclick="window.close()" class="px-6 py-2.5 bg-slate-100 text-slate-600 font-bold rounded-xl hover:bg-slate-200 active:scale-95 transition-all flex items-center gap-2">
                <i class="ph-bold ph-x text-lg"></i> Tutup
            </button>
        </div>

    </div>

    {{-- Script Phosphor Icons agar icon muncul --}}
    <script defer src="https://unpkg.com/@phosphor-icons/web"></script>
    
    {{-- Script auto-print (Opsional, hapus/komen jika tidak ingin otomatis muncul dialog print) --}}
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>