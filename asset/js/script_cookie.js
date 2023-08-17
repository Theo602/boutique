'use strict';


/* Bannière cookie */

; (function () {

    const btnAccept = document.querySelector('.btn-accept');
    const btnDeny = document.querySelector('.btn-deny');
    const cookies = document.querySelector('.cookies');

    function banniereCookie() {

        btnAccept.addEventListener('click', function () {

            cookies.style.opacity = "0";

            setTimeout(function () {
                cookies.style.display = "none";
            }, 300);

            localStorage.setItem('banniere', 'accept');
        });

        btnDeny.addEventListener('click', function () {
            cookies.style.opacity = "0";

            setTimeout(function () {
                cookies.style.display = "none";
            }, 300);

            localStorage.setItem('banniere', 'deny');
        });
    }

    banniereCookie();

    let keyBanniereCookie = localStorage.getItem('banniere');

    function checkBanniere() {

        if (keyBanniereCookie) {
            cookies.style.display = "none";
        } else {
            cookies.style.display = "flex";
        }
    }

    checkBanniere();

})()