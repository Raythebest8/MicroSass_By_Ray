@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-[#FDFDFF] py-12 font-sans">
    <div class="max-w-6xl mx-auto px-6">
        
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-6">
            <div>
                <h1 class="text-4xl font-black text-slate-900 tracking-tight mb-2">Flux Financiers</h1>
                <div class="flex items-center gap-3">
                    <span class="flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                    <p class="text-slate-500 font-bold text-xs uppercase tracking-widest">Mise à jour en temps réel</p>
                </div>
            </div>
            
            <a href="{{ route('admin.transactions.create') }}" class="bg-slate-900 text-white px-8 py-4 rounded-2xl font-black text-xs uppercase tracking-[0.2em] hover:bg-orange-600 transition-all shadow-2xl shadow-slate-200 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path></svg>
                Nouvelle Opération
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-[2rem] border border-slate-50 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Dépôts</p>
                <p class="text-2xl font-black text-blue-600 tracking-tight">{{ number_format($transactions->where('type', 'depot')->sum('montant'), 0, ',', ' ') }} <span class="text-xs">FCFA</span></p>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-50 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Total Retraits</p>
                <p class="text-2xl font-black text-green-600 tracking-tight">{{ number_format($transactions->where('type', 'retrait')->sum('montant'), 0, ',', ' ') }} <span class="text-xs">FCFA</span></p>
            </div>
            <div class="bg-white p-6 rounded-[2rem] border border-slate-50 shadow-sm">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Activité</p>
                <p class="text-2xl font-black text-slate-900 tracking-tight">{{ $transactions->total() }} <span class="text-xs text-slate-400 font-bold uppercase">Flux</span></p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div class="lg:col-span-3 flex flex-wrap gap-3">
                <a href="{{ route('admin.transactions.index') }}" 
                   class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all 
                   {{ !request('type') ? 'bg-slate-900 text-white shadow-lg shadow-slate-200' : 'bg-white text-slate-400 border border-slate-100 hover:shadow-md' }}">
                    Tous
                </a>
                @foreach(['depot' => 'blue', 'retrait' => 'green', 'transfert' => 'purple'] as $type => $color)
                    <a href="{{ route('admin.transactions.index', ['type' => $type, 'search' => request('search')]) }}" 
                       class="px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-widest border transition-all 
                       {{ request('type') == $type ? "bg-$color-600 text-white border-$color-600 shadow-lg" : "bg-white text-slate-400 border-slate-100 hover:text-$color-600 hover:shadow-md" }}">
                        {{ ucfirst($type) }}s
                    </a>
                @endforeach
            </div>
            
            <form action="{{ route('admin.transactions.index') }}" method="GET" class="relative group">
                @if(request('type')) <input type="hidden" name="type" value="{{ request('type') }}"> @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Réf. ou client..." 
                       class="w-full pl-12 pr-4 py-3 bg-white border-slate-100 border rounded-2xl text-xs font-bold focus:ring-2 focus:ring-slate-100 transition-all shadow-sm group-hover:shadow-md">
                <svg class="w-4 h-4 absolute left-4 top-3.5 text-slate-300 group-hover:text-slate-900 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </form>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-50 shadow-[0_20px_60px_rgba(0,0,0,0.03)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-50/50">
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Transaction</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Client</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Date</th>
                            <th class="px-6 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Montant</th>
                            <th class="px-8 py-6 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Action</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-50">
                        @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/30 transition-all group">
                            <td class="px-8 py-7">
                                <div class="flex items-center gap-5">
                                    @php
                                        $styles = [
                                            'depot' => ['bg' => 'bg-blue-100/50', 'text' => 'text-blue-600', 'icon' => 'M12 4v16m8-8H4'],
                                            'retrait' => ['bg' => 'bg-green-100/50', 'text' => 'text-green-600', 'icon' => 'M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z'],
                                            'transfert' => ['bg' => 'bg-purple-100/50', 'text' => 'text-purple-600', 'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                                        ];
                                        $s = $styles[$trx->type];
                                    @endphp
                                    <div class="h-12 w-12 {{ $s['bg'] }} {{ $s['text'] }} rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $s['icon'] }}"></path></svg>
                                    </div>
                                    <div>
                                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">#{{ $trx->reference }}</p>
                                        <p class="text-sm font-black text-slate-900 capitalize">{{ $trx->type }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-7">
                                <span class="text-sm font-black text-slate-800">{{ $trx->user->prenom }} {{ $trx->user->nom }}</span>
                                @if($trx->type === 'transfert' && $trx->receiver)
                                    <div class="flex items-center gap-1 mt-1 text-[10px] font-black text-purple-600 uppercase">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                                        {{ $trx->receiver->prenom }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-7 text-xs font-bold text-slate-500 uppercase">
                                {{ $trx->created_at->translatedFormat('d M Y') }}<br>
                                <span class="text-[10px] text-slate-300">{{ $trx->created_at->format('H:i') }}</span>
                            </td>
                            <td class="px-6 py-7">
                                <span class="text-lg font-black tracking-tight {{ $trx->type == 'depot' ? 'text-blue-600' : 'text-slate-900' }}">
                                    {{ $trx->type == 'depot' ? '+' : '-' }} {{ number_format($trx->montant, 0, ',', ' ') }}
                                </span>
                                <span class="text-[10px] font-black text-slate-300 uppercase ml-1">FCFA</span>
                            </td>
                            <td class="px-8 py-7 text-right">
                                <a href="{{ route('admin.transactions.show', $trx) }}" class="inline-flex items-center justify-center h-10 w-10 bg-slate-50 text-slate-400 rounded-xl hover:bg-slate-900 hover:text-white transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-8 py-20 text-center">
                                <p class="text-slate-400 font-bold text-sm uppercase tracking-widest">Aucun flux trouvé</p>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-8 bg-slate-50/20 border-t border-slate-50">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
</div>

<style>
    .pagination-custom nav svg { width: 1.5rem; }
</style>
@endsection