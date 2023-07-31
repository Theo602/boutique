'use strict';

/* Liste - Responsive */

; (function () {

    const liste = document.querySelectorAll(".table-responsive")
    const btnListe = document.querySelectorAll(".table-responsive i")
    const details = document.querySelectorAll(".table-details, .table-details-user, .table-details-dashboard ")

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



})()

    /* Bouton supprimer */

    ; (function () {

        let btnDelete = document.querySelectorAll('.btnDelete');

        btnDelete.forEach(itemDelete => {
            itemDelete.onclick = function () {
                return (confirm('Etes vous sur de vouloir supprimer?'));
            }
        });

    })()