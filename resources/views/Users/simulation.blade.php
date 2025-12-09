@extends('layouts.users')
@section('content') 
<div class="max-w-4xl mx-auto my-8 p-6 bg-white dark:bg-gray-800 rounded-xl shadow-2xl border border-gray-100 dark:border-gray-700">
    
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
    
    <div id="resultsSummary" class="mt-8 p-4 md:p-6 bg-indigo-50 dark:bg-indigo-900/30 rounded-lg border border-indigo-200 dark:border-indigo-700/50 shadow-md">
        <h4 class="text-lg font-bold text-indigo-800 dark:text-indigo-200 mb-4">Vos résultats estimés :</h4>
        
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-center">
            
            <div class="flex flex-col items-center border-b md:border-b-0 md:border-r border-indigo-200 dark:border-indigo-700/50 pb-2 md:pb-0">
                <span id="monthlyPaymentResult" class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-400">41 018 Fcfa</span>
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400 mt-1">Mensualité</span>
            </div>
            
            <div class="flex flex-col items-center border-b md:border-b-0 border-indigo-200 dark:border-indigo-700/50 pb-2 md:pb-0">
                <span id="debtRatioResult" class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-400">20.5%</span>
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400 mt-1">Taux Endettement</span>
            </div>
            
            <div class="flex flex-col items-center md:border-r border-indigo-200 dark:border-indigo-700/50">
                <span id="interestCostResult" class="text-2xl font-extrabold text-indigo-700 dark:text-indigo-400">76 660 Fcfa</span>
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400 mt-1">Coût des Intérêts</span>
            </div>
            
            <div class="flex flex-col items-center">
                <span id="remainingIncomeResult" class="text-2xl font-extrabold text-emerald-700 dark:text-emerald-400">158 982 Fcfa</span>
                <span class="text-xs font-medium text-gray-600 dark:text-gray-400 mt-1">Reste à Vivre</span>
            </div>
        </div>
    </div>
    <a href="{{ route('users.demande.index')}}">
        <button id="submitButton" class="w-full mt-8 bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 rounded-lg text-lg shadow-xl transition-colors focus:outline-none focus:ring-4 focus:ring-indigo-500 focus:ring-opacity-50">
        Faire une demande
       </button>
    </a>
    
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. Récupérer les éléments du DOM
        const netSalaryInput = document.getElementById('netSalaryRange');
        const netSalaryDisplay = document.getElementById('netSalaryDisplay');
        const loanAmountInput = document.getElementById('loanAmountRange');
        const loanAmountDisplay = document.getElementById('loanAmountDisplay');
        const durationInput = document.getElementById('durationRange');
        const durationDisplay = document.getElementById('durationDisplay');
        const interestInput = document.getElementById('interestRateRange');
        const interestDisplay = document.getElementById('interestRateDisplay');
        
        // NOUVEAUX ÉLÉMENTS DE RÉSULTAT (MISE À JOUR)
        const monthlyPaymentResult = document.getElementById('monthlyPaymentResult'); // NOUVEAU
        const debtRatioResult = document.getElementById('debtRatioResult');
        const interestCostResult = document.getElementById('interestCostResult'); // NOUVEAU
        const remainingIncomeResult = document.getElementById('remainingIncomeResult');
        const submitButton = document.getElementById('submitButton');
        
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
                return principal / months;
            }

            // Formule M = P [ i(1 + i)^n ] / [ (1 + i)^n – 1 ]
            const numerator = monthlyRate * Math.pow((1 + monthlyRate), months);
            const denominator = Math.pow((1 + monthlyRate), months) - 1;
            
            return principal * (numerator / denominator);
        };

        // 4. Fonction de mise à jour des valeurs et du calcul
        const updateLoanCalculator = () => {
            // Lire les valeurs des Sliders
            const netSalary = parseFloat(netSalaryInput.value);
            const principal = parseFloat(loanAmountInput.value);
            const months = parseInt(durationInput.value);
            const annualRate = parseFloat(interestInput.value);

            // Mettre à jour les affichages des sliders
            netSalaryDisplay.textContent = formatFcfa(netSalary);
            loanAmountDisplay.textContent = formatFcfa(principal);
            durationDisplay.textContent = `${months} mois`;
            interestDisplay.textContent = `${annualRate.toFixed(2)} %`;

            // Calculer la mensualité
            const monthlyPayment = calculateMonthlyPayment(principal, annualRate, months);
            
            // --- NOUVEAUX CALCULS DE RÉSULTATS ---
            const totalRepayment = monthlyPayment * months;
            const interestCost = totalRepayment - principal; // Coût des intérêts (différence avec le capital)
            const debtRatio = monthlyPayment / netSalary; // Taux d'endettement
            const remainingIncome = netSalary - monthlyPayment; // Reste à vivre
            
            const monthlyPaymentFormatted = formatFcfa(monthlyPayment);
            
            // --- 5. MISE À JOUR DES RÉSULTATS DÉTAILLÉS ---
            
            // Mensualité
            monthlyPaymentResult.textContent = monthlyPaymentFormatted;
            
            // Taux d'endettement
            const debtRatioPercentage = (debtRatio * 100).toFixed(1);
            debtRatioResult.textContent = `${debtRatioPercentage} %`;
            
            // Coût des Intérêts
            interestCostResult.textContent = formatFcfa(interestCost);
            
            // Reste à Vivre
            remainingIncomeResult.textContent = formatFcfa(remainingIncome);
            
            // --- LOGIQUE D'ALERTE ET COULEURS DU BOUTON ---

            submitButton.textContent = 'Faire une demande'; // Texte du bouton mis à jour

            if (debtRatio > maxDebtRatio) {
                // Rouge si le ratio dépasse 33%
                submitButton.disabled = true;
                submitButton.classList.remove('bg-indigo-600', 'hover:bg-indigo-700');
                submitButton.classList.add('bg-red-500', 'cursor-not-allowed', 'opacity-75');
                
                // Mettre le taux d'endettement en rouge
                debtRatioResult.classList.remove('text-indigo-700', 'dark:text-indigo-400');
                debtRatioResult.classList.add('text-red-600', 'dark:text-red-400');

            } else {
                // Bouton normal si le ratio est acceptable
                submitButton.disabled = false;
                submitButton.classList.add('bg-indigo-600', 'hover:bg-indigo-700');
                submitButton.classList.remove('bg-red-500', 'cursor-not-allowed', 'opacity-75');
                
                // Mettre le taux d'endettement en indigo
                debtRatioResult.classList.add('text-indigo-700', 'dark:text-indigo-400');
                debtRatioResult.classList.remove('text-red-600', 'dark:text-red-400');
            }
        };

        // 6. Attacher les écouteurs d'événements aux sliders
        netSalaryInput.addEventListener('input', updateLoanCalculator); 
        loanAmountInput.addEventListener('input', updateLoanCalculator);
        durationInput.addEventListener('input', updateLoanCalculator);
        interestInput.addEventListener('input', updateLoanCalculator);

        // Initialiser les valeurs au chargement
        updateLoanCalculator();
    });
</script>



@endsection