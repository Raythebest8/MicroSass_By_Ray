<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport d'Activité Officiel - {{ $debutSemaine->format('d/m/Y') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Cette règle CSS cache les éléments marqués 'no-print' lors de l'impression réelle */
        @media print {
            .no-print { 
                display: none !important; 
            }
            body { 
                background: white; 
                padding: 0; 
            }
            .print-container { 
                box-shadow: none !important; 
                border: none !important; 
                width: 100% !important; 
                max-width: 100% !important;
                margin: 0 !important;
            }
        }
    </style>
</head>
<body class="bg-gray-100 p-8">

    <div class="print-container max-w-4xl mx-auto bg-white p-12 shadow-lg border-t-8 border-indigo-600">
        
        <div class="no-print mb-8 flex justify-end">
            <button onclick="window.print()" class="bg-indigo-600 text-white px-8 py-3 rounded-full shadow-lg hover:bg-indigo-700 transition flex items-center gap-2 font-bold">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                </svg>
                Lancer l'impression du rapport
            </button>
        </div>

        <div class="flex justify-between items-start mb-10 border-b pb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 uppercase">Rapport de Situation</h1>
                <p class="text-indigo-600 font-bold tracking-widest text-sm uppercase">Direction des Engagements Financiers</p>
            </div>
            <div class="text-right">
                <p class="text-sm font-bold text-gray-800 italic font-mono">Période du {{ $debutSemaine->format('d/m/Y') }}</p>
                <p class="text-sm font-bold text-gray-800 italic font-mono">au {{ $finSemaine->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 mb-12">
            <div>
                <h2 class="text-lg font-bold text-gray-800 mb-4 border-l-4 border-indigo-600 pl-3 uppercase tracking-tight">I. Analyse du Volume Prêté</h2>
                <div class="space-y-4">
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-xs text-blue-600 font-bold uppercase">Entreprises</p>
                        <p class="text-xl font-black text-blue-900">{{ number_format($volumeEntreprises, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <p class="text-xs text-purple-600 font-bold uppercase">Particuliers</p>
                        <p class="text-xl font-black text-purple-900">{{ number_format($volumeParticuliers, 0, ',', ' ') }} FCFA</p>
                    </div>
                    <div class="border-t pt-2 flex justify-between px-2 font-bold text-gray-900 text-lg">
                        <span>TOTAL ACCORDÉ</span>
                        <span>{{ number_format($volumePrete, 0, ',', ' ') }} FCFA</span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col items-center justify-center">
                <div style="width: 200px; height: 200px;">
                    <canvas id="repartitionChart"></canvas>
                </div>
                <p class="text-[10px] text-gray-400 mt-4 italic font-medium">Répartition en % du volume total</p>
            </div>
        </div>

        <div class="mb-12">
            <h2 class="text-lg font-bold text-gray-800 mb-4 border-l-4 border-indigo-600 pl-3 uppercase tracking-tight">II. Journal des Encaissements</h2>
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-gray-800 text-white italic">
                        <th class="py-2 px-4 text-left border border-gray-700">Date</th>
                        <th class="py-2 px-4 text-left border border-gray-700">Désignation</th>
                        <th class="py-2 px-4 text-right border border-gray-700">Montant</th>
                    </tr>
                </thead>
                <tbody class="divide-y border">
                    @forelse($paiements as $p)
                    <tr>
                        <td class="py-2 px-4 border text-gray-600 font-mono">{{ $p->created_at->format('d/m/Y') }}</td>
                        <td class="py-2 px-4 border font-medium">Versement écheance via {{ $p->methode_paiement }}</td>
                        <td class="py-2 px-4 border text-right text-green-700 font-bold">+ {{ number_format($p->montant, 0, ',', ' ') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="3" class="py-6 text-center text-gray-400 italic">Aucune transaction enregistrée sur cette période</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr class="bg-gray-50 font-black">
                        <td colspan="2" class="py-3 px-4 text-right border uppercase">Cumul des Recettes (Période)</td>
                        <td class="py-3 px-4 text-right border text-green-700 text-lg">{{ number_format($encaissements, 0, ',', ' ') }} FCFA</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="mt-16 border-t pt-8">
            <h2 class="text-lg font-bold text-gray-800 mb-6 uppercase tracking-tight">III. Certification de l'Autorité</h2>
            <div class="grid grid-cols-2 gap-12">
                <div class="border-2 border-dashed border-gray-200 p-4 rounded-lg h-36 bg-gray-50/30">
                    <p class="text-[10px] uppercase font-bold text-gray-400 mb-2 underline italic text-center">Observations</p>
                </div>
                <div class="flex flex-col items-center">
                    <div class="bg-gray-50 border p-6 rounded-lg w-full flex flex-col items-center min-h-[144px] justify-between">
                        <p class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.2em]">Signature & Cachet Officiel</p>
                        <div class="w-full flex flex-col items-center">
                            <div class="w-40 border-b border-gray-400 mb-2"></div>
                            <p class="text-[10px] text-gray-400 italic font-mono">Fait à Lomé, le {{ now()->format('d/m/Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-12 pt-6 border-t flex justify-between items-end italic text-[9px] text-gray-400">
            <p>Document numérique à valeur probante comptable interne.<br>© {{ date('Y') }} Service de Gestion des Prêts.</p>
            <p class="font-mono bg-gray-100 px-2 py-1 rounded">DOC-ID: {{ strtoupper(Str::random(12)) }}</p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('repartitionChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: ['Entreprises', 'Particuliers'],
                    datasets: [{
                        data: [{{ $volumeEntreprises }}, {{ $volumeParticuliers }}],
                        backgroundColor: ['#1e40af', '#7e22ce'],
                        borderColor: '#ffffff',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'bottom',
                            labels: {
                                font: { size: 10, weight: 'bold' },
                                boxWidth: 12
                            }
                        }
                    }
                }
            });
        });
    </script>
</body>
</html>