</div>

</main>

<footer>

    <div class="footer-copyright">

        <p class="text-copyright"><i class="fa fa-copyright"></i> <?php echo date('Y'); ?> - Boutique E-commerce</p>

    </div>

</footer>

<?php if (isset($bodyId)) : ?>

    <?php if ($bodyId === ADMIN_BOUTIQUE || $bodyId === ADMIN_COMPTE_MEMBRE || $bodyId === ADMIN_LISTE_COMMANDE || $bodyId === ADMIN_GESTION_NEWSLETTER || $bodyId === ADMIN_COMPTE || $bodyId === ADMIN_COMMANDE || $bodyId === ADMIN_COMMANDE_MEMBER || $bodyId === ADMIN_DASHBOARD) : ?>
        <script type="module" src="../asset/js/script_liste.js"></script>

    <?php endif ?>

    <?php if ($bodyId === ADMIN_BOUTIQUE || $bodyId === ADMIN_COMPTE_MEMBRE || $bodyId === ADMIN_LISTE_COMMANDE || $bodyId === ADMIN_GESTION_NEWSLETTER || $bodyId === ADMIN_LISTE_COMMANDE) : ?>
        <script src="../asset/js/script_delete_liste.js"></script>
    <?php endif ?>

    <?php if ($bodyId === ADMIN_DASHBOARD || $bodyId === ADMIN_LISTE_COMMANDE || $bodyId === ADMIN_COMPTE || $bodyId === ADMIN_COMMANDE) : ?>
        <script type="module" src="../asset/js/script_ajax_search.js"></script>
    <?php endif ?>

<?php endif ?>


<script src="../asset/js/script_dashboard.js"></script>

</body>

</html>