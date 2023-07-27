<?php


function userConnected()
{
    if (!isset($_SESSION['user'])) {
        return false;
    } else {
        return true;
    }
}

function userIsAdmin()
{
    if (userConnected() && $_SESSION['user']['status'] == 1) {
        return true;
    } else {
        return false;
    }
}
