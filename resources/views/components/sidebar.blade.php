<aside class="sidebar">
    <div class="top-section">
        <div class="logo">
            <img src="{{ asset('assets/images/nCFd0q.jpg') }}" alt="Logo"> Microsass
        </div>
        <div class="toggle_menu">
            <i class="fas fa-bars"></i>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="menu-header">MENU</div>
        <ul>
            <li class="active">
                <a href="/"><i class="fas fa-tachometer-alt"></i> <span>Dashboard</span></a>
            </li>
            <li><a href="#"><i class="fas fa-user-circle"></i> <span>Compte Client</span></a></li>
            <li><a href="#"><i class="fas fa-exchange-alt"></i> <span>Transaction</span></a></li>
            <li><a href="#"><i class="fas fa-credit-card"></i> <span>Paiement</span></a></li>
            <li><a href="#"><i class="fas fa-hand-holding-usd"></i> <span>Demande de prêt</span></a></li>
            <li><a href="#"><i class="fas fa-history"></i> <span>Historique</span></a></li>
            <li><a href="#"><i class="fas fa-chart-line"></i> <span>Suivi</span></a></li>
            <li><a href="#"><i class="fas fa-money-check-alt"></i> <span>Prêt</span></a></li>
            <li><a href="#"><i class="fas fa-undo"></i> <span>Remboursement</span></a></li>
            <li><a href="#"><i class="fas fa-users"></i> <span>Utilisateurs</span></a></li>
            <li><a href="#"><i class="fas fa-file-invoice-dollar"></i> <span>Rapports</span></a></li>
            <li><a href="#"><i class="fas fa-bell"></i> <span>Notifications</span></a></li>
            
            <li class="vertical-dropdown" onclick="toggleVerticalDropdown(this)">
                <a href="javascript:void(0);">
                    <i class="fas fa-th sidebar-icon-grid"></i> 
                    <span>Application</span>
                    <i class="fas fa-chevron-down arrow-indicator"></i>
                </a>
                
                <ul class="vertical-submenu">
                    <li><a href="#"><i class="fas fa-envelope"></i> Email</a></li>
                    <li><a href="#"><i class="fas fa-comments"></i> Messagerie</a></li>
                    <li><a href="#"><i class="fas fa-tasks"></i> Gestionnaire des fichiers</a></li>
                    <li><a href="#"><i class="fas fa-calendar-alt"></i> Calendrier</a></li>
                    <li><a href="#"><i class="fas fa-book"></i> Agenda</a></li>
                </ul>
            </li>
            </ul>
        
        <div class="menu-header general">GENERAL</div>
        <ul>
            <li class="vertical-dropdown" onclick="toggleVerticalDropdown(this)">
                <a href="javascript:void(0);">
                    <i class="fas fa-chart-bar"></i> 
                    <span>Statistique</span>
                    <i class="fas fa-chevron-down arrow-indicator"></i>
                </a>
                
                <ul class="vertical-submenu">
                    <li><a href="#">Rapports Annuels</a></li>
                    <li><a href="#">Rapports Mensuels</a></li>
                </ul>
            </li>
            <li><a href="#"><i class="fas fa-cog"></i> <span>Settings</span></a></li>
            <li class="logout"><a href="#"><i class="fas fa-sign-out-alt"></i> <span>Déconnexion</span></a></li>
        </ul>
    </nav>

    <div class="user-info">
        <img src="{{ asset('assets/images/Roronoa-Zoro-Wanted-Poster-7-scaled.webp') }}" alt="Profile Picture" class="profile-pic">
        <div class="username-details">
            <div class="username">Raymond</div>
            <div class="email">raymond@gmail.com</div>
        </div>
    </div>
</aside>
