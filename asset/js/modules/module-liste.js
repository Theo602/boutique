'use strict';


/* Fonction pour les listes responsives */

function listShow(liste, btnListe, details) {

    liste.forEach((liste, index) => {

        liste.addEventListener('click', function () {
            liste.classList.toggle('active-liste');
            details[index].classList.toggle('active-details');
            btnListe[index].classList.toggle('fa-chevron-up');
        });

    })

    window.onresize = function () {

        if (window.innerWidth > 1250) {
            liste.forEach((liste) => {
                liste.classList.remove('active-liste');
            })

            details.forEach((details) => {
                details.classList.remove('active-details');
            })

            btnListe.forEach((btn) => {
                btn.classList.remove('fa-chevron-up');
            })
        }
    }
}

export { listShow };