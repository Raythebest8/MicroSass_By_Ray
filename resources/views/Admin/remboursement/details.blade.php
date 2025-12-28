@extends('layouts.app')
@section('content')
    <div class="p-8 bg-gray-50 min-h-screen">
        {{-- En-tête avec Navigation --}}
        <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
            <div>
                <a href="{{ route('admin.remboursement.index') }}"
                    class="inline-flex items-center gap-2 text-sm font-bold text-orange-500 hover:text-orange-600 transition mb-3">
                    <i class="fas fa-chevron-left"></i>
                    RETOUR À LA LISTE
                </a>
                <h1 class="text-3xl font-black text-gray-900 tracking-tight">Historique des Paiements</h1>
                <div class="flex items-center gap-2 mt-1">
                    <span class="h-2 w-2 rounded-full bg-orange-500"></span>
                    <p class="text-gray-500 font-semibold uppercase text-xs tracking-widest">{{ $demande->display_name }}</p>
                </div>
            </div>

            {{-- Badge Statut Global --}}
            <div class="bg-white px-5 py-3 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Progression</p>
                    <p class="text-lg font-black text-gray-800">{{ $demande->pourcentage }}%</p>
                </div>
                <div class="w-16 h-16 relative">
                    <svg class="w-full h-full" viewBox="0 0 36 36">
                        <path class="text-gray-100" stroke-width="3" stroke="currentColor" fill="none"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                        <path class="text-orange-500" stroke-width="3" stroke-dasharray="{{ $demande->pourcentage }}, 100"
                            stroke-linecap="round" stroke="currentColor" fill="none"
                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                    </svg>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
            {{-- Sidebar Gauche : État financier --}}
            <div class="lg:col-span-1 space-y-6">
                <div
                    class="bg-white rounded-[2rem] p-8 shadow-xl shadow-gray-200/40 border border-gray-100 relative overflow-hidden">
                    <div class="absolute top-0 right-0 p-4 opacity-5">
                        <i class="fas fa-wallet text-6xl text-gray-900"></i>
                    </div>

                    <h3 class="font-black text-gray-400 mb-6 uppercase text-[11px] tracking-[0.2em]">Résumé Financier</h3>

                    <div class="space-y-6">
                        <div>
                            <p class="text-sm text-gray-400 mb-1">Total Accordé</p>
                            <p class="text-2xl font-black text-gray-900">
                                {{ number_format($demande->montant_souhaite, 0, ',', ' ') }} <span class="text-xs">F</span>
                            </p>
                        </div>

                        <div class="pt-4 border-t border-gray-50">
                            <p class="text-sm text-green-500 mb-1 font-bold">Déjà Remboursé</p>
                            <p class="text-2xl font-black text-green-600">{{ number_format($demande->paye, 0, ',', ' ') }}
                                <span class="text-xs">F</span>
                            </p>
                        </div>

                        <div class="pt-4 border-t border-gray-50">
                            <p class="text-sm text-red-400 mb-1 font-bold">Reste à percevoir</p>
                            <p class="text-3xl font-black text-red-500">{{ number_format($demande->restant, 0, ',', ' ') }}
                                <span class="text-xs font-bold uppercase">FCFA</span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tableau des échéances --}}
            <div class="lg:col-span-3">
                <div class="bg-white rounded-[2rem] shadow-xl shadow-gray-200/40 border border-gray-100 overflow-hidden">
                    <div class="p-6 border-b border-gray-50 bg-white">
                        <h3 class="font-black text-gray-700 uppercase text-xs tracking-widest">Calendrier des Échéances</h3>
                    </div>

                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/50 text-gray-400 text-[11px] uppercase tracking-widest">
                                <th class="p-6 font-black">Date de paiement</th>
                                <th class="p-6 font-black">Montant attendu</th>
                                <th class="p-6 font-black">État du versement</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach ($demande->echeances->sortBy('date_echeance') as $echeance)
                                <tr class="hover:bg-gray-50/50 transition duration-200">
                                    <td class="p-6">
                                        <div class="flex items-center gap-3">
                                            <div class="bg-gray-100 p-2 rounded-lg text-gray-500">
                                                <i class="far fa-calendar-alt"></i>
                                            </div>
                                            <span class="font-bold text-gray-700">
                                                {{ \Carbon\Carbon::parse($echeance->date_echeance)->translatedFormat('d F Y') }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        <div class="flex flex-col">
                                            {{-- Affiche "Échéance 1 sur 12" par exemple --}}
                                            <span
                                                class="text-[10px] font-black text-orange-500 uppercase tracking-widest mb-1">
                                                Échéance {{ $loop->iteration }} / {{ $demande->echeances->count() }}
                                            </span>

                                            <div class="text-xl font-black text-gray-900 leading-none">
                                                {{ number_format($echeance->montant, 0, ',', ' ') }} <small
                                                    class="text-xs">F</small>
                                            </div>

                                            <div
                                                class="text-[10px] text-gray-400 font-bold uppercase tracking-tighter mt-1">
                                                Ref: #ECH-{{ $echeance->id }}
                                            </div>
                                        </div>
                                    </td>
                                    <td class="p-6">
                                        @php
                                            $statut = strtolower($echeance->statut);
                                        @endphp

                                        <div class="flex items-center justify-between gap-4">
                                            {{-- Badge de Statut --}}
                                            @if ($statut === 'payé' || $statut === 'valide')
                                                <div
                                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-green-50 text-green-600 border border-green-100">
                                                    <i class="fas fa-check-circle text-[10px]"></i>
                                                    <span
                                                        class="text-[10px] font-black uppercase tracking-widest">Payé</span>
                                                </div>

                                                {{-- Bouton Reçu PDF --}}
                                                {{-- <a href="{{ route('admin.paiements.downloadRecu', $echeance->id) }}" 
               class="flex items-center gap-2 text-gray-400 hover:text-red-500 transition-colors duration-200"
               title="Télécharger le reçu PDF">
                <i class="fas fa-file-pdf text-xl"></i>
                <span class="text-[10px] font-bold uppercase tracking-tighter">Reçu</span>
            </a> --}}
                                            @elseif(\Carbon\Carbon::parse($echeance->date_echeance)->isPast())
                                                <div
                                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-red-50 text-red-600 border border-red-100">
                                                    <i class="fas fa-exclamation-circle text-[10px]"></i>
                                                    <span class="text-[10px] font-black uppercase tracking-widest">En
                                                        Retard</span>
                                                </div>
                                            @else
                                                <div
                                                    class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-blue-50 text-blue-600 border border-blue-100">
                                                    <i class="far fa-clock text-[10px]"></i>
                                                    <span class="text-[10px] font-black uppercase tracking-widest">En
                                                        attente</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
