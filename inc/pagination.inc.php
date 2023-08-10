<?php if ($result['nb_row'] > $result['limit']) : ?>

    <nav class="barre-pagination">
        <ul class="pagination">

            <li class="<?= ($currentPage == 1) ? "disabled" : "" ?>">
                <a href="<?= $url_page . $currentPage - 1; ?>"><i class="fas fa-arrow-left"></i></a>
            </li>

            <?php for ($page = 1; $page <= $result['pages']; $page++) : ?>

                <li class="<?= ($currentPage == $page) ? "activePage" : "" ?>">
                    <a href="<?= $url_page . $page; ?>">
                        <?= $page; ?>
                    </a>
                </li>

            <?php endfor; ?>

            <li class="<?= ($currentPage == $result['pages']) ? "disabled" : "" ?>">
                <a href="<?= $url_page . $currentPage + 1; ?>"><i class="fas fa-arrow-right"></i></a>
            </li>


        </ul>

    </nav>

<?php endif ?>