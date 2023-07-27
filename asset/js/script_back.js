'use strict';


/* Menu - Dashboard */

; (function () {

    const btnOpenDashboard = document.querySelector('.btnOpenDashboard i');
    const btnCloseDashboard = document.querySelector('.btnCloseDashboard');
    const modalDashboard = document.querySelector('.modal-dashboard-left');
    let contentDashboard = document.querySelector('.menu-dashboard');
    let contentResponsiveDashboard = document.querySelector('.modal-mobile-left');

    contentResponsiveDashboard.innerHTML = contentDashboard.innerHTML;

    btnOpenDashboard.addEventListener('click', function () {
        modalDashboard.classList.add('active-menu-left');
        btnCloseDashboard.style.display = "block";
    });

    btnCloseDashboard.addEventListener('click', function () {
        modalDashboard.classList.remove('active-menu-left');
        btnCloseDashboard.style.display = "none";


    });

    window.onresize = function () {

        if (window.innerWidth > 992) {
            btnOpen.style.display = "none";
        }

        else {
            btnOpen.style.display = "block";
        }
    }

})()

    /* Bouton supprimer */

    ; (function () {

        let btnDelete = document.querySelector('.btnDelete');
        btnDelete.onclick = function () {
            return (confirm('Etes vous sur ?'));
        }

    })()