@extends('layouts.main')
@section('title', 'Kelola Pesanan')

@section('content')
<div class="space-y-8">

    {{-- HEADER & LEGEND STATUS --}}
    <div class="flex flex-col xl:flex-row justify-between items-end gap-6 bg-white/60 backdrop-blur-md p-8 rounded-[2.5rem] border border-white/60 shadow-sm">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Manajemen Pesanan</h1>
            <p class="text-slate-500 font-medium">Kelola semua transaksi laundry yang masuk.</p>
        </div>
        
        <div class="flex flex-wrap gap-2 justify-end">
            {{-- 1. Pending --}}
            <div class="px-3 py-1.5 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-slate-400"></span>
                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                    Menunggu Ditimbang ({{ $pesanan->where('status_pesanan', 'Pending')->count() }})
                </span>
            </div>

            {{-- 2. Menunggu Bayar --}}
            <div class="px-3 py-1.5 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                    Belum Bayar ({{ $pesanan->where('status_pesanan', 'Menunggu Pembayaran')->count() }})
                </span>
            </div>

            {{-- 3. Diproses --}}
            <div class="px-3 py-1.5 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                    Diproses ({{ $pesanan->where('status_pesanan', 'Diproses')->count() }})
                </span>
            </div>

            {{-- 4. Selesai --}}
            <div class="px-3 py-1.5 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                    Selesai ({{ $pesanan->where('status_pesanan', 'Selesai')->count() }})
                </span>
            </div>

             {{-- 5. Dibatalkan --}}
             <div class="px-3 py-1.5 rounded-xl bg-white border border-slate-100 shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-red-500"></span>
                <span class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">
                    Batal ({{ $pesanan->where('status_pesanan', 'Dibatalkan')->count() }})
                </span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden relative">
        
        <div class="p-6 border-b border-slate-50 flex flex-col md:flex-row justify-between items-center gap-4 bg-slate-50/30">
            <form action="{{ url()->current() }}" method="GET" class="relative w-full md:max-w-sm">
                <input type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari ID, Nama, atau Status..." 
                    class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-bold focus:outline-none focus:border-brand-500 transition-all shadow-sm">
                <button type="submit" class="absolute inset-y-0 left-0 flex items-center px-3 text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="ph-bold ph-magnifying-glass"></i>
                </button>
            </form>
            
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                Total: {{ $pesanan->count() }} Transaksi
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <th class="px-8 py-5">ID & Tanggal</th>
                        <th class="px-6 py-5">Pelanggan</th>
                        <th class="px-6 py-5">Layanan & Berat</th>
                        <th class="px-6 py-5">Tagihan & Status Bayar</th>
                        <th class="px-6 py-5">Status Pengerjaan</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($pesanan as $p)
                    
                    {{-- 
                        LOGIKA VISUAL BARIS:
                        1. Dibatalkan -> Abu-abu redup
                        2. Berat 0 (Butuh Aksi Admin) -> Merah muda/Rose
                        3. Menunggu Bayar -> Kuning/Amber
                    --}}
                    <tr class="transition-colors group
                        {{ $p->status_pesanan == 'Dibatalkan' ? 'bg-slate-50 opacity-70 grayscale-[50%]' : 
                           ($p->berat == 0 ? 'bg-rose-50/30 hover:bg-rose-50/50' : 
                           ($p->status_pesanan == 'Menunggu Pembayaran' ? 'bg-amber-50/20 hover:bg-amber-50/40' : 'hover:bg-brand-50/30')) }}">
                        
                        {{-- 1. ID & TANGGAL --}}
                        <td class="px-8 py-5 align-top">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs group-hover:bg-brand-500 group-hover:text-white transition-colors">
                                    #{{ str_pad($p->id_pesanan, 3, '0', STR_PAD_LEFT) }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y') }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- 2. PELANGGAN --}}
                        <td class="px-6 py-5 align-top">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-100 to-fresh-100 flex items-center justify-center text-brand-600 font-bold text-xs border border-white shadow-sm">
                                    {{ substr($p->pelanggan->nama ?? 'U', 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-700 text-sm">{{ $p->pelanggan->nama ?? 'User Terhapus' }}</div>
                                    <div class="text-xs text-slate-400 font-medium">{{ $p->metode }}</div>
                                </div>
                            </div>
                        </td>

                        {{-- 3. LAYANAN & BERAT --}}
                        <td class="px-6 py-5 align-top">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 mb-1">
                                {{ $p->layanan->jenis }}
                            </span>
                            <div class="text-sm font-bold text-slate-800">{{ $p->layanan->nama_layanan }}</div>
                            
                            @if($p->berat == 0 && $p->status_pesanan != 'Dibatalkan')
                                {{-- Alert untuk Admin Input Berat --}}
                                <div class="inline-flex items-center gap-1 mt-1 px-2 py-1 rounded bg-rose-100 text-rose-600 text-[10px] font-bold uppercase tracking-wide animate-pulse">
                                    <i class="ph-bold ph-scales"></i> Belum Ditimbang
                                </div>
                            @elseif($p->status_pesanan == 'Dibatalkan')
                                <div class="text-xs text-slate-400 line-through mt-1">Berat: {{ $p->berat }} {{ $p->layanan->jenis == 'Satuan' ? 'Pcs' : 'Kg' }}</div>
                            @else
                                <div class="text-xs text-slate-500 font-bold mt-1">
                                    Berat: <span class="text-slate-900">{{ $p->berat }} {{ $p->layanan->jenis == 'Satuan' ? 'Pcs' : 'Kg' }}</span>
                                </div>
                            @endif
                        </td>

                        {{-- 4. TAGIHAN & PEMBAYARAN --}}
                        <td class="px-6 py-5 align-top">
                            @if($p->status_pesanan == 'Dibatalkan')
                                <span class="text-xs font-bold text-red-400 line-through block">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                                <span class="text-[10px] font-bold text-red-500 uppercase">Dibatalkan</span>

                            @elseif($p->berat == 0)
                                <div class="text-xs font-bold text-slate-400 italic">
                                    Menunggu timbangan...
                                </div>

                            @elseif($p->total_harga == 0)
                                <span class="inline-block px-3 py-1 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-xs border border-emerald-100">
                                    GRATIS
                                </span>

                            @else
                                {{-- TAMPILAN HARGA --}}
                                <div class="font-bold text-slate-900 text-sm">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</div>

                                {{-- LOGIKA STATUS BAYAR --}}
                                @if($p->jumlah_bayar >= $p->total_harga && $p->total_harga > 0)
                                    {{-- SKENARIO: SUDAH LUNAS --}}
                                    <div class="flex items-center gap-1 text-[10px] font-bold text-emerald-500 mt-1">
                                        <i class="ph-fill ph-check-circle"></i> Lunas
                                    </div>
                                    <div class="text-[10px] text-slate-400 font-medium">via {{ $p->snap_token ? 'Midtrans' : 'Tunai/Cash' }}</div>

                                @else
                                    {{-- SKENARIO: BELUM LUNAS --}}
                                    
                                    {{-- Peringatan jika status sudah Selesai tapi belum bayar --}}
                                    @if($p->status_pesanan == 'Selesai')
                                        <div class="flex items-center gap-1 text-[10px] font-bold text-rose-600 mt-1 bg-rose-100 px-2 py-1 rounded animate-pulse border border-rose-200 w-fit">
                                            <i class="ph-fill ph-warning-octagon"></i> BELUM LUNAS
                                        </div>
                                    @else
                                        <div class="flex items-center gap-1 text-[10px] font-bold text-slate-400 mt-1">
                                            <i class="ph-fill ph-clock"></i> Belum Bayar
                                        </div>
                                    @endif

                                    {{-- TOMBOL BAYAR TUNAI (MUNCUL JIKA BELUM LUNAS) --}}
                                    <div class="mt-2">
                                        <form action="{{ url('/admin/pesanan/'.$p->id_pesanan.'/bayar-tunai') }}" 
                                            method="POST" 
                                            id="form-bayar-{{ $p->id_pesanan }}">
                                            @csrf
                                            
                                            <button type="button" 
                                                    data-id="{{ $p->id_pesanan }}"
                                                    data-harga="{{ number_format($p->total_harga, 0, ',', '.') }}"
                                                    class="btn-bayar flex items-center gap-1.5 px-3 py-1.5 bg-white text-emerald-600 hover:bg-emerald-600 hover:text-white rounded-lg transition-all text-[10px] font-bold uppercase tracking-wide border border-emerald-200 hover:border-emerald-600 shadow-sm w-full justify-center group-btn relative overflow-hidden">
                                                <span>Bayar Tunai</span>
                                            </button>
                                        </form>
                                    </div>

                                @endif
                            @endif
                        </td>

                        {{-- 5. STATUS DROPDOWN --}}
                        <td class="px-6 py-5 align-top">
                            <form action="/admin/pesanan/{{ $p->id_pesanan }}/update-status" method="POST">
                                @csrf
                                <div class="relative w-44"> 
                                    {{-- 
                                        LOGIKA DROPDOWN:
                                        - Jika Berat 0: Disable, paksa admin input berat dulu via tombol edit.
                                        - Jika Dibatalkan: Bisa diubah kembali (opsional), tapi defaultnya merah.
                                    --}}
                                    <select name="status_pesanan" onchange="this.form.submit()" 
                                        {{ ($p->berat == 0 && $p->status_pesanan != 'Dibatalkan') ? 'disabled' : '' }}
                                        class="w-full appearance-none cursor-pointer pl-9 pr-8 py-2 rounded-xl text-xs font-bold border outline-none transition-all shadow-sm
                                        {{ $p->berat == 0 && $p->status_pesanan != 'Dibatalkan' ? 'bg-slate-100 text-slate-400 border-slate-200 cursor-not-allowed' : 
                                          ($p->status_pesanan == 'Selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-100 hover:bg-emerald-100 focus:ring-2 focus:ring-emerald-500' : 
                                          ($p->status_pesanan == 'Diproses' ? 'bg-brand-50 text-brand-700 border-brand-100 hover:bg-brand-100 focus:ring-2 focus:ring-brand-500' : 
                                          ($p->status_pesanan == 'Menunggu Pembayaran' ? 'bg-amber-50 text-amber-700 border-amber-100 hover:bg-amber-100 focus:ring-2 focus:ring-amber-500' :
                                          ($p->status_pesanan == 'Dibatalkan' ? 'bg-red-50 text-red-700 border-red-100 hover:bg-red-100' :
                                          'bg-slate-50 text-slate-700 border-slate-100 hover:bg-slate-100 focus:ring-2 focus:ring-slate-500')))) }}">
                                        
                                        <option value="Pending" {{ $p->status_pesanan == 'Pending' ? 'selected' : '' }}>
                                            {{ $p->berat == 0 ? 'Input Berat Dulu' : 'Menunggu Konfirmasi' }}
                                        </option>
                                        <option value="Menunggu Pembayaran" {{ $p->status_pesanan == 'Menunggu Pembayaran' ? 'selected' : '' }}>Menunggu Bayar</option>
                                        <option value="Diproses" {{ $p->status_pesanan == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Selesai" {{ $p->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="Dibatalkan" {{ $p->status_pesanan == 'Dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                                    </select>
                                    
                                    {{-- Ikon di dalam Select --}}
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3">
                                        @if($p->status_pesanan == 'Dibatalkan')
                                            <i class="ph-bold ph-x-circle text-red-500 text-lg"></i>
                                        @elseif($p->berat == 0)
                                            <i class="ph-bold ph-lock-key text-slate-400 text-lg"></i>
                                        @elseif($p->status_pesanan == 'Selesai')
                                            <i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i>
                                        @elseif($p->status_pesanan == 'Diproses')
                                            <i class="ph-bold ph-spinner text-brand-500 text-lg animate-spin"></i>
                                        @elseif($p->status_pesanan == 'Menunggu Pembayaran')
                                            <i class="ph-fill ph-warning-circle text-amber-500 text-lg"></i>
                                        @else
                                            <i class="ph-bold ph-hourglass text-slate-500 text-lg"></i>
                                        @endif
                                    </div>
                                    
                                    {{-- Chevron Icon --}}
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-500">
                                        <i class="ph-bold ph-caret-down"></i>
                                    </div>
                                </div>
                            </form>
                            
                            {{-- Pesan Bantuan Kecil --}}
                            @if($p->berat == 0 && $p->status_pesanan != 'Dibatalkan')
                                <div class="text-[10px] text-rose-500 mt-1 font-medium flex items-center gap-1">
                                    <i class="ph-bold ph-arrow-right"></i> Input berat di kanan
                                </div>
                            @endif
                        </td>

                        {{-- 6. AKSI --}}
                        <td class="px-8 py-5 align-top text-right">
                            <div class="flex items-center justify-end gap-2 group-hover:opacity-100 transition-opacity {{ $p->berat == 0 ? 'opacity-100' : 'opacity-0' }}">
                                
                                {{-- TOMBOL EDIT / INPUT BERAT --}}
                                @if($p->status_pesanan != 'Dibatalkan')
                                    <a href="/admin/pesanan/{{ $p->id_pesanan }}/edit" 
                                       class="w-9 h-9 flex items-center justify-center rounded-lg border shadow-sm transition-all
                                       {{ $p->berat == 0 ? 'bg-brand-600 text-white border-brand-600 animate-bounce shadow-brand-200' : 'bg-white border-slate-200 text-slate-500 hover:bg-brand-500 hover:text-white' }}"
                                       title="{{ $p->berat == 0 ? 'Wajib: Input Berat' : 'Edit Pesanan' }}">
                                        <i class="ph-bold ph-pencil-simple text-lg"></i>
                                    </a>
                                @endif
                                
                                {{-- TOMBOL HAPUS --}}
                                <form action="/admin/pesanan/{{ $p->id_pesanan }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini secara permanen?')">
                                    @csrf @method('DELETE')
                                    <button class="w-9 h-9 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-rose-500 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all shadow-sm"
                                            title="Hapus Permanen">
                                        <i class="ph-bold ph-trash text-lg"></i>
                                    </button>
                                </form>
                            </div>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($pesanan->isEmpty())
            <div class="text-center py-20">
                <div class="w-24 h-24 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 shadow-inner">
                    <i class="ph-duotone ph-clipboard-text text-4xl"></i>
                </div>
                <h3 class="text-lg font-bold text-slate-800 mb-1">Tidak Ada Pesanan</h3>
                <p class="text-slate-500 font-medium text-sm">Belum ada data pesanan yang sesuai dengan filter Anda.</p>
            </div>
        @endif
    </div>

    <script>
    // 1. Event Listener untuk Tombol Bayar
    document.querySelectorAll('.btn-bayar').forEach(button => {
        button.addEventListener('click', function() {
            // Ambil data dari tombol
            const id = this.getAttribute('data-id');
            const harga = this.getAttribute('data-harga');
            const form = document.getElementById(`form-bayar-${id}`);

            // 1. SWAL KONFIRMASI (Style User)
            Swal.fire({
                title: 'Terima Pembayaran?',
                text: `Konfirmasi terima tunai sejumlah Rp ${harga}. Lanjutkan?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#0f172a', // Slate-900 (Hitam Kebiruan)
                cancelButtonColor: '#94a3b8', // Slate-400 (Abu-abu)
                confirmButtonText: 'Ya, Terima Uang!',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                background: '#fff',
                customClass: {
                    popup: 'rounded-[2rem] p-6',
                    confirmButton: 'px-6 py-3 rounded-xl font-bold shadow-lg shadow-slate-200',
                    cancelButton: 'px-6 py-3 rounded-xl font-bold'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    
                    // 2. SWAL LOADING (Custom HTML dengan Logo)
                    Swal.fire({
                        title: '',
                        icon: '',
                        width: 400,
                        html: `
                            <div class="flex flex-col items-center justify-center pt-4">
                                <div class="relative w-20 h-20 mt-6 mb-6">
                                    <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-14 h-1.5 bg-slate-200 rounded-[100%] blur-sm animate-[pulse_1s_infinite]"></div>
                                    
                                    <img src="{{ asset('img/logo.webp') }}" width="40" height="40"
                                        class="w-full h-full object-contain animate-bounce relative z-10"
                                        alt="Loading...">
                                </div>

                                <h3 class="text-lg font-bold text-slate-800 mb-2">Memproses Transaksi...</h3>
                                <p class="text-xs text-slate-500">Mohon tunggu sebentar.</p>
                            </div>
                        `,
                        showConfirmButton: false,
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        background: '#ffffff',
                        customClass: {
                            popup: 'rounded-[2.5rem] border border-slate-100 shadow-2xl !p-0 overflow-hidden'
                        }
                    });

                    // Delay sedikit agar animasi terlihat, lalu submit form
                    setTimeout(() => {
                        form.submit();
                    }, 800);
                }
            });
        });
    });

    // 2. Alert Sukses (Cek Flash Message dari Laravel Controller)
    // Pastikan di Controller Anda return redirect()->back()->with('success', 'Pembayaran berhasil!');
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 3000,
            showConfirmButton: false,
            background: '#ffffff',
            customClass: {
                popup: 'rounded-[2rem] p-6 shadow-xl border border-emerald-100',
                title: 'text-emerald-600 font-bold',
            }
        });
    @endif

    // 3. Alert Error (Opsional)
    @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Gagal!',
            text: "{{ session('error') }}",
            customClass: {
                popup: 'rounded-[2rem] p-6',
            }
        });
    @endif
</script>
</div>
@endsection