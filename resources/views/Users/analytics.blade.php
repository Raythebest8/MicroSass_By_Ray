@extends('layouts.users')

@section('content')

<div class="max-w-6xl mx-auto my-8 p-4 md:p-8">

    <h2 class="text-3xl font-bold mb-8 text-gray-900 dark:text-white">
        Analyse Financière & Progression des Prêts
    </h2>

    <hr class="mb-8 border-gray-200 dark:border-gray-700">

    <h3 class="text-xl font-semibold mb-4 text-indigo-600 dark:text-indigo-400">Résumé des Engagements</h3>
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
        
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                <i class="fas fa-hand-holding-usd mr-2 text-indigo-500"></i> Total Emprunté
            </div>
            <div id="stat-total" class="text-3xl font-extrabold mt-2 text-indigo-600 dark:text-white">...</div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                <i class="fas fa-check-circle mr-2 text-emerald-500"></i> Total Remboursé
            </div>
            <div id="stat-paid" class="text-3xl font-extrabold mt-2 text-emerald-600 dark:text-emerald-400">...</div>
        </div>
        
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400 flex items-center">
                <i class="fas fa-clock mr-2 text-amber-500"></i> Prochain Paiement
            </div>
            <div class="text-3xl font-extrabold mt-2 text-amber-600 dark:text-amber-400">970 €</div>
            <p class="text-xs text-gray-500 mt-1">20 Oct 2025</p>
        </div>
    </div>

    <hr class="mb-8 border-gray-200 dark:border-gray-700">

    <h3 class="text-xl font-semibold mb-4 text-indigo-600 dark:text-indigo-400">Indicateurs de Performance et Historique</h3>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
            <h4 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Progression Totale du Portefeuille</h4>
            <div class="h-64">
                <canvas id="globalProgressChart"></canvas>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
            <h4 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Évolution du Score de Crédit (Historique)</h4>
            <div class="h-64">
                <canvas id="creditScoreChart"></canvas>
            </div>
        </div>

        <div class="lg:col-span-2 bg-white dark:bg-gray-800 p-6 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
            <h4 class="text-lg font-semibold mb-4 text-gray-800 dark:text-gray-100">Total des Paiements Mensuels Récurrents</h4>
            <div class="h-80">
                <canvas id="monthlyExpensesChart"></canvas>
            </div>
        </div>

    </div>

</div>

<script>
    // --- DATA STORE ---
    // Les données réelles proviendraient de Laravel via Blade ou une API, mais voici la simulation :
    const userData = {
        name: "Raymond",
        lastname: "Kokodako",
        role: "user",
        loans: [
            { id: "L001", name: "Prêt Immobilier", total: 120000, paid: 45000, status: "active", rate: "3.5%", monthly: 650, nextDue: "15 Oct 2025" },
            { id: "L002", name: "Prêt Personnel", total: 15000, paid: 8450, status: "active", rate: "5.2%", monthly: 320, nextDue: "20 Oct 2025" },
            { id: "L003", name: "Crédit Auto", total: 25000, paid: 25000, status: "completed", rate: "4.0%", monthly: 0, nextDue: "-" }
        ],
        creditHistory: [650, 660, 680, 675, 710, 725], // Last 6 months
        expensesHistory: [970, 970, 970, 970, 970, 970] // Total monthly payments
    };

    // --- STATE MANAGEMENT ---
    let charts = {}; // Store chart instances

    // --- INITIALIZATION ---
    document.addEventListener('DOMContentLoaded', () => {
        // Un petit délai est souvent nécessaire pour Chart.js, surtout si les conteneurs sont masqués initialement.
        setTimeout(() => {
            updateUI();
            initCharts();
        }, 100); 
    });

    // --- UI UPDATES ---
    function updateUI() {
        // Calculate totals from loans
        const totalBorrowed = userData.loans.reduce((acc, loan) => acc + loan.total, 0);
        const totalPaid = userData.loans.reduce((acc, loan) => acc + loan.paid, 0);

        // Update text elements
        // La vérification ?. (optional chaining) est ajoutée pour la sécurité
        document.getElementById('stat-total')?.innerText = formatCurrency(totalBorrowed);
        document.getElementById('stat-paid')?.innerText = formatCurrency(totalPaid);
    }

    function formatCurrency(value) {
        // Formatage en EUR (ajustez à XOF si nécessaire pour Fcfa)
        return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(value);
    }

    // --- CHART LOGIC ---
    function initCharts() {
        // Destruction des anciens charts pour éviter les conflits si initCharts est appelé plusieurs fois
        Object.keys(charts).forEach(key => {
            if (charts[key]) {
                charts[key].destroy();
                delete charts[key];
            }
        });

        // 1. Global Progress (Doughnut)
        const ctxGlobal = document.getElementById('globalProgressChart')?.getContext('2d');
        if (ctxGlobal) {
            const totalBorrowed = userData.loans.reduce((acc, loan) => acc + loan.total, 0);
            const totalPaid = userData.loans.reduce((acc, loan) => acc + loan.paid, 0);
            const totalRemaining = totalBorrowed - totalPaid;

            charts.global = new Chart(ctxGlobal, {
                type: 'doughnut',
                data: {
                    labels: ['Remboursé', 'Restant'],
                    datasets: [{
                        data: [totalPaid, totalRemaining],
                        backgroundColor: ['#10B981', '#E2E8F0'], // Emerald-500, Gray-200
                        hoverBackgroundColor: ['#059669', '#CBD5E1'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 20 } },
                        tooltip: { 
                            callbacks: { 
                                label: function(context) {
                                    let label = context.label || '';
                                    if (label) label += ': ';
                                    label += formatCurrency(context.raw);
                                    return label;
                                }
                            }
                        }
                    }
                }
            });
        }

        // 2. Credit Score (Line)
        const ctxScore = document.getElementById('creditScoreChart')?.getContext('2d');
        if (ctxScore) {
             charts.score = new Chart(ctxScore, {
                type: 'line',
                data: {
                    labels: ['Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct'],
                    datasets: [{
                        label: 'Score',
                        data: userData.creditHistory,
                        borderColor: '#4F46E5', // Indigo-600
                        backgroundColor: 'rgba(79, 70, 229, 0.1)',
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: '#fff',
                        pointBorderColor: '#4F46E5',
                        pointRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { min: 500, max: 850, grid: { borderDash: [2, 2] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // 3. Monthly Expenses (Bar)
        const ctxExpenses = document.getElementById('monthlyExpensesChart')?.getContext('2d');
        if (ctxExpenses) {
             charts.expenses = new Chart(ctxExpenses, {
                type: 'bar',
                data: {
                    labels: ['Mai', 'Juin', 'Juil', 'Août', 'Sept', 'Oct'],
                    datasets: [{
                        label: 'Total Paiements',
                        data: userData.expensesHistory,
                        backgroundColor: '#F59E0B', // Amber-500
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { borderDash: [2, 2] } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    }
    
    // Les fonctions router(), renderLoanTable(), showLoanDetails(), etc. ne sont pas nécessaires
    // dans cette vue spécifique, mais vous pouvez les conserver dans un fichier JS global
    // si elles sont utilisées ailleurs dans votre tableau de bord.

</script>

@endsection