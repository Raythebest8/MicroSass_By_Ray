@extends('layouts.app')

@section('content')
    <div class="kpi-cards-container">
        <div class="kpi-card green-bg">
            <div class="kpi-header">Total de compte</div>
            <div class="kpi-main">
                <span class="kpi-value">{{ number_format($totalAccounts, 0, ',', ' ') }}</span>
                <div class="kpi-icon icon-green"><i class="fas fa-wallet"></i></div>
            </div>
            <div class="kpi-footer">
                <span class="indicator {{ $newAccountsThisWeek > 0 ? 'up-arrow' : '' }}">
                    {!! $newAccountsThisWeek > 0 ? '▲' : '●' !!} {{ $newAccountsThisWeek }} nouveau{{ $newAccountsThisWeek > 1 ? 's' : '' }}
                    cette semaine
                </span>
            </div>
        </div>

        <div class="kpi-card blue-bg">
            <div class="kpi-header">Suivi de prêt</div>
            <div class="kpi-main">
                <span class="kpi-value">{{ number_format($totalLoans, 0, ',', ' ') }}</span>
                <div class="kpi-icon icon-blue"><i class="fas fa-users"></i></div>
            </div>
            <div class="kpi-footer">
                <span class="indicator {{ $newLoansThisWeek > 0 ? 'up-arrow' : '' }}">
                    {!! $newLoansThisWeek > 0 ? '▲' : '●' !!} {{ $newLoansThisWeek }} prêt{{ $newLoansThisWeek > 1 ? 's' : '' }} cette semaine
                </span>
            </div>
        </div>

        <div class="kpi-card red-bg">
            <div class="kpi-header">Crédit accordé</div>
            <div class="kpi-main">
                <span class="kpi-value" style="font-size: 22px;">{{ number_format($totalCredit, 0, ',', ' ') }} <small
                        style="font-size: 12px;">FCFA</small></span>
                <div class="kpi-icon icon-red"><i class="fas fa-credit-card"></i></div>
            </div>
            <div class="kpi-footer">
                <span class="indicator {{ $newCreditThisWeek > 0 ? 'up-arrow' : '' }}">
                    {!! $newCreditThisWeek > 0 ? '▲' : '●' !!} {{ number_format($newCreditThisWeek, 0, ',', ' ') }} FCFA cette semaine
                </span>
            </div>
        </div>

        <div class="kpi-card yellow-bg">
            <div class="kpi-header">Paiement reçu</div>
            <div class="kpi-main">
                <span class="kpi-value" style="font-size: 22px;">{{ number_format($totalPayments, 0, ',', ' ') }} <small
                        style="font-size: 12px;">FCFA</small></span>
                <div class="kpi-icon icon-yellow"> <i class="fas fa-chart-line"></i></div>
            </div>
            <div class="kpi-footer">
                <span class="indicator">
                    <i class="fas fa-sync-alt"></i> Recouvrement : {{ $percentageOfCreditReceived }} %
                </span>
            </div>
        </div>
    </div>



    <div class="panels-grid">
        <div class="panel recent-stats">
            <h3>Statistique récent</h3>
            <div class="stat-circles">
                <div class="circle-item">
                    <div class="circle-progress" style="--p:{{ $percentEntreprise }};">
                        <div class="percentage">{{ $percentEntreprise }}%</div>
                    </div>
                    <p>Entreprise</p>
                </div>

                <div class="circle-item">
                    <div class="circle-progress blue" style="--p:{{ $percentParticulier }};">
                        <div class="percentage">{{ $percentParticulier }}%</div>
                    </div>
                    <p>Particulier</p>
                </div>
            </div>
        </div>

        <div class="panel history-panel">
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h3 style="margin: 0;">Historique de transaction</h3>
        <a href="{{ route('admin.transactions.index') }}" class="btn-view-all" style="text-decoration: none; color: #3498db; font-size: 14px; font-weight: bold;">
            Voir tout <i class="fas fa-arrow-right" style="font-size: 12px; margin-left: 5px;"></i>
        </a>
    </div>

    <table>
        <thead>
            <tr>
                <th>Client</th>
                <th>Type de prêt</th>
                <th>Date</th>
                <th>Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentTransactions as $transaction)
                <tr>
                    <td>{{ $transaction->user->nom }}</td> 
                    <td>{{ $transaction->type }}</td>
                    <td>{{ \Carbon\Carbon::parse($transaction->date)->format('d M Y') }}</td>
                    <td style="font-weight: bold;">
                        {{ number_format($transaction->montant, 0, ',', ' ') }} FCFA
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" style="text-align: center; padding: 20px;">Aucune transaction trouvée.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

       <div class="panel loan-requests">
    <div class="panel-header" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
        <h3 style="margin: 0;">Demandes de prêt <i class="fas fa-plus-circle" style="font-size: 14px; color: #3498db; cursor: pointer;"></i></h3>
        <a href="{{ route('admin.demandes.index') }}" style="text-decoration: none; color: #3498db; font-size: 12px; font-weight: bold;">Voir tout</a>
    </div>

    <div class="request-cards">
        @forelse($recentRequests as $request)
            <div class="request-card">
                <div class="amount">{{ number_format($request->montant_souhaite, 0, ',', ' ') }} FCFA</div>
                <div class="date">{{ $request->created_at->format('d/m/y') }}</div>
                
                {{-- Choix de l'icône selon la catégorie --}}
                @php
                    $iconClass = 'home'; 
                    $iconName = 'fa-home';
                    if($request->categorie == 'Professionnel') { $iconClass = 'factory'; $iconName = 'fa-industry'; }
                    if($request->categorie == 'Scolaire') { $iconClass = 'game'; $iconName = 'fa-graduation-cap'; }
                @endphp

                <div class="request-icon {{ $iconClass }}">
                    <i class="fas {{ $iconName }}"></i>
                </div>
                
                <div class="client-name">{{ $request->client_nom }}</div>
            </div>
        @empty
            <p style="text-align: center; width: 100%; color: #888; padding: 20px;">Aucune demande en attente.</p>
        @endforelse
    </div>
</div>
    </div>


    <style>
    </style>
@endsection
