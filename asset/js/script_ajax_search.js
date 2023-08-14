'use strict';

import { listShow } from "./modules/module-liste";

/* Select search état des commande*/

; (function () {

    const selectSearch = document.querySelector('#search-select');
    const commandeSearch = document.querySelector('#commande_search');
    const userSearch = document.querySelector('#id_search');
    const tableFilter = document.querySelector(".search");


    selectSearch.addEventListener('change', function () {
        const selectValue = selectSearch.value;
        const commandeValue = commandeSearch.value;
        const userValue = userSearch.value;

        let search = {
            search: commandeValue,
            user: userValue,
            status: selectValue
        }
        console.log('search', search)
        fetchSearch(search);
    });



    async function fetchSearch(search) {
        const response = await fetch("http://localhost/boutique/config/ajax/ajax", {
            method: "POST",
            body: JSON.stringify(search)
        });

        const data = await response.text();
        //console.log('response', data)

        tableFilter.innerHTML = data;

        const liste = document.querySelectorAll(".table-responsive");
        const btnListe = document.querySelectorAll(".table-responsive i");
        const details = document.querySelectorAll(" .table-details-commande");

        listShow(liste, btnListe, details);



    }

})()