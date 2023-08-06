'use strict';


/* Formulaire Inscription */

; (function () {

    const nom = document.querySelector('#nom');
    const prenom = document.querySelector('#prenom');
    const email = document.querySelector('#email');
    const password = document.querySelector('#password');
    const confirmPassword = document.querySelector('#confirmPassword');
    const ville = document.querySelector('#ville');
    const code_postal = document.querySelector('#code_postal');
    const adresse = document.querySelector('#adresse');
    const telephone = document.querySelector('#telephone');
    const pays = document.querySelector('#pays');

    function check(field) {

        field.addEventListener('blur', function () {
            this.value = this.value.trim();
            if (this.value === "") {
                this.style.border = '2px solid red';
                this.nextElementSibling.textContent = "Veuillez remplir le champ";
                this.nextElementSibling.classList.remove('active-message');
                return false;
            } else {
                this.style.border = '2px solid #119d15';
                this.nextElementSibling.textContent = "";
                this.nextElementSibling.classList.add('active-message');
            }
        })
    }

    check(prenom);
    check(nom);
    check(email);
    check(password);
    check(confirmPassword);
    check(ville);
    check(code_postal);
    check(adresse);
    check(telephone);
    check(pays);

})()