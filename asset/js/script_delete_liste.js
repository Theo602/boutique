'use strict';


/* Bouton supprimer */

; (function () {

    let btnDelete = document.querySelectorAll('.btnDelete, .btnDesabonner');

    btnDelete.forEach(itemDelete => {
        itemDelete.onclick = function () {
            return (confirm('Etes vous sur de vouloir supprimer?'));
        }
    });

})()