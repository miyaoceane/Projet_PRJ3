document.addEventListener('DOMContentLoaded', function() {

    
    const MOIS = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre'];
    const today = new Date();
    today.setHours(0, 0, 0, 0);

    
    function hToDecimal(heureStr) {
        var parts = heureStr.split(':');
        return parseInt(parts[0]) + parseInt(parts[1]) / 60;
    }

    var HORAIRES = {};
    for (var jour in HORAIRES_BDD) {
        HORAIRES[jour] = [];
        for (var i = 0; i < HORAIRES_BDD[jour].length; i++) {
            HORAIRES[jour].push({
                d: hToDecimal(HORAIRES_BDD[jour][i].debut),
                f: hToDecimal(HORAIRES_BDD[jour][i].fin)
            });
        }
    }

    var RESERVATIONS = {};
    for (var r = 0; r < RESERVATIONS_bdd.length; r++) {
        var parts = RESERVATIONS_bdd[r].split(' ');
        var date = parts[0];
        var heure = parts[1].slice(0, 5);
        if (!RESERVATIONS[date]) {
        RESERVATIONS[date] = [];
        RESERVATIONS[date].push(heure);
        }
    }

    var anneeAff = today.getFullYear();
    var moisAff  = today.getMonth();
    var selDate  = null;
    var selSlot  = null;
    var selDur   = 0;

    

    function dateStr(d) {
        
        var mm = String(d.getMonth() + 1).padStart(2, '0');
        var dd = String(d.getDate()).padStart(2, '0');
        return d.getFullYear() + '-' + mm + '-' + dd;
    }

    function getCreneaux(date, duree_Min) {
        var day = date.getDay(); 
        var plages = HORAIRES[day] || [];
        var slots = [];
        for (var i = 0; i < plages.length; i++) {
            var p = plages[i];
            var h = p.d;
            while (h * 60 + duree_Min <= p.f * 60) {
                var hh = Math.floor(h);
                var mm = Math.round((h - hh) * 60);
                slots.push(String(hh).padStart(2, '0') + ':' + String(mm).padStart(2, '0'));
                h += 0.5; 
            }
        }
        return slots;
    }


    function afficherCalendrier() {
        document.getElementById('cal-title').textContent = MOIS[moisAff] + ' ' + anneeAff;
        var premier = new Date(anneeAff, moisAff, 1);
        var decalage = premier.getDay();
        decalage = (decalage === 0) ? 6 : decalage - 1; // lundi = 0
        var nbJours = new Date(anneeAff, moisAff + 1, 0).getDate();
        var grille = document.getElementById('cal-grid');
        grille.innerHTML = '';

        for (var i = 0; i < decalage; i++) {
            var vide = document.createElement('button');
            vide.type = 'button';
            vide.className = 'day-btn empty';
            grille.appendChild(vide);
        }

        for (var j = 1; j <= nbJours; j++) {
            var dt = new Date(anneeAff, moisAff, j);
            var btn = document.createElement('button');
            btn.type = 'button';
            btn.className = 'day-btn';
            btn.textContent = j;

            var estPasse  = dt < today;
            var day = dt.getDay();
            var estFerme  = !HORAIRES[day] || HORAIRES[day].length === 0;
            var estAujourd = dt.getTime() === today.getTime();
            var k = dateStr(dt);
            var slots  = getCreneaux(dt, selDur || 30);
            var pris   = RESERVATIONS[k] || [];
            var libres = slots.filter(function(s) { return pris.indexOf(s) === -1; });

            if (estAujourd) btn.classList.add('today');
            if (estPasse)   btn.classList.add('past');
            else if (estFerme) btn.classList.add('closed');
            else if (libres.length > 0) btn.classList.add('available');
            else btn.classList.add('complet');

            if (selDate && dateStr(selDate) === k) btn.classList.add('selected');

            if (!estPasse && !estFerme) {
                (function(date) {
                    btn.addEventListener('click', function() { choisirDate(date); });
                })(dt);
            }

            grille.appendChild(btn);
        }
    }

    function choisirDate(dt) {
        selDate = dt;
        selSlot = null;
        document.getElementById('heure_rdv').value = '';
        document.getElementById('confirm-bar').style.display = 'none';
        afficherCalendrier();

        var dur   = selDur || 30;
        var slots = getCreneaux(dt, dur);
        var pris  = RESERVATIONS[dateStr(dt)] || [];

        document.getElementById('slots-label').textContent =
            'Créneaux — ' + dt.toLocaleDateString('fr-FR', {weekday:'long', day:'numeric', month:'long'});

        var grille = document.getElementById('slots-grid');
        grille.innerHTML = '';

        if (slots.length === 0) {
            grille.innerHTML = '<p>Aucun créneau disponible ce jour.</p>';
        } 
        else {
            slots.forEach(function(s) {
                var b = document.createElement('button');
                b.type = 'button';
                b.className = 'slot-btn';
                b.textContent = s;
                if (pris.indexOf(s) !== -1) {
                    b.classList.add('taken');
                    b.disabled = true;
                } 
                else {
                    b.addEventListener('click', function() { choisirCreneau(s, b); });
                }
                grille.appendChild(b);
            });
        }

        document.getElementById('slots-section').style.display = 'block';
        document.getElementById('form-client').style.display = 'none';
        document.getElementById('err-date').textContent = '';
    }

    function choisirCreneau(s, btnClique) {
        selSlot = s;
        document.querySelectorAll('.slot-btn').forEach(function(b) {
            b.classList.remove('selected-slot');
        });
        btnClique.classList.add('selected-slot');


        document.getElementById('date_rdv').value  = dateStr(selDate);
        document.getElementById('heure_rdv').value = s;

        var sel = document.getElementById('service');
        var opt = sel.options[sel.selectedIndex];
        var nomService = opt.text.split('—')[0].trim();

        document.getElementById('confirm-info').innerHTML =
            '<strong>' + nomService + '</strong> — ' +
            selDate.toLocaleDateString('fr-FR', {weekday:'long', day:'numeric', month:'long'}) +
            ' à ' + s + ' — ' + opt.getAttribute('data-prix') + '€';

        document.getElementById('confirm-bar').style.display = 'block';
        document.getElementById('form-client').style.display = 'block';
        document.getElementById('err-slot').textContent = '';
    }



    document.getElementById('prev-btn').addEventListener('click', function() {
        moisAff--;
        if (moisAff < 0) { moisAff = 11; anneeAff--; }
        afficherCalendrier();
    });

    document.getElementById('next-btn').addEventListener('click', function() {
        moisAff++;
        if (moisAff > 11) { moisAff = 0; anneeAff++; }
        afficherCalendrier();
    });



    document.getElementById('service').addEventListener('change', function() {
        var opt = this.options[this.selectedIndex];
        if (opt.value) {
            selDur = parseInt(opt.getAttribute('data-dur')) || 30;
            document.getElementById('info-card').innerHTML =
                '<p><strong>' + opt.text.split('—')[0].trim() + '</strong></p>' +
                '<p>' + opt.getAttribute('data-prix') + '€ — ' + selDur + ' min</p>';
            document.getElementById('cal-section').style.display = 'block';
        } 
        else {
            selDur = 0;
            document.getElementById('info-card').innerHTML = '<p>Sélectionnez un service pour voir les disponibilités</p>';
            document.getElementById('cal-section').style.display = 'none';
        }
        selDate = null;
        selSlot = null;
        document.getElementById('slots-section').style.display = 'none';
        document.getElementById('confirm-bar').style.display = 'none';
        document.getElementById('form-client').style.display = 'none';
        document.getElementById('date_rdv').value  = '';
        document.getElementById('heure_rdv').value = '';
        afficherCalendrier();
    });


    document.getElementById('reservationForm').addEventListener('submit', function(e) {
        var valide = true;

        if (!document.getElementById('date_rdv').value) {
            document.getElementById('err-date').textContent = 'Veuillez choisir une date.';
            valide = false;
        }
        if (!document.getElementById('heure_rdv').value) {
            document.getElementById('err-slot').textContent = 'Veuillez choisir un créneau.';
            valide = false;
        }
        if (!document.getElementById('service').value) {
            document.getElementById('err-service').textContent = 'Veuillez choisir un service.';
            valide = false;
        }

       
        var champsTxt = ['nom', 'prenom'];
        champsTxt.forEach(function(id) {
            var input = document.getElementById(id);
            var err   = document.getElementById('err-' + id);
            if (!input.value.trim()) {
                err.textContent = 'Ce champ est obligatoire.';
                valide = false;
            } 
            else {
                err.textContent = '';
            }
        });

        var email = document.getElementById('email');
        var patternEmail = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!email.value.trim()) {
            document.getElementById('err-email').textContent = 'Ce champ est obligatoire.';
            valide = false;
        } else if (!patternEmail.test(email.value)) {
            document.getElementById('err-email').textContent = 'Email invalide.';
            valide = false;
        } else {
            document.getElementById('err-email').textContent = '';
        }

        var tel = document.getElementById('telephone');
        if (!tel.value.trim()) {
            document.getElementById('err-tel').textContent = 'Ce champ est obligatoire.';
            valide = false;
        } else if (!/^[0-9]{10}$/.test(tel.value)) {
            document.getElementById('err-tel').textContent = 'Téléphone invalide (10 chiffres).';
            valide = false;
        } else {
            document.getElementById('err-tel').textContent = '';
        }

        if (!valide) e.preventDefault();
    });

    afficherCalendrier();

});


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
        this.form.submit();
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

