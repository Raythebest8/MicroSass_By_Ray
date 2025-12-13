<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document; 
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\RedirectResponse; // Import nécessaire pour le type de retour

class AdminDocumentController extends Controller
{
    /**
     * Télécharge un document spécifique de manière sécurisée en spécifiant le Type MIME.
     */
    public function download(int $documentId): StreamedResponse | RedirectResponse
    {
        $document = Document::findOrFail($documentId);
        $filePath = $document->chemin_stockage; 
        $diskName = 'public'; 
        
        // La vérification de l'existence du fichier
        if (!Storage::disk($diskName)->exists($filePath)) {
            // Loggez ou retournez une erreur si le fichier est manquant
            return back()->with('error', 'Le fichier n\'existe plus sur le serveur de stockage.');
        }

        // --- DÉBUT DE LA LOGIQUE DE CORRECTION DU NOM ET DU MIME ---
        
        $fileName = $document->nom_afficher;
        // 1. Tenter d'obtenir le Type MIME depuis la base de données, sinon le lire depuis le fichier
        $mimeType = $document->mime_type ?? Storage::disk($diskName)->mimeType($filePath);
        
        // 2. Déduire l'extension à partir du Type MIME
        $extension = $this->getExtensionFromMime($mimeType);
        
        // 3. S'assurer que le nom du fichier (utilisé pour le téléchargement) inclut l'extension
        if ($extension && !str_ends_with(strtolower($fileName), strtolower($extension))) {
             $fileName .= $extension;
        }

        // 4. Définir les en-têtes (headers)
        $headers = [];
        if ($mimeType) {
            $headers['Content-Type'] = $mimeType;
        }

        // Le Content-Disposition est géré par la méthode download de Laravel, mais ajouter
        // le Content-Type est crucial pour l'affichage correct dans le navigateur.

        // --- FIN DE LA LOGIQUE DE CORRECTION DU NOM ET DU MIME ---
        
        // 5. Téléchargement sécurisé
        return Storage::disk($diskName)->download(
            $filePath, 
            $fileName, // Le nom du fichier AVEC l'extension
            $headers   // Les headers incluant le Content-Type
        );
    }
    
    /**
     * Fonction utilitaire pour déduire l'extension la plus courante à partir du Type MIME.
     */
    private function getExtensionFromMime(?string $mime): string
    {
        if (!$mime) return '';
        
        // Mapping simple des types les plus courants
        return match ($mime) {
            'application/pdf' => '.pdf',
            'image/jpeg', 'image/jpg' => '.jpg',
            'image/png' => '.png',
            'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx',
            'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => '.xlsx',
            'text/plain' => '.txt',
            default => '',
        };
    }
}