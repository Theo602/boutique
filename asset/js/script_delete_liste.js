'use strict';


import { deleteBtn } from "./modules/module-delete";


/* Bouton supprimer */

; (function () {

    let btnDelete = document.querySelectorAll('.btnDelete, .btnDesabonner');

    deleteBtn(btnDelete);

})()