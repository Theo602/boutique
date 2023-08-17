<?php require_once('inc/banniere-cookie.inc.php');  ?>
<?php require_once('inc/scroll.inc.php');  ?>

</main>

<footer>

    <div class="footer-copyright">

        <p class="text-copyright"><i class="fa fa-copyright"></i> <?php echo date('Y'); ?> - Boutique E-commerce</p>

    </div>

</footer>

<?php if (isset($bodyId)) : ?>

    <?php if ($bodyId === HOME_PAGE) : ?>
        <script src="asset/js/script_carrousel.js"></script>
    <?php endif ?>

    <?php if ($bodyId === SIGN_IN) : ?>
        <script type="module" src="asset/js/script_inscription.js"></script>
    <?php endif ?>

    <?php if ($bodyId === CONTACT) : ?>
        <script type="module" src="asset/js/script_contact.js"></script>
    <?php endif ?>

    <?php if ($bodyId === MEMBER_COMPTE  || $bodyId === MEMBER_PROFILE || $bodyId === MEMBER_COMMANDE) : ?>
        <script src="asset/js/script_dashboard.js"></script>
    <?php endif ?>

    <?php if (
        $bodyId === PROCESS_COMMANDE || $bodyId === PANIER || $bodyId === MEMBER_DETAIL_COMMANDE || $bodyId === MEMBER_COMMANDE
        || $bodyId === MEMBER_COMPTE
    ) : ?>
        <script type="module" src="asset/js/script_liste.js"></script>
    <?php endif ?>

    <?php if ($bodyId === MEMBER_COMPTE) : ?>
        <script type="module" src="asset/js/script_delete_liste.js"></script>
    <?php endif ?>


    <?php if ($bodyId === MEMBER_COMPTE || $bodyId === MEMBER_COMMANDE) : ?>
        <script type="module" src="asset/js/script_ajax_search.js"></script>
    <?php endif ?>

<?php endif ?>

<script src="asset/js/script.js"></script>
<script src="asset/js/script_search_barre.js"></script>
<script src="asset/js/script_cookie.js"></script>
<script src="asset/js/script_scroll.js"></script>
</body>

</html>