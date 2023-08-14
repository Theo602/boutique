'use strict';


import { deleteBtn } from "./module-delete";
import { listShow } from "./module-liste";

/* Requête Ajax pour filtrer les différentes recherches */


async function fetchSearch(search, url) {
    const response = await fetch(url, {
        method: "POST",
        body: JSON.stringify(search)
    });

    const data = await response.text();
    //console.log('response', data)

    const tableFilter = document.querySelector(".search");
    const pagination = document.querySelector('.barre-pagination');

    tableFilter.innerHTML = data;

    if (pagination != null) {
        pagination.style.display = "none";
    }

    const liste = document.querySelectorAll(".table-responsive");
    const btnListe = document.querySelectorAll(".table-responsive i");
    const details = document.querySelectorAll(" .table-details-commande, .table-details, .table-details-user");

    listShow(liste, btnListe, details);

    let btnDelete = document.querySelectorAll('.btnDelete');

    if (btnDelete.length !== 0) {
        deleteBtn(btnDelete);
    }

}

export { fetchSearch };