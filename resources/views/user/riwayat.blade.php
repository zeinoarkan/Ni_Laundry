@extends('layouts.main')
@section('title', 'Riwayat Pesanan')

@section('content')

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>

<div class="max-w-4xl mx-auto space-y-8">
    
    <div class="flex flex-col md:flex-row justify-between items-end gap-4 bg-white/60 backdrop-blur-md p-8 rounded-[2.5rem] border border-white/60 shadow-sm"
         data-aos="fade-down">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 mb-2">Riwayat Transaksi</h1>
            <p class="text-slate-500 font-medium">Pantau status cucian dan histori pembayaran Anda.</p>
        </div>
        <a href="/layanan" class="px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 font-bold text-sm hover:bg-slate-50 hover:border-slate-300 transition-all flex items-center gap-2 shadow-sm">
            <i class="ph-bold ph-plus"></i> Pesan Lagi
        </a>
    </div>

    @if($pesanan->isEmpty())
        <div class="text-center py-20 bg-white/50 rounded-[2.5rem] border border-dashed border-slate-300"
             data-aos="zoom-in" data-aos-delay="200">
            <div class="w-24 h-24 bg-slate-100 rounded-full flex items-center justify-center mx-auto mb-6 text-slate-300 text-5xl">
                <i class="ph-duotone ph-basket"></i>
            </div>
            <h3 class="text-xl font-bold text-slate-800 mb-2">Belum ada pesanan</h3>
            <p class="text-slate-500 mb-6 font-medium">Riwayat cucian Anda akan muncul di sini.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($pesanan as $p)
            
            <div class="bg-white rounded-[2rem] p-6 border border-slate-100 shadow-sm hover:shadow-glass transition-all duration-300 flex flex-col md:flex-row justify-between items-center gap-6 group"
                 data-aos="fade-up"
                 data-aos-delay="{{ $loop->index * 100 }}" 
                 data-aos-offset="50">
                
                <div class="flex items-center gap-6 w-full md:w-auto">
                    <div class="hidden md:flex flex-col items-center justify-center w-20 h-20 bg-brand-50 rounded-2xl text-brand-600 border border-brand-100 group-hover:bg-brand-600 group-hover:text-white transition-colors duration-500">
                        <span class="text-xs font-bold uppercase tracking-wider">{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('M') }}</span>
                        <span class="text-2xl font-bold">{{ \Carbon\Carbon::parse($p->tanggal_pesan)->format('d') }}</span>
                    </div>
                    
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h4 class="font-bold text-slate-800 text-lg">{{ $p->layanan->nama_layanan }}</h4>
                            @if($p->berat > 8)
                                <span class="px-2 py-0.5 rounded-md text-[10px] font-bold bg-purple-100 text-purple-600 uppercase tracking-wider">Promo >8Kg</span>
                            @endif
                        </div>
                        <p class="text-sm text-slate-500 mb-3 font-medium">
                            {{ $p->berat }} Kg • {{ $p->metode }}
                        </p>
                        
                        @if($p->status_pesanan == 'Pending')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-50 text-amber-700 text-xs font-bold border border-amber-100">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Menunggu Bayar
                            </span>
                        @elseif($p->status_pesanan == 'Diproses')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-brand-50 text-brand-700 text-xs font-bold border border-brand-100">
                                <span class="w-2 h-2 rounded-full border-2 border-brand-500 border-t-transparent animate-spin"></span> Sedang Dicuci
                            </span>
                        @elseif($p->status_pesanan == 'Selesai')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 text-emerald-700 text-xs font-bold border border-emerald-100">
                                <i class="ph-bold ph-check-circle"></i> Selesai
                            </span>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col items-end gap-3 w-full md:w-auto border-t md:border-t-0 border-slate-100 pt-4 md:pt-0">
                    <div class="text-right">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-0.5">Total Tagihan</p>
                        @if($p->total_harga == 0)
                             <span class="text-2xl font-bold text-emerald-500">GRATIS</span>
                        @else
                            <div class="flex flex-col items-end">
                                <span class="text-2xl font-bold text-slate-800">Rp {{ number_format($p->total_harga, 0, ',', '.') }}</span>
                                @if($p->berat > 8 || ($p->berat * $p->layanan->harga > $p->total_harga))
                                    <span class="text-xs text-slate-400 font-medium line-through">Rp {{ number_format($p->berat * $p->layanan->harga, 0, ',', '.') }}</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    @if($p->status_pesanan == 'Pending' && $p->snap_token)
                        <button onclick="bayar('{{ $p->id_pesanan }}', '{{ $p->snap_token }}')" 
                                class="w-full md:w-auto px-6 py-2.5 rounded-xl bg-slate-900 text-white font-bold text-sm shadow-lg hover:shadow-glow hover:-translate-y-0.5 hover:bg-brand-600 transition-all duration-300 flex items-center justify-center gap-2">
                            <i class="ph-bold ph-credit-card"></i> Bayar
                        </button>
                    @elseif($p->status_pesanan == 'Diproses')
                         <span class="text-xs font-bold text-slate-400 flex items-center gap-1">
                            <i class="ph-fill ph-check-circle"></i> Lunas
                         </span>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @endif
</div>

<script type="text/javascript">
    function bayar(id_pesanan, token) {
        snap.pay(token, {
            onSuccess: function(result){ window.location.href = '/pesanan/sukses/' + id_pesanan; },
            onPending: function(result){ alert("Menunggu pembayaran!"); location.reload(); },
            onError: function(result){ alert("Gagal!"); location.reload(); }
        });
    }
</script>
@endsection