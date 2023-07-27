    </main>

    <footer>

        <div class="footer-copyright">

            <p class="text-copyright"><i class="fa fa-copyright"></i> <?php echo date('Y'); ?> - Boutique E-commerce</p>

        </div>

    </footer>

    <?php if (isset($bodyId) && $bodyId === HOME_PAGE) : ?>
        <script src="<?php URL; ?>asset/js/script_carrousel.js"></script>
    <?php endif ?>

    <?php if (isset($bodyId) && $bodyId === SIGN_IN) : ?>
        <script src="<?php URL; ?>asset/js/script_form.js"></script>
    <?php endif ?>

    <script src="<?php URL; ?>asset/js/script.js"></script>

    </body>

    </html>