'use strict';


/* Menu - Responsive */

; (function () {

    const btnOpen = document.querySelector('.btnOpen');
    const btnClose = document.querySelector('.btnClose');
    const modal = document.querySelector('.modal-navigation');
    let content = document.querySelector('.menu-navigation');
    let contentResponsive = document.querySelector('.modal-mobile');

    contentResponsive.innerHTML = content.innerHTML;

    btnOpen.addEventListener('click', function () {

        modal.classList.add('active-menu');
        btnClose.style.display = "block";

    });

    btnClose.addEventListener('click', function () {

        modal.classList.remove('active-menu');
        btnClose.style.display = "none";

    });

})()

    /* MouseOver Mouseout - Block - fiche-release - fiche-band - fiche-shop - fiche-news */

    ; (function () {
        const blockFiche = document.querySelectorAll('.fiche-produit, .fiche-categorie');

        blockFiche.forEach(block => {
            block.addEventListener('mouseover', function () {
                block.classList.add('activeBlock')
            })

            block.addEventListener('mouseout', function () {
                block.classList.remove('activeBlock')
            })

        })

    })()