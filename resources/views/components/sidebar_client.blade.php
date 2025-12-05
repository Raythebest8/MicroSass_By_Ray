<aside class="w-64 bg-white border-r border-gray-200 hidden md:flex flex-col z-10 shadow-sm">
    
    <div class="p-6 border-b border-gray-100 flex items-center justify-center">
        <h1 class="text-2xl font-extrabold text-indigo-700 tracking-tight">MicroSaaS</h1>
    </div>
    
    <nav class="flex-1 overflow-y-auto py-4">
        <ul class="space-y-1">
            <li>
                <a href="#dashboard" id="nav-dashboard" class="nav-item active w-full text-left px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors flex items-center group">
                    <span class="mr-3 text-lg">📊</span> Vue d'ensemble
                </a>
            </li>
            
            <li>
                <a href="#simulation" id="nav-simulation" class="nav-item w-full text-left px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors flex items-center group">
                    <span class="mr-3 text-lg">🧮</span> Simulation de Prêt
                </a>
            </li>
            <li>
                <a href="#demande-pret" id="nav-request" class="nav-item w-full text-left px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors flex items-center group">
                    <span class="mr-3 text-lg">📝</span> Demande de Prêt
                </a>
            </li>
            <li>
                <a href="#loans" id="nav-loans" class="nav-item w-full text-left px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors flex items-center group">
                    <span class="mr-3 text-lg">💰</span> Mes Prêts Actifs
                </a>
            </li>
            <li>
                <a href="#echeances" id="nav-due-dates" class="nav-item w-full text-left px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors flex items-center group">
                    <span class="mr-3 text-lg">🗓️</span> Échéances & Paiements
                </a>
            </li>
            <li>
                <a href="#analytics" id="nav-analytics" class="nav-item w-full text-left px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors flex items-center group">
                    <span class="mr-3 text-lg">📈</span> Suivi & Historique
                </a>
            </li>
            <li>
                <a href="#profile" id="nav-profile" class="nav-item w-full text-left px-6 py-3 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors flex items-center group">
                    <span class="mr-3 text-lg">👤</span> Mon Profil
                </a>
            </li>
        </ul>
    </nav>

    <div class="p-4 border-t border-gray-100">
        <div class="flex items-center space-x-3 mb-4">
            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
                <span id="user-initials">{{ Auth::user()->profile_image }}</span>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-700" id="user-name-display"> {{ Auth::user()->prenom }}</p>
                <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
            </div>
        </div>
        <form method="POST" action="{{ route('auth.logout') }}" id="logout-form-bottom">
        @csrf 
        <button type="submit" class="w-full bg-red-50 text-red-600 hover:bg-red-100 py-2 rounded-md text-sm font-medium transition-colors">
            Déconnexion
        </button>
    </form>
    </div>
</aside>
<!-- 
<div class="p-4 border-t border-gray-100">
    <div class="flex items-center space-x-3 mb-4">
        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold">
            <span id="user-initials">{{ substr(Auth::user()->prenom, 0, 1) }}{{ substr(Auth::user()->nom, 0, 1) }}</span>
        </div>
        <div>
            <p class="text-sm font-medium text-gray-700" id="user-name-display">{{ Auth::user()->prenom }} {{ Auth::user()->nom }}</p>
            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
        </div>
    </div>
    
    
</div> -->