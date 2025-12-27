@extends('layouts.app')
@section('content')

<div class="p-8 bg-gray-50 min-h-screen">
    {{-- En-tête avec Statistiques Rapides --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-900 tracking-tight">Suivi des Remboursements</h1>
            <p class="text-gray-500 mt-1 flex items-center gap-2">
                <span class="flex h-2 w-2 rounded-full bg-green-500"></span>
                {{ $demandesActives->count() }} dossiers actifs en cours de recouvrement
            </p>
        </div>
        
        <div class="flex gap-4">
            <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="bg-orange-100 p-3 rounded-xl">
                    <i class="fas fa-hand-holding-usd text-orange-600"></i>
                </div>
                <div>
                    <p class="text-xs text-gray-400 uppercase font-bold tracking-wider">Total Restant</p>
                    <p class="text-lg font-black text-gray-800">{{ number_format($demandesActives->sum('restant'), 0, ',', ' ') }} F</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Tableau Principal --}}
    <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/50 text-gray-400 text-[11px] uppercase tracking-widest border-b border-gray-50">
                        <th class="p-6 font-black">Client / Structure</th>
                        <th class="p-6 font-black">Type de prêt</th>
                        <th class="p-6 font-black">Progression</th>
                        <th class="p-6 font-black">Solde Restant</th>
                        <th class="p-6 font-black text-center">Détails</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($demandesActives as $demande)
                    <tr class="group hover:bg-orange-50/30 transition-all duration-300">
                        <td class="p-6">
                            <div class="flex items-center gap-4">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-gray-500 font-bold shadow-sm">
                                    {{ substr($demande->display_name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-800 group-hover:text-orange-600 transition-colors">{{ $demande->display_name }}</p>
                                    <p class="text-xs text-gray-400">ID: #PR-{{ str_pad($demande->id, 4, '0', STR_PAD_LEFT) }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            @if($demande->type_label === 'Entreprise')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-blue-50 text-blue-600 text-[10px] font-black uppercase tracking-wide border border-blue-100">
                                    <i class="fas fa-building text-[12px]"></i> Entreprise
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-lg bg-purple-50 text-purple-600 text-[10px] font-black uppercase tracking-wide border border-purple-100">
                                    <i class="fas fa-user text-[12px]"></i> Particulier
                                </span>
                            @endif
                        </td>
                        <td class="p-6">
                            <div class="w-32">
                                <div class="flex justify-between mb-1">
                                    <span class="text-[10px] font-bold text-gray-400">{{ $demande->pourcentage }}%</span>
                                </div>
                                <div class="h-1.5 w-full bg-gray-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-orange-500 rounded-full transition-all duration-1000" style="width: {{ $demande->pourcentage }}%"></div>
                                </div>
                            </div>
                        </td>
                        <td class="p-6">
                            <span class="text-sm font-black text-gray-900">{{ number_format($demande->restant, 0, ',', ' ') }} <small class="text-gray-400 font-normal">FCFA</small></span>
                        </td>
                        <td class="p-6 text-center">
                            <a href="{{ route('admin.remboursement.details', ['id' => $demande->id, 'type' => strtolower($demande->type_label)]) }}" 
                               class="inline-flex items-center justify-center h-10 w-10 rounded-xl bg-white border border-gray-200 text-gray-400 hover:text-orange-500 hover:border-orange-200 hover:bg-orange-50 transition-all shadow-sm group-hover:scale-110">
                                <i class="fas fa-chevron-right text-xs"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="p-20 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-20 w-20 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="fas fa-folder-open text-gray-200 text-3xl"></i>
                                </div>
                                <p class="text-gray-400 font-medium">Aucun dossier de remboursement actif.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection