@extends('layouts.main')
@section('title', 'Kelola Pesanan')

@section('content')
<div class="space-y-8">

    <div class="flex flex-col md:flex-row justify-between items-end gap-4 bg-white/60 backdrop-blur-md p-8 rounded-[2.5rem] border border-white/60 shadow-sm">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 mb-2">Manajemen Pesanan</h1>
            <p class="text-slate-500 font-medium">Kelola semua transaksi laundry yang masuk.</p>
        </div>
        
        <div class="flex gap-2">
            <div class="px-4 py-2 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Pending</span>
            </div>
            <div class="px-4 py-2 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-brand-500"></span>
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Diproses</span>
            </div>
            <div class="px-4 py-2 rounded-2xl bg-white border border-slate-100 shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Selesai</span>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden relative">
        
        <div class="p-6 border-b border-slate-50 flex justify-between items-center bg-slate-50/30">
            <form action="{{ url()->current() }}" method="GET" class="relative w-full max-w-sm">
                <input type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Cari nama, layanan, status, atau Tanggal" 
                    class="w-full bg-white border border-slate-200 rounded-xl py-3 pl-10 pr-4 text-sm font-bold focus:outline-none focus:border-brand-500 transition-all shadow-sm">
                    
                <button type="submit" class="absolute inset-y-0 left-0 flex items-center px-3 text-slate-400 hover:text-slate-600 transition-colors">
                    <i class="ph-bold ph-magnifying-glass"></i>
                </button>
            </form>
            
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider">
                Total: {{ $pesanan->count() }} Data
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100 text-xs uppercase tracking-wider text-slate-500 font-bold">
                        <th class="px-8 py-5">ID & Tanggal</th>
                        <th class="px-6 py-5">Pelanggan</th>
                        <th class="px-6 py-5">Detail Layanan</th>
                        <th class="px-6 py-5">Tagihan</th>
                        <th class="px-6 py-5">Status Pengerjaan</th>
                        <th class="px-8 py-5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($pesanan as $p)
                    <tr class="hover:bg-brand-50/30 transition-colors group">
                        
                        <td class="px-8 py-5 align-top">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs group-hover:bg-brand-500 group-hover:text-white transition-colors">
                                    #{{ $p->id_pesanan }}
                                </div>
                                <div>
                                    <div class="text-sm font-bold text-slate-900">{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d M Y') }}</div>
                                    <div class="text-xs text-slate-400 font-medium">{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('H:i') }} WIB</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5 align-top">
                            <div class="flex items-center gap-2">
                                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-brand-100 to-fresh-100 flex items-center justify-center text-brand-600 font-bold text-xs border border-white shadow-sm">
                                    {{ substr($p->pelanggan->nama, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-700 text-sm">{{ $p->pelanggan->nama }}</div>
                                    <div class="text-xs text-slate-400 font-medium">{{ $p->metode }}</div>
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-5 align-top">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-slate-100 text-slate-500 mb-1">
                                {{ $p->layanan->jenis }}
                            </span>
                            <div class="text-sm font-bold text-slate-800">{{ $p->layanan->nama_layanan }}</div>
                            <div class="text-xs text-slate-500 font-medium">Berat: {{ $p->berat }} Kg</div>
                        </td>

                        <td class="px-6 py-5 align-top">
                            @if($p->total_harga == 0)
                                <span class="inline-block px-3 py-1 rounded-lg bg-emerald-50 text-emerald-600 font-bold text-xs border border-emerald-100">
                                    GRATIS (Bonus)
                                </span>
                            @else
                                <div class="font-bold text-slate-900 text-sm">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</div>
                                @if($p->status_pesanan == 'Selesai' || $p->status_pesanan == 'Diproses')
                                    <div class="flex items-center gap-1 text-[10px] font-bold text-emerald-500 mt-1">
                                        <i class="ph-fill ph-check-circle"></i> Lunas
                                    </div>
                                @elseif($p->snap_token)
                                    <div class="flex items-center gap-1 text-[10px] font-bold text-amber-500 mt-1">
                                        <i class="ph-fill ph-clock"></i> Belum Bayar
                                    </div>
                                @endif
                            @endif
                        </td>

                        <td class="px-6 py-5 align-top">
                            <form action="/admin/pesanan/{{ $p->id_pesanan }}/update-status" method="POST">
                                @csrf
                                <div class="relative w-40">
                                    <select name="status_pesanan" onchange="this.form.submit()" 
                                        class="w-full appearance-none cursor-pointer pl-9 pr-4 py-2 rounded-xl text-xs font-bold border outline-none transition-all shadow-sm
                                        {{ $p->status_pesanan == 'Selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-100 hover:bg-emerald-100 focus:ring-2 focus:ring-emerald-500' : 
                                          ($p->status_pesanan == 'Diproses' ? 'bg-brand-50 text-brand-700 border-brand-100 hover:bg-brand-100 focus:ring-2 focus:ring-brand-500' : 
                                          'bg-amber-50 text-amber-700 border-amber-100 hover:bg-amber-100 focus:ring-2 focus:ring-amber-500') }}">
                                        <option value="Pending" {{ $p->status_pesanan == 'Pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="Diproses" {{ $p->status_pesanan == 'Diproses' ? 'selected' : '' }}>Diproses</option>
                                        <option value="Selesai" {{ $p->status_pesanan == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                                    </select>
                                    
                                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center px-3">
                                        @if($p->status_pesanan == 'Selesai')
                                            <i class="ph-fill ph-check-circle text-emerald-500 text-lg"></i>
                                        @elseif($p->status_pesanan == 'Diproses')
                                            <i class="ph-bold ph-spinner text-brand-500 text-lg animate-spin"></i>
                                        @else
                                            <i class="ph-fill ph-clock text-amber-500 text-lg"></i>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </td>

                        <td class="px-8 py-5 align-top text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                <a href="/admin/pesanan/{{ $p->id_pesanan }}/edit" 
                                   class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-500 hover:bg-brand-500 hover:text-white hover:border-brand-500 transition-all shadow-sm"
                                   title="Edit Detail">
                                    <i class="ph-bold ph-pencil-simple"></i>
                                </a>
                                
                                <form action="/admin/pesanan/{{ $p->id_pesanan }}" method="POST" onsubmit="return confirm('Yakin hapus pesanan ini? Poin pelanggan akan direset jika pesanan Selesai.')">
                                    @csrf @method('DELETE')
                                    <button class="w-8 h-8 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-rose-500 hover:bg-rose-500 hover:text-white hover:border-rose-500 transition-all shadow-sm"
                                            title="Hapus">
                                        <i class="ph-bold ph-trash"></i>
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
                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-4 text-slate-300 text-4xl">
                    <i class="ph-duotone ph-clipboard-text"></i>
                </div>
                <p class="text-slate-500 font-medium">Belum ada pesanan masuk.</p>
            </div>
        @endif
    </div>
</div>
@endsection