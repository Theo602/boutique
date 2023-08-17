'use strict';

import { check } from "./modules/module-formulaire";


/* Formulaire Inscription */

; (function () {

    const prenom = document.querySelector('#prenom');
    const nom = document.querySelector('#nom');
    const email = document.querySelector('#email');
    const password = document.querySelector('#password');
    const confirmPassword = document.querySelector('#confirmPassword');
    const ville = document.querySelector('#ville');
    const code_postal = document.querySelector('#code_postal');
    const adresse = document.querySelector('#adresse');
    const telephone = document.querySelector('#telephone');
    const pays = document.querySelector('#pays');

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