<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    // 1. Liste de toutes les transactions
   public function index(Request $request) {
    $query = Transaction::with(['user', 'receiver'])->latest();

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('search')) {
        $q = $request->search;
        $query->where(function($f) use ($q) {
            $f->where('reference', 'LIKE', "%$q%")
              ->orWhereHas('user', fn($u) => $u->where('nom', 'LIKE', "%$q%")->orWhere('prenom', 'LIKE', "%$q%"));
        });
    }

    $transactions = $query->paginate(10);
    return view('admin.transactions.index', compact('transactions'));
}

    // 2. Formulaire de création (ou peut être géré via la vue index)
    public function create()
    {
        $users = User::all(); // Pour sélectionner les clients
        return view('admin.transactions.create', compact('users'));
    }

    // 3. Logique principale : Enregistrement de l'opération
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:depot,retrait,transfert',
            'montant' => 'required|numeric|min:1',
            'user_id' => 'required|exists:users,id',
            'receiver_id' => 'required_if:type,transfert|nullable|exists:users,id',
            'libelle' => 'nullable|string|max:255',
        ]);

        return DB::transaction(function () use ($request) {
            $user = User::findOrFail($request->user_id);
            $montant = $request->montant;

            // Traitement selon le type
            if ($request->type === 'depot') {
                $user->increment('solde', $montant);
            } 
            
            elseif ($request->type === 'retrait') {
                if ($user->solde < $montant) {
                    return back()->withErrors(['montant' => 'Solde insuffisant pour ce retrait.']);
                }
                $user->decrement('solde', $montant);
            } 
            
            elseif ($request->type === 'transfert') {
                if ($user->solde < $montant) {
                    return back()->withErrors(['montant' => 'Solde insuffisant pour le transfert.']);
                }
                $receiver = User::findOrFail($request->receiver_id);
                
                $user->decrement('solde', $montant);
                $receiver->increment('solde', $montant);
            }

            // Création de la transaction avec référence unique
            $transaction = Transaction::create([
                'reference' => 'TRX-' . strtoupper(Str::random(8)),
                'type' => $request->type,
                'montant' => $montant,
                'user_id' => $user->id,
                'receiver_id' => $request->receiver_id,
                'libelle' => $request->libelle,
                'statut' => 'succes',
            ]);

            return redirect()->route('admin.transactions.show', $transaction)
                             ->with('success', 'Opération effectuée avec succès.');
        });
    }

    // 4. Affichage d'une transaction spécifique (Le Reçu)
    public function show(Transaction $transaction)
    {
        // On charge les relations pour le reçu
        $transaction->load(['user', 'receiver']);
        return view('admin.transactions.receipt', compact('transaction'));
    }

    // 5. Exportation du reçu en PDF (Nécessite DomPDF)
    public function downloadReceipt(Transaction $transaction)
    {
        // Logique pour générer un PDF (si vous installez barryvdh/laravel-dompdf)
        // $pdf = Pdf::loadView('admin.transactions.receipt_pdf', compact('transaction'));
        // return $pdf->download('recu-'.$transaction->reference.'.pdf');
    }
}