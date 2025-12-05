@extends('layouts.app')

@section('content')

<div class="kpi-cards-container">
    <div class="kpi-card green-bg">
        <div class="kpi-header">Total de compte</div>
        <div class="kpi-main">
            <span class="kpi-value">4805</span>
            <div class="kpi-icon icon-green"><i class="fas fa-wallet"></i></div>
        </div>
        <div class="kpi-footer">
            <span class="indicator up-arrow">▲ 34 compte cette semaine </span>
        </div>
    </div>

    <div class="kpi-card blue-bg">
        <div class="kpi-header">Suivie de prêt</div>
        <div class="kpi-main">
            <span class="kpi-value">234</span>
            <div class="kpi-icon icon-blue"><i class="fas fa-users"></i></div>
        </div>
        <div class="kpi-footer">
            <span class="indicator up-arrow">▲ 24 prêt cette semaine</span>
        </div>
    </div>

    <div class="kpi-card red-bg">
        <div class="kpi-header">Crédit accordée</div>
        <div class="kpi-main">
            <span class="kpi-value">30987347 Fcfa</span>
            <div class="kpi-icon icon-red"><i class="fas fa-credit-card"></i></div>
        </div>
        <div class="kpi-footer">
            <span class="indicator down-arrow">▼ 1000000 Fcfa cette semaine</span>
        </div>
    </div>

    <div class="kpi-card yellow-bg">
        <div class="kpi-header">Payment reçu</div>
        <div class="kpi-main">
            <span class="kpi-value">3455653 Fcfa</span>
            <div class="kpi-icon icon-yellow"> <i class="fas fa-chart-line"></i></div>
        </div>
        <div class="kpi-footer">
            <span class="indicator down-arrow">▼ 13,2 % du credit accordée</span>
        </div>
    </div>
</div>

   

<div class="panels-grid">
                <div class="panel recent-stats">
                    <h3>Statistique récent</h3>
                    <div class="stat-circles">
                        <div class="circle-item">
                            <div class="circle-progress" style="--p:50;">
                                <div class="percentage">50%</div>
                            </div>
                            <p>Entreprise</p>
                        </div>
                        <div class="circle-item">
                            <div class="circle-progress" style="--p:75;">
                                <div class="percentage">75%</div>
                            </div>
                            <p>Entreprise</p>
                        </div>
                        <div class="circle-item">
                            <div class="circle-progress blue" style="--p:87;">
                                <div class="percentage">87%</div>
                            </div>
                            <p>Particulier</p>
                        </div>
                    </div>
                </div>

                <div class="panel history-panel">
                    <h3>Historique de transaction</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Type de pret</th>
                                <th>Date</th>
                                <th>Montants</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr><td>Raymond</td><td>Scolaire</td><td>12 Dec 2025</td><td>$80.67</td></tr>
                            <tr><td>Jonathan</td><td>Personnel</td><td>14 Dec 2025</td><td>$1290.00</td></tr>
                            <tr><td>Palmer</td><td>Immobilier</td><td>07 Dec 2025</td><td>$99.50</td></tr>
                            <tr><td>Bradock</td><td>Professionnel</td><td>05 Dec 2025</td><td>$15.50</td></tr>
                            <tr><td>Theodore</td><td>Agricole</td><td>31 Nov 2025</td><td>$230</td></tr>
                            <tr><td>Client 6</td><td>Personnel</td><td>01 Jan 2026</td><td>$500.00</td></tr>
                            <tr><td>Client 7</td><td>Immobilier</td><td>02 Jan 2026</td><td>$150.00</td></tr>
                            <tr><td>Client 8</td><td>Professionnel</td><td>03 Jan 2026</td><td>$25.00</td></tr>
                            <tr><td>Client 9</td><td>Scolaire</td><td>04 Jan 2026</td><td>$75.00</td></tr>
                            <tr><td>Client 10</td><td>Agricole</td><td>05 Jan 2026</td><td>$400.00</td></tr>
                            <tr><td>Client 11</td><td>Personnel</td><td>06 Jan 2026</td><td>$1000.00</td></tr>
                            <tr><td>Client 12</td><td>Immobilier</td><td>07 Jan 2026</td><td>$50.00</td></tr>
                            <tr><td>Client 13</td><td>Professionnel</td><td>08 Jan 2026</td><td>$12.50</td></tr>
                            <tr><td>Client 14</td><td>Scolaire</td><td>09 Jan 2026</td><td>$90.00</td></tr>
                        </tbody>
                    </table>
                </div>

                <!-- <div class="panel loan-requests">
                    <h3>Demande de pret <i class="fas fa-plus-circle"></i></h3>
                    <div class="request-cards">
                        <div class="request-card">
                            <div class="amount">140 000 FCFA</div>
                            <div class="date">12/12/25</div>
                            <div class="request-icon home"><i class="fas fa-home"></i></div>
                            <div class="client-name">Mr Claude</div>
                        </div>
                        <div class="request-card">
                            <div class="amount">400 000 FCFA</div>
                            <div class="date">12/12/25</div>
                            <div class="request-icon factory"><i class="fas fa-industry"></i></div>
                            <div class="client-name">Mme Eva</div>
                        </div>
                        <div class="request-card">
                            <div class="amount">760 000 FCFA</div>
                            <div class="date">12/12/25</div>
                            <div class="request-icon game"><i class="fas fa-gamepad"></i></div>
                            <div class="client-name">Mr Raymond</div>
                        </div>
                    </div>
                </div> -->
</div>


 <style>
</style>
@endsection