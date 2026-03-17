class FormValidator {
    constructor(formId) {
        this.form = document.getElementById(formId);
        this.fields = {};
        this.init();
    }
    
    init() {
        // Récupérer tous les champs avec validation
        const inputs = this.form.querySelectorAll('input[required], input[pattern]');
        
        inputs.forEach(input => {
            // Validation en temps réel pendant la saisie
            input.addEventListener('input', (e) => {
                this.validateField(e.target);
            });
            
            // Validation à la perte du focus
            input.addEventListener('blur', (e) => {
                this.validateField(e.target);
            });
        });
        
        // Validation à la soumission
        this.form.addEventListener('submit', (e) => {
            e.preventDefault();
            this.validateForm();
        });
    }
    
    validateField(field) {
        const errorSpan = field.parentElement.querySelector('.error-message');
        let isValid = true;
        let errorMessage = '';
        
        // Vérification required
        if (field.hasAttribute('required') && !field.value.trim()) {
            isValid = false;
            errorMessage = 'Ce champ est obligatoire';
        }
        
        // Vérification pattern
        else if (field.hasAttribute('pattern')) {
            const pattern = new RegExp(field.getAttribute('pattern'));
            if (!pattern.test(field.value)) {
                isValid = false;
                errorMessage = this.getPatternError(field);
            }
        }
        
        // Vérification type email
        else if (field.type === 'email') {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailPattern.test(field.value)) {
                isValid = false;
                errorMessage = 'Format d\'email invalide';
            }
        }
        
        // Vérification minlength
        else if (field.hasAttribute('minlength')) {
            const minLength = parseInt(field.getAttribute('minlength'));
            if (field.value.length < minLength) {
                isValid = false;
                errorMessage = `Minimum ${minLength} caractères requis`;
            }
        }
        
        // Mise à jour visuelle
        if (isValid) {
            field.classList.remove('invalid');
            field.classList.add('valid');
            errorSpan.textContent = '';
        } else {
            field.classList.remove('valid');
            field.classList.add('invalid');
            errorSpan.textContent = errorMessage;
        }
        
        return isValid;
    }
    
    getPatternError(field) {
        const fieldName = field.name;
        const errors = {
            'city': 'Seules les lettres, espaces et tirets sont autorisés',
            'postalCode': 'Le code postal doit contenir 5 chiffres',
            'phone': 'Format de téléphone invalide'
        };
        return errors[fieldName] || 'Format invalide';
    }
    
    validateForm() {
        const inputs = this.form.querySelectorAll('input[required], input[pattern]');
        let isFormValid = true;
        
        inputs.forEach(input => {
            if (!this.validateField(input)) {
                isFormValid = false;
            }
        });
        
        if (isFormValid) {
            this.submitForm();
        } else {
            this.showFormError('Veuillez corriger les erreurs avant de soumettre');
        }
    }
    
    submitForm() {
        const formData = new FormData(this.form);
        const data = Object.fromEntries(formData);
        console.log('Données valides :', data);
        
        // Afficher un message de succès
        this.showSuccess('Formulaire soumis avec succès !');
    }
    
    showSuccess(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-success';
        alert.textContent = message;
        this.form.prepend(alert);
        
        setTimeout(() => alert.remove(), 3000);
    }
    
    showFormError(message) {
        const alert = document.createElement('div');
        alert.className = 'alert alert-error';
        alert.textContent = message;
        this.form.prepend(alert);
        
        setTimeout(() => alert.remove(), 3000);
    }
}

// Initialisation
const validator = new FormValidator('reservationForm');