'use strict';


import { fetchSearch } from "./modules/module-fetch";

/* Filtrages des status des commande*/

; (function () {

    const selectSearch = document.querySelector('#search-select');
    const commandeSearch = document.querySelector('#commande_search');
    const userSearch = document.querySelector('#id_search');

    selectSearch.addEventListener('change', function () {
        const selectValue = selectSearch.value;
        const commandeValue = commandeSearch.value;
        const userValue = userSearch.value;

        let search = {
            search: commandeValue,
            user: userValue,
            status: selectValue
        }

        fetchSearch(search, "http://localhost/boutique/config/ajax/ajax-commande");
    });

})()