'use strict';


/* Fonction de saisie pour les formulaire */

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

export { check };