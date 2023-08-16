'use strict';

/* Recherche dans la barre de recherche */

; (function () {

    const formSearch = document.querySelector('.formSearch');
    const produitSearch = document.querySelector('#search');
    const resultSearch = document.querySelector('#result-search');

    formSearch.addEventListener('submit', function (event) {
        event.preventDefault();

        const search = produitSearch.value;

        fetchSearch(search);
    });

    async function fetchSearch(search) {

        if (search !== '') {
            const response = await fetch(`http://localhost/boutique/config/ajax/ajax-barre?search=${search}`, {
                method: "GET",
            });

            const data = await response.text();
            //console.log('response', data);

            resultSearch.style.display = "block";
            resultSearch.innerHTML = data;

            setTimeout(() => {
                resultSearch.classList.remove('hide');
                resultSearch.classList.add('show');
            }, 200)


        } else {
            const resultNotFount = `
                                    <div class="result">
                                    <p class="result-no-found">Veuillez saisir une recherche</p>
                                    </div>
                                    `
            resultSearch.style.display = "block";
            resultSearch.innerHTML = resultNotFount;
        }

        let removeSearch = document.querySelectorAll('.formSearch, .result, .result-no-found, .result-found, #result-search, #search, .btnSearch');
        removeSearch = Array.from(removeSearch)

        window.onclick = (e) => {

            let arraySearch = removeSearch.includes(e.target);

            if (!arraySearch) {
                resultSearch.style.display = "none";
            }
        }

    }

})()