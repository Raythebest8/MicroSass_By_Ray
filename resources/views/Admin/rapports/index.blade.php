@extends('layouts.app')

@section('content')
    <div class="p-8 bg-gray-50 min-h-screen">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h1 class="text-3xl font-extrabold text-gray-800">Rapport Hebdomadaire</h1>
                <p class="text-gray-500 font-medium">Période : du {{ $debutSemaine->format('d/m') }} au
                    {{ $finSemaine->format('d/m/Y') }}</p>
            </div>
            <a href="{{ route('admin.rapports.print', ['semaine' => $debutSemaine->format('Y-m-d')]) }}" target="_blank"
                class="bg-indigo-600 text-white px-4 py-2 rounded-lg shadow hover:bg-indigo-700 transition flex items-center gap-2">
                <i class="fas fa-file-invoice"></i> Générer le rapport officiel
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-indigo-500">
                <div class="text-gray-400 text-sm font-bold uppercase mb-1">Dossiers Créés</div>
                <div class="text-3xl font-black text-gray-800">{{ $nouveauxPrets }}</div>
                <div class="text-xs text-green-500 mt-2 font-semibold">Cette semaine</div>
            </div>

            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-green-500">
                <div class="text-gray-400 text-sm font-bold uppercase mb-1">Total Encaissé</div>
                <div class="text-3xl font-black text-green-600">{{ number_format($encaissements, 0, ',', ' ') }} Fcfa</div>
                <div class="text-xs text-gray-500 mt-2">Remboursements validés</div>
            </div>

            {{-- <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-orange-500">
            <div class="text-gray-400 text-sm font-bold uppercase mb-1">Volume Prêté</div>
            <div class="text-3xl font-black text-gray-800">{{ number_format($volumePrete, 0, ',', ' ') }} Fcfa</div>
            <div class="text-xs text-gray-500 mt-2">Nouveaux engagements</div>
        </div> --}}
            <div class="bg-white p-6 rounded-2xl shadow-sm border-l-4 border-orange-500">
                <div class="text-gray-400 text-sm font-bold uppercase mb-1">Volume Prêté</div>
                <div class="text-3xl font-black text-gray-800">
                    {{ number_format($volumePrete, 0, ',', ' ') }} Fcfa
                </div>
                <div class="flex gap-2 mt-2">
                    <span class="text-[10px] bg-blue-50 text-blue-600 px-2 py-0.5 rounded">
                        Ent: {{ number_format($volumeEntreprises, 0, ',', ' ') }}
                    </span>
                    <span class="text-[10px] bg-purple-50 text-purple-600 px-2 py-0.5 rounded">
                        Part: {{ number_format($volumeParticuliers, 0, ',', ' ') }}
                    </span>
                </div>
                <div class="text-xs text-gray-500 mt-1">Nouveaux engagements approuvés</div>
            </div>
        </div>

        <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100">
            <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                <h3 class="font-bold text-gray-700">Flux de trésorerie récent</h3>
            </div>
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="text-gray-400 text-xs uppercase tracking-wider">
                        <th class="px-6 py-4 font-bold">Date</th>
                        <th class="px-6 py-4 font-bold">Client / Échéance</th>
                        <th class="px-6 py-4 font-bold">Montant</th>
                        <th class="px-6 py-4 font-bold">Mode</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($derniersPaiements as $paiement)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $paiement->date_paiement->format('d/m/Y') }}</td>
                            <td class="px-6 py-4 text-sm font-bold text-gray-800">Paiement Échéance
                                #{{ $paiement->echeance_id }}</td>
                            <td class="px-6 py-4 text-sm font-black text-green-600">+
                                {{ number_format($paiement->montant, 0, ',', ' ') }} Fcfa</td>
                            <td class="px-6 py-4">
                                <span
                                    class="px-3 py-1 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold uppercase">
                                    {{ $paiement->methode_paiement }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
