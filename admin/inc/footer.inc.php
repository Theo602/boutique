</div>

</main>

<footer>

    <div class="footer-copyright">

        <p class="text-copyright"><i class="fa fa-copyright"></i> <?php echo date('Y'); ?> - Boutique E-commerce</p>

    </div>

</footer>

<?php if (isset($bodyId)) : ?>
    <?php if ($bodyId === ADMIN_BOUTIQUE  || $bodyId === ADMIN_COMPTE || $bodyId === ADMIN_COMMANDE) : ?>
        <script src="../asset/js/script_back_liste.js"></script>
    <?php endif ?>
<?php endif ?>

<script src="../asset/js/script_back.js"></script>

</body>

</html>