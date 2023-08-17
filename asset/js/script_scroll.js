'use strict';

/* Scroll flêche retour début de la page */

; (function () {

    const modalScroll = document.querySelector(".modal-scroll")
    const firstElement = document.documentElement;

    window.addEventListener('scroll', function () {

        let hauteur = document.documentElement.scrollTop;
        let windowBreakPoint = this.outerWidth;

        if (hauteur > 400 && windowBreakPoint <= 992) {
            modalScroll.style.display = "flex";
        } else {
            modalScroll.style.display = "none";
        }

    })

    modalScroll.addEventListener('click', function () {
        firstElement.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    })

})()