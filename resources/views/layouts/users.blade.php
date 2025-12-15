<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord MicroSaaS -  Client</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    
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
<body class="flex h-screen overflow-hidden bg-slate-50 dark:bg-gray-900 ">

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
<main class="flex-1 overflow-y-auto p-0 bg-slate-50 dark:bg-gray-900 relative custom-scroll">
    
    
    <div id="view-dashboard" class="fade-in block">
         <div class="sticky top-0 z-10 bg-slate-50 dark:bg-gray-900 pt-0 pb-0 mb-0 border-b border-gray-100 dark:border-gray-700 px-4 md:px-8">
            <div class="pt-4 md:pt-4 pb-4 flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-800 dark:text-white">Tableau de Bord</h2>
                    <p class="text-gray-500 mt-1 dark:text-gray-400">Bienvenue, <span id="welcome-name">Raymond</span> ! Voici votre situation financière.</p>
                </div>

                <div class="device-theme-toggle flex items-center">
                    <button id="toggle-theme-btn" onclick="toggleTheme()" class="mt-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-700 dark:text-gray-200 px-3 py-1 rounded-lg text-sm transition-colors">
                        🌙 </button>
                </div>
            </div>
        </div>

            @yield('content')


    </div>
    
    @yield('modals')

</main>
<script>
    // Clé utilisée pour stocker la préférence de l'utilisateur
    const THEME_KEY = 'theme-preference';

    // Fonction principale de bascule du thème
    function toggleTheme() {
        const root = document.documentElement;
        const button = document.getElementById('toggle-theme-btn');
        
        // 1. Bascule la classe 'dark'
        root.classList.toggle('dark');

        // 2. Met à jour localStorage et l'icône
        const isDark = root.classList.contains('dark');
        
        if (isDark) {
            localStorage.setItem(THEME_KEY, 'dark');
            if (button) button.innerHTML = '☀️'; // Soleil en mode sombre
        } else {
            localStorage.setItem(THEME_KEY, 'light');
            if (button) button.innerHTML = '🌙'; // Lune en mode clair
        }
    }
    
    // Initialisation au chargement (Récupère la préférence utilisateur)
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.documentElement;
        const savedTheme = localStorage.getItem(THEME_KEY);
        const button = document.getElementById('toggle-theme-btn');

        // Applique le thème sauvegardé
        if (savedTheme === 'dark') {
            root.classList.add('dark');
        } 
        // Si aucune préférence n'est enregistrée, ou si c'est 'light',
        // on peut ajouter une logique pour le thème du système ici (matchMedia), 
        // mais nous nous en tiendrons au comportement actuel pour la simplicité.

        // Met à jour l'icône après l'initialisation
        if (button) {
            if (root.classList.contains('dark')) {
                button.innerHTML = '☀️';
            } else {
                button.innerHTML = '🌙';
            }
        }
    });
</script>

   
</body>
</html>