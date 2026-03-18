// script.js
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reservationForm');

    form.addEventListener('submit', function(e) {
        let valid = true;

        // Vérification simple des champs requis
        form.querySelectorAll('input').forEach(function(input) {
            const errorSpan = input.parentElement.querySelector('.error-message');
            if (!input.value.trim()) {
                valid = false;
                errorSpan.textContent = 'Ce champ est obligatoire';
                input.classList.add('alert alert-danger');
            } else {
                errorSpan.textContent = '';
                input.classList.remove('alert alert-danger');
            }
        });

        // Vérification email
        const email = form.querySelector('#email');
        const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const emailError = email.parentElement.querySelector('.error-message');
        if (email.value && !emailPattern.test(email.value)) {
            valid = false;
            emailError.textContent = 'Email alert alert-dangere';
            email.classList.add('alert alert-danger');
        }

        // Vérification téléphone 10 chiffres
        const tel = form.querySelector('#telephone');
        const telError = tel.parentElement.querySelector('.error-message');
        if (tel.value && !/^[0-9]{10}$/.test(tel.value)) {
            valid = false;
            telError.textContent = 'Téléphone alert alert-dangere (10 chiffres)';
            tel.classList.add('alert alert-danger');
        }

        // Si invalide, empêcher la soumission
        // if (!valid) {e.preventDefault();}
    });
});




