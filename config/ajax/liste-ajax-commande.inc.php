    <?php if (!empty($commandes)) : ?>

        <?php foreach ($commandes as $commande) : extract($commande); ?>

            <tr class="table-responsive">

                <td>Commande nᵒ <?= $id_commande; ?></td>
                <td><i class="fas fa-chevron-down"></td>

            </tr>

            <tr class="table-details-commande">
                <td data-label="Commande nᵒ"><?= $id_commande; ?></td>
                <td data-label="Référence"><?= $reference; ?></td>
                <td data-label="Client"><?= ($email) ? $email : 'Commande archivée' ?></td>
                <td data-label="Date"><?= $date_commande; ?></td>
                <td data-label="Total"><?= $total_ttc; ?></td>
                <td data-label="Status">
                    <p class="table-etat <?php
                                            if ($etat === 'livré') {
                                                echo 'etat-livrer';
                                            } elseif ($etat === 'envoyé') {
                                                echo 'etat-envoyer';
                                            } elseif ($etat === 'en cours de traitement') {
                                                echo 'etat-pending';
                                            } elseif ($etat === 'annulé') {
                                                echo 'etat-annuler';
                                            } else {
                                                echo 'etat-payer';
                                            }
                                            ?>">
                        <?= ucfirst($etat); ?>
                    </p>
                </td>
                <td data-label="Option">

                    <?php if ($data["search"] === 'commande_dashboard') : ?>

                        <a href="membre_detail_commande.php?commande=<?= $id_commande; ?>" title="Voir"><i class="fa fa-eye"></i></a>

                    <?php elseif ($data["search"] === 'commande_client') : ?>

                        <a href="membre_detail_commande.php?commande=<?= $id_commande; ?>" title="Voir"><i class="fa fa-eye"></i></a>
                        <a href="../facture-commande.php?commande=<?= $id_commande; ?>" title="Facture"><i class="fas fa-file-pdf"></i></a>
                        <a href="gestion_commande.php?action=edit&commande=<?= $id_commande ?>" title="Modifier"><i class="fas fa-pencil"></i></a>
                        <a class="btnDelete" href="gestion_commande.php?action=suppression&commande=<?= $id_commande ?>" title="Supprimer"> <i class="fa fa-trash-can"></i></a>

                    <?php elseif ($data["search"] === 'commande_compte' || $data["search"] === 'commande_user') : ?>

                        <a href="../detail-commande.php?commande=<?= $id_commande; ?>" title="Voir"><i class="fa fa-eye"></i></a>
                        <a href="../facture-commande.php?commande=<?= $id_commande; ?>" title="Facture"><i class="fas fa-file-pdf"></i></a>

                    <?php endif; ?>

                </td>
            </tr>

        <?php endforeach; ?>

    <?php else : ?>

        <tr class="td-empty">
            <td colspan="7">Aucune commande</td>
        </tr>

    <?php endif; ?>