'use strict';

import { listShow } from "./modules/module-liste";




/* Liste - Responsive */

; (function () {

    const liste = document.querySelectorAll(".table-responsive");
    const btnListe = document.querySelectorAll(".table-responsive i");
    const details = document.querySelectorAll(".table-details, .table-details-user, .table-details-dashboard, .table-details-newsletter, .table-details-commande, .table-details-panier");

    listShow(liste, btnListe, details);

})()
