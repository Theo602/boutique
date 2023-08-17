'use strict';


/* Fonction pour les boutons supprimer des listes et de la newsletter */

function deleteBtn(btn) {

    btn.forEach(itemDelete => {

        itemDelete.onclick = function () {
            return (confirm('Etes vous sur de vouloir supprimer?'));
        }
    });

}

export { deleteBtn };