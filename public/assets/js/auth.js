document.addEventListener('DOMContentLoaded', function() {
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    function showForm(formToShowId) {
        if (formToShowId === 'login-form') {
            loginTab.classList.add('active');
            loginTab.setAttribute('aria-selected', 'true');
            registerTab.classList.remove('active');
            registerTab.setAttribute('aria-selected', 'false');
            loginForm.style.display = 'block';
            registerForm.style.display = 'none';
        } else {
            registerTab.classList.add('active');
            registerTab.setAttribute('aria-selected', 'true');
            loginTab.classList.remove('active');
            loginTab.setAttribute('aria-selected', 'false');
            registerForm.style.display = 'block';
            loginForm.style.display = 'none';
        }
    }

    loginTab?.addEventListener('click', (e) => {
        e.preventDefault();
        showForm('login-form');
    });

    registerTab?.addEventListener('click', (e) => {
        e.preventDefault();
        showForm('register-form');
    });

    // Initialiser avec le formulaire d'inscription actif
    showForm('register-form');
});