@extends('layouts.main')
@section('title', 'Admin Dashboard')

@section('content')
<div class="space-y-8">

    <div class="flex flex-col md:flex-row justify-between items-end gap-4 bg-white/60 backdrop-blur-md p-8 rounded-[2.5rem] border border-white/60 shadow-sm">
        <div>
            <span class="text-xs font-bold tracking-widest text-brand-600 uppercase mb-1 block">Overview</span>
            <h1 class="text-3xl font-bold text-slate-900">Dashboard Admin</h1>
            <p class="text-slate-500 font-medium mt-1">Pantau performa bisnis laundry hari ini.</p>
        </div>
        <div class="flex items-center gap-2 text-sm font-medium text-slate-500 bg-white px-4 py-2 rounded-full border border-slate-200 shadow-sm">
            <i class="ph-bold ph-calendar-blank text-brand-600"></i>
            {{ \Carbon\Carbon::now()->format('d F Y') }}
        </div>
    </div>

    <div class="grid md:grid-cols-3 gap-6">
        
        <div class="p-8 rounded-[2rem] bg-white border border-slate-100 shadow-sm hover:shadow-glass hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <span class="px-3 py-1 rounded-full bg-slate-50 text-slate-500 text-xs font-bold border border-slate-100">Total</span>
            </div>
            <h3 class="text-4xl font-bold text-slate-900 mb-1">{{ $total_pesanan }}</h3>
            <p class="text-slate-500 text-sm font-medium">Pesanan Masuk</p>
        </div>

        <div class="p-8 rounded-[2rem] bg-white border border-slate-100 shadow-sm hover:shadow-glass hover:-translate-y-1 transition-all duration-300 group">
            <div class="flex justify-between items-start mb-4">
                <span class="px-3 py-1 rounded-full bg-slate-50 text-slate-500 text-xs font-bold border border-slate-100">Aktif</span>
            </div>
            <h3 class="text-4xl font-bold text-slate-900 mb-1">{{ $total_pelanggan }}</h3>
            <p class="text-slate-500 text-sm font-medium">Pelanggan Terdaftar</p>
        </div>

        <div class="p-8 rounded-[2rem] bg-gradient-to-br from-slate-900 to-slate-800 text-white shadow-xl shadow-slate-200 hover:-translate-y-1 transition-all duration-300 relative overflow-hidden group">
            <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -mr-16 -mt-16 transition-transform group-hover:scale-110"></div>
            
            <div class="flex justify-between items-start mb-4 relative z-10">
                <span class="px-3 py-1 rounded-full bg-white/20 text-white text-xs font-bold border border-white/10">Revenue</span>
            </div>
            <h3 class="text-3xl font-bold mb-1 relative z-10">Rp {{ number_format($pendapatan, 0, ',', '.') }}</h3>
            <p class="text-slate-400 text-sm font-medium relative z-10">Total Pendapatan Selesai</p>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="p-8 border-b border-slate-50 flex justify-between items-center">
            <h2 class="text-xl font-bold text-slate-800">Pesanan Terbaru</h2>
            <a href="/admin/pesanan" class="text-sm font-bold text-brand-600 hover:text-brand-700 hover:underline">Lihat Semua</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-50 text-xs uppercase tracking-wider text-slate-500">
                        <th class="px-8 py-4 font-bold">Pelanggan</th>
                        <th class="px-6 py-4 font-bold">Layanan</th>
                        <th class="px-6 py-4 font-bold">Berat</th>
                        <th class="px-6 py-4 font-bold">Status</th>
                        <th class="px-8 py-4 font-bold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($pesanan_terbaru as $p)
                    <tr class="hover:bg-slate-50/50 transition-colors group">
                        <td class="px-8 py-4">
                            <div class="font-bold text-slate-900">{{ $p->pelanggan->nama }}</div>
                            <div class="text-xs text-slate-400 font-medium">#Order-{{ $p->id_pesanan }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-600">
                            {{ $p->layanan->nama_layanan }}
                        </td>
                        <td class="px-6 py-4 text-sm font-medium text-slate-600">
                            {{ $p->berat }} Kg
                        </td>
                        <td class="px-6 py-4">
                            <form action="/admin/pesanan/{{ $p->id_pesanan }}/update-status" method="POST">
                                @csrf
                                <select name="status_pesanan" onchange="this.form.submit()" 
                                    class="appearance-none cursor-pointer pl-3 pr-8 py-1.5 rounded-full text-xs font-bold border outline-none transition-all
                                    {{ $p->status_pesanan == 'Selesai' ? 'bg-emerald-50 text-emerald-700 border-emerald-100 hover:bg-emerald-100' : 
                                      ($p->status_pesanan == 'Diproses' ? 'bg-brand-50 text-brand-700 border-brand-100 hover:bg-brand-100' : 
                                      'bg-amber-50 text-amber-700 border-amber-100 hover:bg-amber-100') }}">
                                    <option value="Pending" {{ $p->status_pesanan == 'Pending' ? 'selected' : '' }}>⏳ Pending</option>
                                    <option value="Diproses" {{ $p->status_pesanan == 'Diproses' ? 'selected' : '' }}>🫧 Diproses</option>
                                    <option value="Selesai" {{ $p->status_pesanan == 'Selesai' ? 'selected' : '' }}>✅ Selesai</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-8 py-4 text-right">
                            <a href="/admin/pesanan/{{ $p->id_pesanan }}/edit" class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-50 text-slate-400 hover:bg-brand-500 hover:text-white transition-all shadow-sm">
                                <i class="ph-bold ph-pencil-simple"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        @if($pesanan_terbaru->isEmpty())
            <div class="text-center py-12">
                <p class="text-slate-400 text-sm">Belum ada pesanan terbaru.</p>
            </div>
        @endif
    </div>
</div>
@endsection