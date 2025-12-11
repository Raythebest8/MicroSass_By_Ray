<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Notifications\Notifiable;

class NotificationController extends Controller
{
    
    /**
     * Marque une notification spécifique comme lue et redirige.
     * * @param string $id L'ID de la notification (stocké en base)
     * @param Request $request Contient l'URL de redirection ('redirect')
     * @return \Illuminate\Http\RedirectResponse
     */
    public function markAsRead(Request $request, $id)
    {
        // 1. Trouver la notification pour l'utilisateur connecté
        $notification = Auth::user()->notifications()->find($id);

        if ($notification) {
            // 2. Marquer comme lue
            $notification->markAsRead();
        }

        // 3. Rediriger vers l'URL stockée dans la notification (pour voir les détails de la demande)
        // Utilise l'URL passée dans la requête, ou redirige vers le tableau de bord admin par défaut
        return Redirect::to($request->input('redirect', route('admin.dashboard')))->with('success', 'Notification marquée comme lue.');
    }

    /**
     * Affiche toutes les notifications de l'administrateur.
     */
    public function index()
    {
        $notifications = Auth::user()->notifications()->paginate(20);

        return view('admin.notifications.index', compact('notifications'));
    }
}