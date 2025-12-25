@extends('layouts.app')

@section('content')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <div class="container mx-auto p-6 min-h-screen">

        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-8">
            <div class="lg:col-span-3 grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-emerald-100 p-6 rounded-xl shadow-sm border border-emerald-200">
                    <p class="text-emerald-800 text-sm font-medium">Montant total encaissé</p>
                    <h2 class="text-2xl font-bold text-emerald-900 mt-1">
                      {{ number_format($totalMontant, 0, ',', '.') }} Fcfa

                    </h2>
                    <p class="text-emerald-600 text-xs mt-2">{{ now()->translatedFormat('d F Y') }}</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                    <p class="text-gray-500 text-sm font-medium">Restant à percevoir</p>
                    <h2 class="text-2xl font-bold text-blue-900 mt-1">
                        {{ number_format($paiementEnCours ?? 0, 0, ',', '.') }} Fcfa
                    </h2>
                    <p class="text-gray-400 text-xs mt-2 italic">Global échéances</p>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex flex-col justify-center">
                    <div class="flex justify-between items-center mb-2">
                        <p class="text-gray-500 text-sm font-medium">Canaux actifs</p>
                        <a href="{{ route('admin.paiements.create') }}" class="text-blue-500"><i
                                class="fas fa-plus-circle"></i></a>
                    </div>
                    <div class="flex gap-3 items-center opacity-80">
                        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/1280px-Visa_Inc._logo.svg.png"
                            class="h-5" alt="Visa">
                        <img src="https://img.over-blog-kiwi.com/1/54/05/32/20200606/ob_57d1c7_moov.png" class="h-5"
                            alt="Flooz">
                        <img src="https://cdn-ilcckdn.nitrocdn.com/mJCoEvGkeejlEvJdEMQjiBVdPamvpGSY/assets/images/optimized/rev-3060196/yas.tg/wp-content/uploads/2025/12/Logo-Mixx-By-YAS-1-1.jpeg"
                            class="h-5" alt="mix by yas">
                    </div>
                </div>
            </div>

            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 flex flex-col items-center justify-center">
                <p class="text-gray-400 text-[10px] uppercase font-bold mb-2">Flux par méthode</p>
                <div style="height: 100px; width: 100px;">
                    <canvas id="methodChart"></canvas>
                </div>
            </div>
        </div>

        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold text-gray-700">Paiements récents</h3>
            <a href="{{ route('admin.paiements.create') }}"
                class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm transition shadow-sm flex items-center">
                <i class="fas fa-plus mr-2"></i> Enregistrer un paiement
            </a>
        </div>

        <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
            <form action="{{ route('admin.paiements.index') }}" method="GET" class="flex flex-wrap gap-4 items-end">
                <div class="flex-1 min-w-[200px]">
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Rechercher un client</label>
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Nom ou prénom..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 text-sm">
                        <div class="absolute left-3 top-2.5 text-gray-400"><i class="fas fa-search"></i></div>
                    </div>
                </div>

                <div class="w-48">
                    <label class="block text-xs font-medium text-gray-500 uppercase mb-1">Méthode</label>
                    <select name="methode" class="w-full py-2 border border-gray-200 rounded-lg text-sm">
                        <option value="">Toutes</option>
                        <option value="Guichet" {{ request('methode') == 'Guichet' ? 'selected' : '' }}>Guichet</option>
                        <option value="Flooz" {{ request('methode') == 'Flooz' ? 'selected' : '' }}>Flooz</option>
                        <option value="Mix by Yas" {{ request('methode') == 'Mix by Yas' ? 'selected' : '' }}>Mix by Yas
                        </option>
                        <option value="Visa" {{ request('methode') == 'Visa' ? 'selected' : '' }}>Visa</option>
                    </select>
                </div>

                <button type="submit"
                    class="bg-gray-800 text-white px-6 py-2 rounded-lg text-sm hover:bg-black transition">Filtrer</button>
                @if (request()->anyFilled(['search', 'methode']))
                    <a href="{{ route('admin.paiements.index') }}"
                        class="text-gray-400 hover:text-red-500 text-sm pb-2">Effacer</a>
                @endif
            </form>
        </div>

        <div class="bg-white shadow-sm rounded-xl overflow-hidden border border-blue-400">
            <table class="min-w-full text-left">
                <thead>
                    <tr class="bg-gray-50 text-blue-500 text-xs uppercase tracking-wider border-b">
                        <th class="py-4 px-6 font-bold">Client</th>
                        <th class="py-4 px-6 font-bold">Référence</th>
                        <th class="py-4 px-6 font-bold">Date</th>
                        <th class="py-4 px-6 font-bold">Montant</th>
                        <th class="py-4 px-6 font-bold">Moyen de paiement</th>
                        <th class="py-4 px-6 text-center font-bold">Status</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm">
                    @forelse($paiements as $paiement)
                        <tr class="border-b border-gray-50 hover:bg-gray-50 transition">
                            <td class="py-4 px-6">
                                <span class="font-medium text-gray-800">{{ $paiement->user->nom }}
                                    {{ $paiement->user->prenom }}</span>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-mono text-[10px] bg-gray-100 text-gray-500 px-2 py-1 rounded border">
                                    {{ $paiement->reference_transaction }}
                                </span>
                            </td>
                            <td class="py-4 px-6 text-gray-500">
                                {{ $paiement->created_at->translatedFormat('d F, Y') }}
                            </td>
                            <td class="py-4 px-6 font-semibold text-gray-700">
                                {{ number_format($paiement->montant, 0, ',', '.') }} FCFA
                            </td>
                            <td class="py-4 px-6">
                                @switch(strtolower($paiement->methode))
                                    @case('flooz')
                                        <span class="text-orange-500 font-medium">Flooz</span>
                                    @break

                                    @case('mix by yas')
                                        <span class="text-blue-400 font-medium">Mix by Yas</span>
                                    @break

                                    @case('visa')
                                        <span class="text-blue-700 font-medium"><i class="fab fa-cc-visa"></i> Visa</span>
                                    @break

                                    @default
                                        <span class="text-gray-600 font-medium">
                                            <i class="fas fa-cash-register mr-1"></i> {{ $paiement->methode_paiement ?? 'Guichet' }}
                                        </span>
                                        
                                @endswitch
                            </td>
                            <td class="py-4 px-6 text-center">
                                <span
                                    class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-1 rounded-full uppercase">Success</span>
                            </td>
                        </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-10 text-center text-gray-400 italic">Aucune transaction trouvée.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $paiements->appends(request()->input())->links() }}
            </div>
        </div>

        <script>
            const ctx = document.getElementById('methodChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Guichet', 'Flooz', 'Mix', 'Visa'],
                    datasets: [{
                        data: [
                            {{ $statsMethodes->where('methode_paiement', 'Guichet')->first()->total ?? 0 }},
                            {{ $statsMethodes->where('methode_paiement', 'Flooz')->first()->total ?? 0 }},
                            {{ $statsMethodes->where('methode_paiement', 'Mix by Yas')->first()->total ?? 0 }},
                            {{ $statsMethodes->where('methode_paiement', 'Visa')->first()->total ?? 0 }}
                        ], // Remplacez par vos variables $statsMethodes si dispo
                        backgroundColor: ['#e2e8f0', '#f97316', '#60a5fa', '#1d4ed8'],
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        </script>
    @endsection
