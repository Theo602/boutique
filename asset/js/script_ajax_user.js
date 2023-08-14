'use strict';


import { fetchSearch } from "./modules/module-fetch";

/* Filtrages des produits */

; (function () {

    const selectSearch = document.querySelector('#search-select');
    const userSearch = document.querySelector('#role_user');

    selectSearch.addEventListener('change', function () {

        const selectValue = selectSearch.value;
        const userValue = userSearch.value;

        let search = {
            search: userValue,
            status: selectValue
        }

        fetchSearch(search, "http://localhost/boutique/config/ajax/ajax-user");
    });

})()