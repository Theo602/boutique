'use strict';


/* Carrousel */

; (function () {

    let vitesse = parseInt(document.querySelector('#carrousel').dataset.vitesse) || 4000;

    /* On liste les images */

    let mesImages = document.querySelectorAll('#reglette figure');
    let nbImages = mesImages.length;

    /* On définit la largeur de la réglette */

    let reglette = document.getElementById('reglette');
    reglette.style.width = nbImages * 100 + '%';


    function reorder() {
        for (let i = 0; i < nbImages; i++) {
            mesImages[i].style.order = i;
            mesImages[i].style.width = 100 / nbImages + '%';
        }
    }

    reorder();

    let compteurImages = 0;
    let timer1 = setInterval(carrousel, vitesse);

    function carrousel() {

        let position = 0;
        let timer2 = setInterval(coulisse, 20);

        function coulisse() {

            if (position == -100) {

                clearInterval(timer2);
                mesImages[compteurImages].style.order = parseInt(mesImages[compteurImages].style.order) + nbImages;
                compteurImages++;
                reglette.style.left = 0;

                if (compteurImages == nbImages) {
                    compteurImages = 0;
                    reorder();
                }

            } else {
                position -= 2;
                reglette.style.left = position + '%';
            }
        }
    }

    let monCarrousel = document.querySelector('#carrousel');

    monCarrousel.addEventListener('mouseenter', function () {

        clearInterval(timer1);

    });

    monCarrousel.addEventListener('mouseleave', function () {

        timer1 = setInterval(carrousel, 4000);

    });

})()