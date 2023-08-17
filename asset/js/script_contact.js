'use strict';

/* Formulaire de contact */

import { check } from "./modules/module-formulaire";

; (function () {

    const prenom = document.querySelector('#prenom');
    const nom = document.querySelector('#nom');
    const email = document.querySelector('#email');
    const sujet = document.querySelector('#sujet');
    const message = document.querySelector('#message');


    check(prenom);
    check(nom);
    check(email);
    check(sujet);
    check(message);


})()