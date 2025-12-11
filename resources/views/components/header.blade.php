 <header class="topbar">
            <div class="title-section">
                <div class="main-title">
                    <h1>Microsass</h1>
                    <small>04 Dec 2025</small>
                </div>
                <h2 class="subtitle">Dashboard</h2> 
            </div>
            <div class="topbar-actions">
                <div class="email">
                    <a href="mailto:raymond@gmail.com" style="color: inherit;"><i class="fas fa-envelope"></i></a>
                </div>
                            
            <div class="dropdown-notification" id="notificationToggle">
                {{-- 1. Icône de notification --}}
                <i class="fas fa-bell"></i>

                {{-- 2. Badge de compteur dynamique --}}
                @php
                    // On récupère les notifications non lues de l'utilisateur connecté
                    $unreadNotifications = Auth::user()->unreadNotifications;
                @endphp

                @if ($unreadNotifications->count() > 0)
                    <span class="notification-badge">{{ $unreadNotifications->count() }}</span>
                @endif
                
                {{-- 3. Liste déroulante des notifications --}}
                <ul class="notification-list" id="notificationMenu">
                    
                    @forelse ($unreadNotifications->take(5) as $notification)
                        {{-- Chaque notification est un lien cliquable --}}
                        <li>
                            {{-- L'URL combine le lien vers le détail (data['url']) et marque comme lu (markAsRead) --}}
                            <a href="{{ route('admin.notifications.read', ['id' => $notification->id, 'redirect' => $notification->data['url']]) }}" class="dropdown-item d-flex align-items-center">
                                
                                {{-- Affichage du contenu de la notification stockée dans 'data' --}}
                                <div class="notification-message">
                                    {{-- Type et Nom du client --}}
                                    <span class="font-weight-bold">
                                        {{ $notification->data['type'] }} ({{ $notification->data['client_name'] }})
                                    </span> 
                                    <br>
                                    {{-- Montant et ID --}}
                                    <small class="text-muted">
                                        Demande #{{ $notification->data['demande_id'] }} - {{ $notification->data['montant'] }}
                                    </small>
                                </div>

                                {{-- Heure de la notification --}}
                                <div class="notification-time ms-auto">
                                    {{ $notification->created_at->diffForHumans() }}
                                </div>
                            </a>
                        </li>
                    @empty
                        {{-- Message s'il n'y a pas de notifications --}}
                        <li>
                            <div style="padding: 10px; text-align: center;">Aucune nouvelle notification.</div>
                        </li>
                    @endforelse

                    {{-- Lien pour voir toutes les notifications --}}
                    <li style="text-align: center; font-weight: bold; padding: 8px; border-top: 1px solid #eee; background-color: #f0f2f5;">
                        <a href="{{ route('admin.notifications.index') }}" class="text-decoration-none">
                            Voir toutes les notifications ({{ Auth::user()->notifications->count() }} totales)
                        </a>
                    </li>
                </ul>
            </div>
                <div class="search-section">
                    <i class="fas fa-search"></i>
                    <input type="text" placeholder="Search...">
                </div>
            </div>
        </header>

