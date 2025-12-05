<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/auth.css') }}">
    <title>MicroSaaS-connexion-inscription</title>

    
</head>
<body>

<div class="auth-container">
    <aside class="visual-panel" aria-hidden="false">
        <img src="/assets/images/nCFd0q.jpg" alt="Illustration MicroSass" class="visual-image">

        <div class="visual-text">
            <span>Bienvenue sur MicroSass</span>
            <span>Créez votre compte en quelques secondes</span>
            <span>Gérez vos prêts facilement</span>
            <span>Sécurisé & Fiable</span>
        </div>
    </aside>
    
    <section class="form-panel" aria-label="Formulaires Connexion et Inscription">
        <div class="client-area-form">
            <h1>Votre microsass</h1>
            <p class="subtitle">Connectez-vous ou créez votre compte</p>

            <div class="tabs" role="tablist">
                <button class="tab-button" id="login-tab" role="tab" aria-selected="false" aria-controls="login-form">
                    Connexion
                </button>
                <button class="tab-button active" id="register-tab" role="tab" aria-selected="true" aria-controls="register-form">
                    Inscription
                </button>
            </div>

            <form id="register-form" method="POST" action="{{ route('auth.register') }}" enctype="multipart/form-data" role="tabpanel" aria-labelledby="register-tab">
                @csrf
                
                <div class="input-group-row">
                    <div class="input-field-container">
                        <label for="nom">Nom <span class="required">*</span></label>
                        <input type="text" id="nom" name="nom" placeholder="Votre Nom" 
                            value="{{ old('nom') }}" required
                            aria-invalid="{{ $errors->has('nom') ? 'true' : 'false' }}">
                        @error('nom')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="input-field-container">
                        <label for="prenom">Prénom <span class="required">*</span></label>
                        <input type="text" id="prenom" name="prenom" placeholder="Votre Prénom" 
                            value="{{ old('prenom') }}" required
                            aria-invalid="{{ $errors->has('prenom') ? 'true' : 'false' }}">
                        @error('prenom')
                            <span class="error-message">{{ $message }}</span>
                        @enderror
                    </div>
                </div>

                <div class="input-field-container">
                    <label for="email">Email <span class="required">*</span></label>
                    <input type="email" id="email" name="email" placeholder="Votre Email" 
                        value="{{ old('email') }}" required
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-field-container">
                    <label for="password">Mot de passe <span class="required">*</span></label>
                    <input type="password" id="password" name="password" placeholder="Mot de passe" 
                        required
                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-field-container">
                    <label for="password_confirmation">Confirmation du Mot de passe <span class="required">*</span></label>
                    <input type="password" id="password_confirmation" name="password_confirmation" 
                        placeholder="Confirmez le mot de passe" required>
                </div>

                <div class="input-field-container">
                    <label for="image">Image (optionnel)</label>
                    <input type="file" id="image" name="image_path" accept="image/*"
                        aria-invalid="{{ $errors->has('image_path') ? 'true' : 'false' }}">
                    @error('image_path')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-checkbox">
                    <input type="checkbox" id="terms" name="terms" required 
                        aria-invalid="{{ $errors->has('terms') ? 'true' : 'false' }}">
                    <label for="terms">J'accepte les <a href="/terms">termes et conditions</a> <span class="required">*</span></label>
                    @error('terms')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="submit-button">
                    <span class="icon"><i class="fas fa-user-plus"></i></span> Créer un compte
                </button>
            </form>

            <form id="login-form" method="POST" action="{{ route('auth.login') }}" style="display:none;" role="tabpanel" aria-labelledby="login-tab">
                @csrf
                
                <div class="input-field-container">
                    <label for="login-email">Email <span class="required">*</span></label>
                    <input type="email" id="login-email" name="email" placeholder="Votre email" 
                        value="{{ old('email') }}" required
                        aria-invalid="{{ $errors->has('email') ? 'true' : 'false' }}">
                    @error('email')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>

                <div class="input-field-container">
                    <label for="login-password">Mot de passe <span class="required">*</span></label>
                    <input type="password" id="login-password" name="password" placeholder="Mot de passe" 
                        required
                        aria-invalid="{{ $errors->has('password') ? 'true' : 'false' }}">
                    @error('password')
                        <span class="error-message">{{ $message }}</span>
                    @enderror
                </div>
                
                <a href="" class="forgot-password">Mot de passe oublié ?</a>

                <button type="submit" class="submit-button">
                    <span class="icon"><i class="fas fa-sign-in-alt"></i></span> Se connecter
                </button>
            </form>
        </div>
    </section>
</div>
    <script src="{{ asset('assets/js/auth.js') }}"></script>
</body>
</html>