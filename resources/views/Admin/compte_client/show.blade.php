@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-3xl mx-auto px-4">
        
        <div class="mb-6">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-orange-600 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Retour à la liste
            </a>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            
            {{-- Header avec dégradé --}}
            <div class="bg-gradient-to-r from-gray-100 to-white px-8 pt-10 pb-16 border-bottom border-gray-100 relative">
                <div class="flex flex-col md:flex-row items-center gap-6 relative z-10">
                    
                    {{-- Zone IMAGE --}}
                    <div class="relative">
                        <div class="h-28 w-28 rounded-3xl bg-white p-1 shadow-lg">
                            <div class="h-full w-full rounded-[1.25rem] bg-orange-100 flex items-center justify-center overflow-hidden">
                                @if($user->profile_image)
                                    <img src="{{ asset('storage/profile_image/' . $user->profile_image) }}" class="h-full w-full object-cover">
                                @else
                                    <span class="text-orange-600 text-3xl font-black">
                                        {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        {{-- Badge de statut --}}
                        <div class="absolute -bottom-2 -right-2 h-6 w-6 bg-green-500 border-4 border-white rounded-full"></div>
                    </div>
                    
                    <div class="text-center md:text-left">
                        <h1 class="text-2xl font-black text-gray-900 leading-tight">
                            {{ $user->prenom }} {{ $user->nom }}
                        </h1>
                        <p class="text-gray-500 font-medium">{{ $user->profession ?? 'Collaborateur' }}</p>
                        <div class="mt-3 flex flex-wrap justify-center md:justify-start gap-2">
                            <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-orange-100">
                                {{ $user->role }}
                            </span>
                            <span class="px-3 py-1 bg-gray-50 text-gray-600 text-[10px] font-black uppercase tracking-widest rounded-full border border-gray-100">
                                ID: N°{{ $user->id }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    
                    {{-- Coordonnées --}}
                    <div>
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Coordonnées</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-blue-50 rounded-lg text-blue-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Email</p>
                                    <p class="text-sm font-bold text-gray-700">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-green-50 rounded-lg text-green-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg></div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Téléphone</p>
                                    <p class="text-sm font-bold text-gray-700">{{ $user->telephone ?? 'Non renseigné' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Détails Personnels --}}
                    <div>
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-4">Détails Personnels</h3>
                        <div class="space-y-4">
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-purple-50 rounded-lg text-purple-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg></div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Profession</p>
                                    <p class="text-sm font-bold text-gray-700">{{ $user->profession }}</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-3">
                                <div class="p-2 bg-red-50 rounded-lg text-red-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg></div>
                                <div>
                                    <p class="text-[10px] text-gray-400 uppercase font-bold">Situation Matrimoniale</p>
                                    <p class="text-sm font-bold text-gray-700">{{ $user->situation_matrimonial }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-12 pt-6 border-t border-gray-50 flex flex-col sm:flex-row gap-3">
                    <a href="{{ route('admin.users.edit', $user) }}" class="flex-1 bg-gray-900 hover:bg-black text-white text-center py-3 rounded-2xl font-bold transition-all shadow-lg shadow-gray-200">
                        Modifier le profil
                    </a>
                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="flex-1" onsubmit="return confirm('Confirmer la suppression ?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="w-full bg-white border border-red-100 text-red-500 hover:bg-red-50 py-3 rounded-2xl font-bold transition-all">
                            Désactiver le compte
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <p class="text-center mt-6 text-[10px] text-gray-400 uppercase tracking-widest font-bold">
            Compte créé le {{ $user->created_at->format('d/m/Y à H:i') }}
        </p>
    </div>
</div>
@endsection