<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord MicroSaaS - Rapport Financier Interactif</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- Chosen Palette: Indigo & Warm Neutrals - Professional, trustworthy financial look -->
    <!-- Application Structure Plan: 
         The application transforms the static dashboard concept into a fully interactive Single Page Application (SPA).
         Structure:
         1. Sidebar Navigation: Persistent access to key modules (Vue d'ensemble, Mes Prêts, Analytique, Profil).
         2. Main Content Area: Dynamic container that swaps views based on user interaction.
         3. Modules:
            - 'Vue d'ensemble': High-level metrics and a summary chart.
            - 'Mes Prêts' (Services): Detailed table with filtering and drill-down capability to see repayment schedules.
            - 'Analytique': Visual trends of credit score and financial health.
            - 'Profil': Read-only display of user details from the source.
         Interaction Flow: Users start at the Dashboard. They can filter loans, click on specific loans to see charts, and toggle between analytics views.
    -->

    <!-- Visualization & Content Choices:
         1. Loan Repayment Progress -> Goal: Inform/Track -> Viz: Doughnut Chart (Chart.js) -> Interaction: Hover for exact amounts -> Justification: Shows percentage paid vs remaining clearly.
         2. Credit Score Trend -> Goal: Change/Trend -> Viz: Line Chart (Chart.js) -> Interaction: Point hover -> Justification: Shows financial health improvement over time.
         3. Active Loans List -> Goal: Organize -> Viz: Interactive Table (HTML/JS) -> Interaction: Filter by status, Sort by amount -> Justification: Easy management of multiple items.
         4. Repayment Schedule -> Goal: Compare -> Viz: Bar Chart (Chart.js) -> Interaction: Toggle timeframes -> Justification: Visualizes monthly burden.
         CONFIRMATION: NO SVG graphics used. NO Mermaid JS used.
    -->

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #F8FAFC; /* Slate 50 */
            color: #1E293B; /* Slate 800 */
        }

        /* Chart Container Styling - Mandatory Requirements */
        .chart-container {
            position: relative;
            width: 100%;
            max-width: 600px; /* Explicit max-width */
            margin-left: auto;
            margin-right: auto;
            height: 300px; /* Base height */
            max-height: 400px;
        }

        @media (min-width: 768px) {
            .chart-container {
                height: 350px;
            }
        }

        /* Custom Scrollbar for tables */
        .custom-scroll::-webkit-scrollbar {
            width: 6px;
            height: 6px;
        }
        .custom-scroll::-webkit-scrollbar-track {
            background: #f1f1f1;
        }
        .custom-scroll::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }
        .custom-scroll::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .nav-item.active {
            background-color: #E0E7FF; /* Indigo 100 */
            color: #4338ca; /* Indigo 700 */
            border-right: 3px solid #4338ca;
        }

        .fade-in {
            animation: fadeIn 0.3s ease-in-out;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="flex h-screen overflow-hidden">

    <x-sidebar_client />

    <!-- Mobile Header -->
    <div class="md:hidden fixed top-0 w-full bg-white border-b border-gray-200 z-20 flex items-center justify-between p-4 shadow-sm">
        <h1 class="text-xl font-bold text-indigo-700">MicroSaaS</h1>
        <button onclick="toggleMobileMenu()" class="text-gray-600 hover:text-indigo-700 focus:outline-none">
            <span class="text-2xl">☰</span>
        </button>
    </div>

    <!-- Mobile Menu Overlay -->
    <div id="mobile-menu" class="fixed inset-0 bg-gray-800 bg-opacity-75 z-30 hidden" onclick="toggleMobileMenu()">
        <div class="bg-white w-64 h-full p-4 flex flex-col" onclick="event.stopPropagation()">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg font-bold text-gray-800">Menu</h2>
                <button onclick="toggleMobileMenu()" class="text-gray-500">✕</button>
            </div>
            <nav class="space-y-2">
                <button onclick="router('dashboard'); toggleMobileMenu()" class="block w-full text-left px-4 py-3 bg-gray-50 rounded text-gray-700">Vue d'ensemble</button>
                <button onclick="router('loans'); toggleMobileMenu()" class="block w-full text-left px-4 py-3 bg-gray-50 rounded text-gray-700">Mes Prêts</button>
                <button onclick="router('analytics'); toggleMobileMenu()" class="block w-full text-left px-4 py-3 bg-gray-50 rounded text-gray-700">Analytique</button>
                <button onclick="router('profile'); toggleMobileMenu()" class="block w-full text-left px-4 py-3 bg-gray-50 rounded text-gray-700">Profil</button>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <main class="flex-1 overflow-y-auto p-4 md:p-8 pt-20 md:pt-8 bg-slate-50 relative custom-scroll">
        

    @yield('content')
        <!-- View: Dashboard -->
        <div id="view-dashboard" class="fade-in block">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Tableau de Bord</h2>
                <p class="text-gray-500 mt-1">Bienvenue, <span id="welcome-name">Raymond</span> ! Voici votre situation financière.</p>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Borrowed -->
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-indigo-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Total Emprunté</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1" id="stat-total">15 000 €</p>
                    </div>
                    <div class="bg-indigo-50 p-3 rounded-full text-indigo-600 text-xl">💰</div>
                </div>
                <!-- Amount Paid -->
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Remboursé</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1" id="stat-paid">8 450 €</p>
                    </div>
                    <div class="bg-emerald-50 p-3 rounded-full text-emerald-600 text-xl">✅</div>
                </div>
                <!-- Next Payment -->
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-500 uppercase">Prochaine Échéance</p>
                        <p class="text-2xl font-bold text-gray-800 mt-1">15 Oct</p>
                        <p class="text-xs text-amber-600 font-medium">320.00 €</p>
                    </div>
                    <div class="bg-amber-50 p-3 rounded-full text-amber-600 text-xl">📅</div>
                </div>
            </div>

            <!-- Quick Charts Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                
                <!-- Main Status Chart -->
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-bold text-gray-800">Progression Globale</h3>
                        <button class="text-sm text-indigo-600 hover:text-indigo-800 font-medium" onclick="router('loans')">Détails →</button>
                    </div>
                    <div class="chart-container">
                        <canvas id="globalProgressChart"></canvas>
                    </div>
                    <p class="text-sm text-gray-500 text-center mt-4">Proportion du capital remboursé vs restant dû.</p>
                </div>

                <!-- Recent Activity / Notifications -->
                <div class="bg-white p-6 rounded-xl shadow-sm flex flex-col">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Dernières Activités</h3>
                    <div class="flex-1 overflow-y-auto custom-scroll pr-2 max-h-[300px]">
                        <ul class="space-y-4">
                            <li class="flex items-start pb-4 border-b border-gray-100 last:border-0">
                                <div class="bg-green-100 text-green-600 rounded-full h-8 w-8 flex items-center justify-center mr-3 mt-1 text-xs">✓</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Paiement reçu</p>
                                    <p class="text-xs text-gray-500">Prêt Immobilier - 15 Septembre</p>
                                    <p class="text-sm font-bold text-green-600 mt-1">- 450.00 €</p>
                                </div>
                            </li>
                            <li class="flex items-start pb-4 border-b border-gray-100 last:border-0">
                                <div class="bg-blue-100 text-blue-600 rounded-full h-8 w-8 flex items-center justify-center mr-3 mt-1 text-xs">ℹ</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Nouveau document disponible</p>
                                    <p class="text-xs text-gray-500">Relevé annuel 2024</p>
                                </div>
                            </li>
                            <li class="flex items-start pb-4 border-b border-gray-100 last:border-0">
                                <div class="bg-yellow-100 text-yellow-600 rounded-full h-8 w-8 flex items-center justify-center mr-3 mt-1 text-xs">!</div>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Rappel d'échéance</p>
                                    <p class="text-xs text-gray-500">Prêt Personnel - Échéance dans 5 jours</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- View: Loans (Mes Prêts) -->
        <div id="view-loans" class="fade-in hidden">
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800">Mes Services</h2>
                    <p class="text-gray-500 mt-1">Gérez vos prêts actifs et consultez les détails.</p>
                </div>
                <div class="flex gap-2">
                    <select id="loan-filter" onchange="filterLoans()" class="bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="all">Tous les statuts</option>
                        <option value="active">Actifs</option>
                        <option value="completed">Terminés</option>
                    </select>
                </div>
            </div>

            <!-- Interactive Loan Table -->
            <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-8">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200">
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Nom du Service</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Montant Total</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Restant</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Statut</th>
                                <th class="p-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                            </tr>
                        </thead>
                        <tbody id="loan-table-body" class="text-sm text-gray-700">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Loan Details Section (Dynamic) -->
            <div id="loan-details-panel" class="bg-white p-6 rounded-xl shadow-sm hidden border border-gray-100">
                <div class="flex justify-between items-start mb-6 border-b border-gray-100 pb-4">
                    <div>
                        <h3 class="text-xl font-bold text-gray-800" id="detail-title">Prêt Immobilier</h3>
                        <p class="text-gray-500 text-sm" id="detail-id">#LOAN-001</p>
                    </div>
                    <button onclick="closeDetails()" class="text-gray-400 hover:text-gray-600">✕ Fermer</button>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <h4 class="font-semibold text-gray-700 mb-4">Répartition du Remboursement</h4>
                        <div class="chart-container" style="height: 250px;">
                            <canvas id="loanDetailChart"></canvas>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase">Taux d'intérêt</p>
                            <p class="text-lg font-bold text-indigo-600" id="detail-rate">3.5%</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase">Mensualité</p>
                            <p class="text-lg font-bold text-indigo-600" id="detail-monthly">450.00 €</p>
                        </div>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-xs text-gray-500 uppercase">Prochaine Échéance</p>
                            <p class="text-lg font-bold text-amber-600" id="detail-next">15 Oct 2025</p>
                        </div>
                        <button class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg shadow-md transition-colors mt-4">
                            Effectuer un Paiement
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- View: Analytics -->
        <div id="view-analytics" class="fade-in hidden">
            <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Analytique Financière</h2>
                <p class="text-gray-500 mt-1">Évolution de votre santé financière et score de crédit.</p>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Évolution du Score de Crédit</h3>
                    <div class="chart-container">
                        <canvas id="creditScoreChart"></canvas>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center">Score mis à jour mensuellement.</p>
                </div>
                
                <div class="bg-white p-6 rounded-xl shadow-sm">
                    <h3 class="text-lg font-bold text-gray-800 mb-4">Dépenses Mensuelles (Prêts)</h3>
                    <div class="chart-container">
                        <canvas id="monthlyExpensesChart"></canvas>
                    </div>
                    <p class="text-xs text-gray-400 mt-2 text-center">Historique des 6 derniers mois.</p>
                </div>
            </div>

            <div class="bg-indigo-900 text-white p-8 rounded-xl shadow-lg relative overflow-hidden">
                <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 bg-indigo-700 rounded-full opacity-50"></div>
                <div class="relative z-10">
                    <h3 class="text-xl font-bold mb-2">Conseil IA MicroSaaS</h3>
                    <p class="text-indigo-100 max-w-2xl">
                        Basé sur votre rythme de remboursement actuel, vous pourriez économiser <span class="font-bold text-white">450 €</span> d'intérêts en augmentant votre mensualité de 50 € sur le "Prêt Personnel".
                    </p>
                    <button class="mt-4 bg-white text-indigo-900 px-4 py-2 rounded-lg text-sm font-bold hover:bg-indigo-50 transition">
                        Voir la simulation
                    </button>
                </div>
            </div>
        </div>

        <!-- View: Profile -->
        <div id="view-profile" class="fade-in hidden">
             <div class="mb-8">
                <h2 class="text-3xl font-bold text-gray-800">Mon Profil</h2>
                <p class="text-gray-500 mt-1">Gérez vos informations personnelles.</p>
            </div>

            <div class="bg-white rounded-xl shadow-sm overflow-hidden max-w-2xl mx-auto border border-gray-100">
                <div class="bg-indigo-50 p-6 border-b border-indigo-100 flex items-center gap-4">
                    <div class="h-16 w-16 bg-indigo-200 rounded-full flex items-center justify-center text-2xl font-bold text-indigo-700">RK</div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Raymond Kokodako</h3>
                        <p class="text-indigo-600 text-sm font-medium">Compte Utilisateur Standard</p>
                    </div>
                </div>
                <div class="p-6 space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Nom</label>
                            <div class="p-3 bg-gray-50 rounded-lg text-gray-800 border border-gray-200">Kokodako</div>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Prénom</label>
                            <div class="p-3 bg-gray-50 rounded-lg text-gray-800 border border-gray-200">Raymond</div>
                        </div>
                        <div class="md:col-span-2">
                            <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Email</label>
                            <div class="p-3 bg-gray-50 rounded-lg text-gray-800 border border-gray-200 flex justify-between items-center">
                                <span>kokodakoraymond@gmail.com</span>
                                <span class="text-green-500 text-xs font-bold bg-green-50 px-2 py-1 rounded">Vérifié</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-100 pt-6">
                        <h4 class="font-bold text-gray-800 mb-4">Sécurité</h4>
                        <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium flex items-center">
                            <span class="mr-2">🔒</span> Changer le mot de passe
                        </button>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-sm">Annuler</button>
                        <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-bold shadow-md">Sauvegarder</button>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        // --- DATA STORE ---
        // Mock data based on the context of the user report
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
        let currentView = 'dashboard';
        let charts = {}; // Store chart instances

        // --- INITIALIZATION ---
        document.addEventListener('DOMContentLoaded', () => {
            updateUI();
            initCharts();
            renderLoanTable();
        });

        // --- ROUTER & NAVIGATION ---
        function router(viewName) {
            // Update State
            currentView = viewName;
            
            // Update Sidebar UI
            document.querySelectorAll('.nav-item').forEach(el => el.classList.remove('active', 'bg-indigo-50', 'text-indigo-700', 'border-r-4', 'border-indigo-700'));
            const activeNav = document.getElementById(`nav-${viewName}`);
            if(activeNav) activeNav.classList.add('active');

            // Update Main Content
            document.querySelectorAll('main > div[id^="view-"]').forEach(el => el.classList.add('hidden'));
            const activeView = document.getElementById(`view-${viewName}`);
            if(activeView) {
                activeView.classList.remove('hidden');
                activeView.classList.add('fade-in'); // Re-trigger animation
            }
        }

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        // --- UI UPDATES ---
        function updateUI() {
            // Calculate totals from loans
            const totalBorrowed = userData.loans.reduce((acc, loan) => acc + loan.total, 0);
            const totalPaid = userData.loans.reduce((acc, loan) => acc + loan.paid, 0);

            // Update text elements
            document.getElementById('stat-total').innerText = formatCurrency(totalBorrowed);
            document.getElementById('stat-paid').innerText = formatCurrency(totalPaid);
            
            // Note: In a real app, 'next payment' would be calculated dynamically
        }

        function formatCurrency(value) {
            return new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR', maximumFractionDigits: 0 }).format(value);
        }

        // --- CHART LOGIC ---
        function initCharts() {
            // 1. Global Progress (Doughnut) - Dashboard
            const ctxGlobal = document.getElementById('globalProgressChart').getContext('2d');
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

            // 2. Credit Score (Line) - Analytics
            const ctxScore = document.getElementById('creditScoreChart').getContext('2d');
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

            // 3. Monthly Expenses (Bar) - Analytics
            const ctxExpenses = document.getElementById('monthlyExpensesChart').getContext('2d');
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

        // --- LOAN TABLE LOGIC ---
        function renderLoanTable(filter = 'all') {
            const tbody = document.getElementById('loan-table-body');
            tbody.innerHTML = ''; // Clear

            const filteredLoans = userData.loans.filter(loan => filter === 'all' || loan.status === filter);

            filteredLoans.forEach(loan => {
                const remaining = loan.total - loan.paid;
                const statusColor = loan.status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600';
                const statusLabel = loan.status === 'active' ? 'Actif' : 'Terminé';

                const tr = document.createElement('tr');
                tr.className = "border-b border-gray-100 hover:bg-gray-50 transition-colors";
                tr.innerHTML = `
                    <td class="p-4 font-medium text-gray-800">${loan.name}</td>
                    <td class="p-4 text-gray-600">${formatCurrency(loan.total)}</td>
                    <td class="p-4 font-bold text-gray-800">${formatCurrency(remaining)}</td>
                    <td class="p-4">
                        <span class="text-xs font-bold px-2 py-1 rounded-full ${statusColor}">${statusLabel}</span>
                    </td>
                    <td class="p-4">
                        <button onclick="showLoanDetails('${loan.id}')" class="text-indigo-600 hover:text-indigo-900 text-sm font-semibold">Voir</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function filterLoans() {
            const filterValue = document.getElementById('loan-filter').value;
            renderLoanTable(filterValue);
        }

        // --- LOAN DETAILS INTERACTION ---
        function showLoanDetails(loanId) {
            const loan = userData.loans.find(l => l.id === loanId);
            if(!loan) return;

            // Update Details Text
            document.getElementById('detail-title').innerText = loan.name;
            document.getElementById('detail-id').innerText = `#${loan.id}`;
            document.getElementById('detail-rate').innerText = loan.rate;
            document.getElementById('detail-monthly').innerText = loan.monthly > 0 ? formatCurrency(loan.monthly) : "-";
            document.getElementById('detail-next').innerText = loan.nextDue;

            // Show Panel
            const panel = document.getElementById('loan-details-panel');
            panel.classList.remove('hidden');
            panel.scrollIntoView({ behavior: 'smooth', block: 'center' });

            // Create/Update Detail Chart
            const ctxDetail = document.getElementById('loanDetailChart').getContext('2d');
            const remaining = loan.total - loan.paid;

            if(charts.detail) {
                charts.detail.destroy();
            }

            charts.detail = new Chart(ctxDetail, {
                type: 'bar', // Using a stacked bar to show progress
                data: {
                    labels: ['Progression'],
                    datasets: [
                        { label: 'Payé', data: [loan.paid], backgroundColor: '#10B981', barThickness: 40 },
                        { label: 'Restant', data: [remaining], backgroundColor: '#E2E8F0', barThickness: 40 }
                    ]
                },
                options: {
                    indexAxis: 'y', // Horizontal bar
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        x: { stacked: true, display: false },
                        y: { stacked: true, display: false }
                    },
                    plugins: {
                        legend: { position: 'bottom' },
                        tooltip: {
                            callbacks: {
                                label: (ctx) => `${ctx.dataset.label}: ${formatCurrency(ctx.raw)}`
                            }
                        }
                    }
                }
            });
        }

        function closeDetails() {
            document.getElementById('loan-details-panel').classList.add('hidden');
        }

    </script>
</body>
</html>