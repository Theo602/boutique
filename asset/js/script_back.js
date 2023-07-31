'use strict';


/* Menu - Responsive */

; (function () {

    const containerOpen = document.querySelector('.btnOpen');
    const containerClose = document.querySelector('.btnClose');
    const btnOpen = document.querySelector('.btnOpen i');
    const btnClose = document.querySelector('.btnClose i');
    const modal = document.querySelector('.modal-menu');
    let content = document.querySelector('.menu-dashboard');
    let contentResponsive = document.querySelector('.modal-mobile');

    contentResponsive.innerHTML = content.innerHTML;

    btnOpen.addEventListener('click', function (event) {

        event.preventDefault();

        containerClose.style.display = "block";
        containerOpen.style.display = "none";

        if (!modal.classList.contains('active-menu')) {

            modal.classList.add('active-menu');
            modal.style.height = 'auto';

            let height = modal.clientHeight + 'px';

            modal.style.height = '0px';

            setTimeout(function () {
                modal.style.height = height;
            }, 0);

        }

    });

    btnClose.addEventListener('click', function (event) {

        event.preventDefault();

        modal.style.height = '0px';
        containerClose.style.display = "none";
        containerOpen.style.display = "block";

        modal.addEventListener('transitionend', function () {
            modal.classList.remove('active-menu');
        }, {
            once: true
        });
    });

})()