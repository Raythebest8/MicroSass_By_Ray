@extends('layouts.users')
@section('content') 
<div class="max-w-xl mx-auto my-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700">
    
    <div class="mb-6">
        <h3 class="text-2xl font-extrabold text-gray-800 dark:text-white flex items-center">
            <span class="mr-3 text-3xl text-indigo-600 dark:text-indigo-400">
                <i class="fas fa-calculator"></i>
            </span>
            Calculateur de prêt
        </h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Ajustez les paramètres pour obtenir une estimation de vos mensualités</p>
    </div>

    <hr class="mb-6 border-gray-200 dark:border-gray-700">

    <div class="space-y-8">
        
        <div class="input-group">
            <label class="block text-base font-semibold text-gray-700 dark:text-gray-200 mb-2">
                Salaire mensuel net: <span id="netSalaryDisplay" class="text-emerald-600 dark:text-emerald-400 font-bold">200 000 Fcfa</span>
            </label>
            <input type="range" id="netSalaryRange" min="50000" max="5000000" step="10000" value="200000" class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer range-lg custom-range">
            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                <span>50 000 Fcfa</span>
                <span>5 000 000 Fcfa</span>
            </div>
        </div>

        <div class="input-group">
            <label class="block text-base font-semibold text-gray-700 dark:text-gray-200 mb-2">
                Montant du prêt: <span id="loanAmountDisplay" class="text-indigo-600 dark:text-indigo-400 font-bold">1 400 000 Fcfa</span>
            </label>
            <input type="range" id="loanAmountRange" min="10000" max="50000000" step="10000" value="1400000" class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer range-lg custom-range">
            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                <span>10 000 Fcfa</span>
                <span>50 000 000 Fcfa</span>
            </div>
        </div>

        <div class="input-group">
            <label class="block text-base font-semibold text-gray-700 dark:text-gray-200 mb-2">
                Durée: <span id="durationDisplay" class="text-indigo-600 dark:text-indigo-400 font-bold">36 mois</span>
            </label>
            <input type="range" id="durationRange" min="6" max="84" step="6" value="36" class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer range-lg custom-range">
            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                <span>6 mois</span>
                <span>84 mois</span>
            </div>
        </div>

        <div class="input-group">
            <label class="block text-base font-semibold text-gray-700 dark:text-gray-200 mb-2">
                Taux d'intérêt: <span id="interestRateDisplay" class="text-indigo-600 dark:text-indigo-400 font-bold">3.50 %</span>
            </label>
            <input type="range" id="interestRateRange" min="0.5" max="10" step="0.05" value="3.50" class="w-full h-2 bg-gray-200 dark:bg-gray-700 rounded-lg appearance-none cursor-pointer range-lg custom-range">
            <div class="flex justify-between text-xs text-gray-500 dark:text-gray-400 mt-2">
                <span>0.5 %</span>
                <span>10 %</span>
            </div>
        </div>

    </div>

    <button id="submitButton" class="w-full mt-8 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg text-lg shadow-xl transition-colors focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50">
        Mensualité: 41 018 Fcfa
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
    // 1. Récupérer les éléments du DOM (MISE À JOUR)
    const netSalaryInput = document.getElementById('netSalaryRange');
    const netSalaryDisplay = document.getElementById('netSalaryDisplay');
    
    const loanAmountInput = document.getElementById('loanAmountRange');
    const loanAmountDisplay = document.getElementById('loanAmountDisplay');
    
    const durationInput = document.getElementById('durationRange');
    const durationDisplay = document.getElementById('durationDisplay');
    
    const interestInput = document.getElementById('interestRateRange');
    const interestDisplay = document.getElementById('interestRateDisplay');
    
    // Constante
    const maxDebtRatio = 0.33; // Ratio d'endettement maximal (33%)

    // 2. Fonction de formatage (pour les Fcfa)
    const formatFcfa = (number) => {
        return new Intl.NumberFormat('fr-FR', {
            style: 'currency',
            currency: 'XOF', // Code ISO pour le Franc CFA Ouest Africain
            minimumFractionDigits: 0
        }).format(number).replace('XOF', 'Fcfa').trim();
    };

    // 3. Fonction principale de calcul de la mensualité (Formule de l'annuité)
    const calculateMonthlyPayment = (principal, annualRate, months) => {
        if (principal <= 0 || months <= 0) return 0;

        const monthlyRate = (annualRate / 100) / 12; // Taux mensuel

        if (monthlyRate === 0) {
            // Pas de taux d'intérêt (cas rare mais géré)
            return principal / months;
        }

        // Formule M = P [ i(1 + i)^n ] / [ (1 + i)^n – 1 ]
        const numerator = monthlyRate * Math.pow((1 + monthlyRate), months);
        const denominator = Math.pow((1 + monthlyRate), months) - 1;
        
        return principal * (numerator / denominator);
    };

    // 4. Fonction de mise à jour des valeurs et du calcul
    const updateLoanCalculator = () => {
        // Lire les valeurs des Sliders (MISE À JOUR)
        const netSalary = parseFloat(netSalaryInput.value);
        const principal = parseFloat(loanAmountInput.value);
        const months = parseInt(durationInput.value);
        const annualRate = parseFloat(interestInput.value);

        // Mettre à jour les affichages
        netSalaryDisplay.textContent = formatFcfa(netSalary); // NOUVEL AFFICHAGE
        loanAmountDisplay.textContent = formatFcfa(principal);
        durationDisplay.textContent = `${months} mois`;
        interestDisplay.textContent = `${annualRate.toFixed(2)} %`;

        // Calculer la mensualité
        const monthlyPayment = calculateMonthlyPayment(principal, annualRate, months);
        
        // --- LOGIQUE D'ALERTE ET COULEURS ---

        // Calculer le ratio d'endettement actuel
        const debtRatio = monthlyPayment / netSalary;
        const monthlyPaymentFormatted = formatFcfa(monthlyPayment);

        // Sélectionner l'élément pour afficher le résultat
        const button = document.getElementById('submitButton');

        if (debtRatio > maxDebtRatio) {
            // Rouge si le ratio dépasse 33%
            button.disabled = true;
            button.textContent = `Mensualité: ${monthlyPaymentFormatted} (Ratio dépassé)`;
            button.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
            button.classList.add('bg-red-500', 'cursor-not-allowed', 'opacity-75');

        } else {
            // Bouton normal si le ratio est acceptable
            button.disabled = false;
            button.textContent = `Mensualité: ${monthlyPaymentFormatted} (Demander)`;
            button.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
            button.classList.remove('bg-red-500', 'cursor-not-allowed', 'opacity-75');
        }
    };

    // 5. Attacher les écouteurs d'événements aux sliders (MISE À JOUR)
    netSalaryInput.addEventListener('input', updateLoanCalculator); 
    loanAmountInput.addEventListener('input', updateLoanCalculator);
    durationInput.addEventListener('input', updateLoanCalculator);
    interestInput.addEventListener('input', updateLoanCalculator);

    // Initialiser les valeurs au chargement
    updateLoanCalculator();
});
</script>
@endsection