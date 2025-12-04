// Fonction de gestion de la réduction/déploiement de la sidebar
function toggleSidebar() {
    const body = document.body;
    const sidebar = document.querySelector('.sidebar');
    
    // Bascule la classe 'collapsed' sur la sidebar
    sidebar.classList.toggle('collapsed');
    
    // Bascule une classe sur le body pour ajuster la grille du contenu principal
    body.classList.toggle('sidebar-collapsed-active'); 

    // Ferme tous les menus accordéon/vertical lorsque la sidebar est réduite
    document.querySelectorAll('.sidebar-nav .vertical-dropdown.open').forEach(activeLi => {
        activeLi.classList.remove('open');
        activeLi.querySelector('.vertical-submenu').style.maxHeight = '0';
    });
}


document.addEventListener('DOMContentLoaded', () => {
    // 1. Attacher le clic au bouton de la sidebar
    const toggleButton = document.querySelector('.toggle_menu');
    
    if (toggleButton) {
        toggleButton.addEventListener('click', toggleSidebar);
    }
    
    
});



    // Fonction pour basculer le menu déroulant vertical
function toggleVerticalDropdown(element) {
    // Le conteneur LI (parent)
    const li = element;
    
    // Le sous-menu UL (enfant)
    const submenu = li.querySelector('.vertical-submenu');

    // 1. Fermer tous les autres dropdowns ouverts dans cette UL
    // Remarque : Si vous voulez que plusieurs menus soient ouverts en même temps, 
    // retirez ce bloc de fermeture des autres menus.
    const allDropdowns = li.closest('ul').querySelectorAll('.vertical-dropdown.open');
    allDropdowns.forEach(activeLi => {
        if (activeLi !== li) {
            activeLi.classList.remove('open');
            activeLi.querySelector('.vertical-submenu').style.maxHeight = '0';
        }
    });

    // 2. Basculer l'état du menu actuel
    li.classList.toggle('open');
    
    if (li.classList.contains('open')) {
        // Ouvre le menu : utilise scrollHeight pour calculer la hauteur nécessaire
        submenu.style.maxHeight = submenu.scrollHeight + 'px'; 
    } else {
        // Ferme le menu
        submenu.style.maxHeight = '0';
    }
}


// Fonction de gestion de la réduction de la sidebar
function toggleSidebar() {
    const body = document.body;
    const sidebar = document.querySelector('.sidebar');
    
    // Bascule la classe 'collapsed' sur la sidebar
    sidebar.classList.toggle('collapsed');
    body.classList.toggle('sidebar-collapsed-active'); // Pour ajuster la grille du contenu principal

    // Ferme tous les menus accordéon/vertical lorsque la sidebar est réduite
    document.querySelectorAll('.sidebar-nav .vertical-dropdown.open').forEach(activeLi => {
        activeLi.classList.remove('open');
        activeLi.querySelector('.vertical-submenu').style.maxHeight = '0';
    });
}


// --- Écouteurs d'événements ---

document.addEventListener('DOMContentLoaded', () => {
    // 1. Attacher le clic au bouton de la sidebar
    const toggleButton = document.querySelector('.toggle_menu');
    const sidebar = document.querySelector('.sidebar');
    
    if (toggleButton) {
        toggleButton.addEventListener('click', toggleSidebar);
    }
    
    // 2. Initialiser l'état 'collapsed' si nécessaire au chargement
    // Vous pouvez définir l'état initial ici si vous voulez que la sidebar soit fermée par défaut
    // sidebar.classList.add('collapsed');
    // document.body.classList.add('sidebar-collapsed-active');
});


// Fonction pour basculer le menu déroulant de notification
function toggleNotificationDropdown() {
    const notificationMenu = document.getElementById('notificationMenu');
    const notificationToggle = document.getElementById('notificationToggle');
    
    // Bascule la classe 'show' sur la liste
    notificationMenu.classList.toggle('show');

    // Ajoute/Retire la classe 'active' sur le conteneur du toggle pour le feedback visuel (facultatif)
    notificationToggle.classList.toggle('active'); 
}

// Fonction pour fermer le menu si l'utilisateur clique en dehors
function closeNotificationDropdown(event) {
    const notificationToggle = document.getElementById('notificationToggle');
    const notificationMenu = document.getElementById('notificationMenu');

    // Si le clic n'est PAS sur le bouton de bascule ET que le menu est visible
    if (notificationMenu.classList.contains('show') && !notificationToggle.contains(event.target)) {
        notificationMenu.classList.remove('show');
        notificationToggle.classList.remove('active');
    }
}

document.addEventListener('DOMContentLoaded', () => {
  
    const notificationToggle = document.getElementById('notificationToggle');
    if (notificationToggle) {
        notificationToggle.addEventListener('click', toggleNotificationDropdown);
    }

    document.addEventListener('click', closeNotificationDropdown);
});