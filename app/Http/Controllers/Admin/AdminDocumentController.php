<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\RedirectResponse;

class AdminDocumentController extends Controller
{
    /**
     * Télécharger un document
     */
    public function download(int $documentId): StreamedResponse|RedirectResponse
    {
        $document = Document::findOrFail($documentId);

        // 🔒 Sécurité minimale (à adapter à ton business)
        // abort_if($document->demande->statut !== 'validee', 403);

        $filePath = $document->chemin_stockage;
        $disk = 'public';

        if (!Storage::disk($disk)->exists($filePath)) {
            return back()->with('error', 'Le fichier est introuvable.');
        }

        $mimeType = $document->mime_type
            ?? Storage::disk($disk)->mimeType($filePath);

        $extension = $this->getExtensionFromMime($mimeType);

        $fileName = $document->nom_afficher;
        if ($extension && !str_ends_with(strtolower($fileName), $extension)) {
            $fileName .= $extension;
        }

        return Storage::disk($disk)->download(
            $filePath,
            $fileName,
            ['Content-Type' => $mimeType]
        );
    }

    /**
     * Prévisualiser un document (INLINE)
     */
    public function preview(Document $document)
    {
        $filePath = $document->chemin_stockage;
        $disk = 'public';

        if (!Storage::disk($disk)->exists($filePath)) {
            abort(404, 'Document introuvable');
        }

        $mimeType = $document->mime_type
            ?? Storage::disk($disk)->mimeType($filePath);

        return response()->file(
            Storage::disk($disk)->path($filePath),
            [
                'Content-Type' => $mimeType,
                'Content-Disposition' => 'inline'
            ]
        );
    }

    /**
     * Mapping MIME → extension
     */
    private function getExtensionFromMime(?string $mime): string
    {
        return match ($mime) {
            'application/pdf' => '.pdf',
            'image/jpeg' => '.jpg',
            'image/png' => '.png',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document' => '.docx',
            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet' => '.xlsx',
            'text/plain' => '.txt',
            default => '',
        };
    }
}
