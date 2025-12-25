@extends('layouts.users')

@section('content')
<div class="max-w-6xl mx-auto my-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-100 dark:border-gray-700">
    <div class="flex items-center justify-between mb-6 border-b border-gray-100 dark:border-gray-700 pb-4">
        <h3 class="text-2xl font-extrabold text-gray-800 dark:text-white tracking-tight">
            Mon Calendrier de Remboursement
        </h3>
    </div>

    <div id="calendar" class="bg-white dark:bg-gray-800 rounded-lg p-2 text-gray-700 dark:text-gray-200"></div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js'></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const calendarEl = document.getElementById('calendar');
    // On s'assure que events est un tableau vide si aucune donnée n'est reçue
    const events = @json($events) || [];

    const calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'fr',
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,dayGridWeek'
        },
        buttonText: { today: "Aujourd'hui", month: "Mois", week: "Semaine" },
        events: events,
        // On utilise la propriété eventDidMount pour ajouter des classes Tailwind
        eventDidMount: function(info) {
            info.el.classList.add('cursor-pointer', 'hover:opacity-80', 'transition-opacity', 'rounded-md', 'p-1', 'border-0');
        },
        eventClick: function(info) {
            const props = info.event.extendedProps;
            
            if (props.statut !== 'payée') {
                Swal.fire({
                    title: info.event.title,
                    html: `<p class="mt-2 text-sm">Statut : <span class="font-bold text-orange-500">${props.statut}</span></p><p class="mt-1">Voulez-vous régler cette échéance ?</p>`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonText: 'Payer maintenant',
                    confirmButtonColor: '#4F46E5',
                    background: document.documentElement.classList.contains('dark') ? '#1F2937' : '#FFFFFF',
                    color: document.documentElement.classList.contains('dark') ? '#F3F4F6' : '#1F2937'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.location.href = props.url;
                    }
                });
            }
        }
    });
    calendar.render();

    // 2. Toast Configuration
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 5000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.style.cursor = 'pointer';
            toast.addEventListener('click', () => { /* Redirection gérée dans .then */ });
        }
    });

    // 3. Alertes de rappel
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    events.forEach((event, index) => {
        const eventDate = new Date(event.start);
        eventDate.setHours(0, 0, 0, 0);
        const diffDays = Math.ceil((eventDate - today) / (1000 * 60 * 60 * 24));

        if (diffDays >= 0 && diffDays <= 5 && event.extendedProps.statut !== 'payée') {
            setTimeout(() => {
                Toast.fire({
                    icon: 'warning',
                    title: 'Échéance proche',
                    html: `Règlement de <b>${event.extendedProps.montant} FCFA</b> attendu.<br>Cliquez pour payer.`
                }).then((result) => {
                    if (result.dismiss !== Swal.DismissReason.timer) {
                        window.location.href = event.extendedProps.url;
                    }
                });
            }, index * 1000);
        }
    });
});
</script>

<style>
    /* CSS Standard sans @apply pour éviter les erreurs de compilation */
    .fc .fc-toolbar-title { font-size: 1.1rem; font-weight: 700; }
    .fc .fc-button-primary { 
        background-color: #4F46E5 !important; 
        border-color: #4F46E5 !important; 
        text-transform: capitalize !important;
    }
    .fc .fc-button-primary:hover { background-color: #4338CA !important; }
    
    /* Adaptations Mode Sombre manuelles */
    .dark .fc-theme-standard td, .dark .fc-theme-standard th { border-color: #374151 !important; }
    .dark .fc .fc-daygrid-day-number { color: #9CA3AF !important; }
    .dark .fc-col-header-cell-cushion { color: #F3F4F6 !important; }
</style>
@endsection