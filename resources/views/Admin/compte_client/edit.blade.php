@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50 py-12">
    <div class="max-w-4xl mx-auto px-4">
        
        <div class="mb-8">
            <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-orange-600 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Annuler et revenir
            </a>
        </div>

        <div class="bg-white rounded-[2.5rem] shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <form method="POST" action="{{ route('admin.users.update', $user) }}" enctype="multipart/form-data" class="p-8 md:p-12">
                @csrf
                @method('PATCH')

                <div class="flex flex-col lg:flex-row gap-12">
                    
                    <div class="lg:w-1/3 flex flex-col items-center">
                        <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Photo de profil</h3>
                        
                        <div class="relative group cursor-pointer">
                            <div class="h-48 w-48 rounded-[2rem] bg-gray-100 border-4 border-white shadow-2xl overflow-hidden flex items-center justify-center transition-all group-hover:ring-4 group-hover:ring-orange-100">
                                @if($user->photo)
                                    <img id="preview" src="{{ asset('storage/' . $user->photo) }}" class="h-full w-full object-cover">
                                @else
                                    <div id="placeholder" class="text-orange-600 text-5xl font-black">
                                        {{ strtoupper(substr($user->prenom, 0, 1)) }}{{ strtoupper(substr($user->nom, 0, 1)) }}
                                    </div>
                                    <img id="preview" src="#" class="hidden h-full w-full object-cover">
                                @endif
                            </div>
                            
                            <label for="photo" class="absolute -bottom-2 -right-2 bg-gray-900 text-white p-3 rounded-2xl shadow-xl hover:bg-orange-600 transition-colors cursor-pointer">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </label>
                            <input type="file" id="photo" name="photo" class="hidden" accept="image/*" onchange="previewImage(event)">
                        </div>
                        <p class="mt-4 text-[10px] text-gray-400 font-medium italic text-center px-4">Cliquez sur l'icône pour modifier la photo (JPG, PNG max 2Mo)</p>
                    </div>

                    <div class="lg:w-2/3">
                        <div class="mb-10 text-center lg:text-left">
                            <h2 class="text-3xl font-black text-gray-900 leading-tight">Modifier le profil</h2>
                            <p class="text-gray-400 font-medium">Mise à jour des accès de {{ $user->prenom }}</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Nom</label>
                                <input type="text" name="nom" value="{{ old('nom', $user->nom) }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all font-bold text-gray-700 @error('nom') border-red-500 @enderror" required>
                                @error('nom') <span class="text-red-500 text-[10px] ml-2">{{ $message }}</span> @enderror
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Prénom</label>
                                <input type="text" name="prenom" value="{{ old('prenom', $user->prenom) }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all font-bold text-gray-700 @error('prenom') border-red-500 @enderror" required>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Téléphone</label>
                                <input type="text" name="telephone" value="{{ old('telephone', $user->telephone) }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all font-bold text-gray-700" required>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Profession</label>
                                <input type="text" name="profession" value="{{ old('profession', $user->profession) }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all font-bold text-gray-700" required>
                            </div>
                        </div>

                        <div class="space-y-1 mb-8">
                            <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Adresse Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email) }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all font-bold text-gray-700" required>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-12">
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Rôle Système</label>
                                <select name="role" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all font-bold text-gray-700 appearance-none">
                                    @foreach ($roles as $role)
                                        <option value="{{ $role }}" {{ old('role', $user->role) == $role ? 'selected' : '' }}>{{ ucfirst($role) }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-black text-gray-400 uppercase ml-2">Situation Matrimoniale</label>
                                <input type="text" name="situation_matrimonial" value="{{ old('situation_matrimonial', $user->situation_matrimonial) }}" class="w-full px-5 py-3 rounded-2xl bg-gray-50 border-transparent focus:bg-white focus:ring-2 focus:ring-orange-100 focus:border-orange-500 transition-all font-bold text-gray-700" required>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-4 border-t pt-8">
                            <a href="{{ route('admin.users.index') }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 px-6">Annuler</a>
                            <button type="submit" class="bg-gray-900 text-white px-10 py-4 rounded-2xl font-black text-xs uppercase tracking-widest hover:bg-orange-600 hover:shadow-2xl hover:shadow-orange-200 transition-all">
                                Enregistrer les modifications
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function previewImage(event) {
        const reader = new FileReader();
        const preview = document.getElementById('preview');
        const placeholder = document.getElementById('placeholder');
        
        reader.onload = function(){
            preview.src = reader.result;
            preview.classList.remove('hidden');
            if(placeholder) placeholder.classList.add('hidden');
        }
        reader.readAsDataURL(event.target.files[0]);
    }
</script>
@endsection