'use strict';


import { fetchSearch } from "./modules/module-fetch";

/* Filtrages des produits */

; (function () {

    const selectSearch = document.querySelector('#search-select');
    const produitSearch = document.querySelector('#produit_search');

    selectSearch.addEventListener('change', function () {

        const selectValue = selectSearch.value;
        const produitValue = produitSearch.value;

        let search = {
            search: produitValue,
            categorie: selectValue
        }

        fetchSearch(search, "http://localhost/boutique/config/ajax/ajax-produit");
    });

})()