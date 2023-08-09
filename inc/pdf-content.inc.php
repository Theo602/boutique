 <!DOCTYPE html>
 <html lang="fr">

 <head>
     <meta charset="UTF-8">
     <meta http-equiv="X-UA-Compatible" content="IE=edge">
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="description" content="Télécharger la facture de votre commande.">
     <title>Facture - Commande n° <?= $commande_reference ?></title>

     <!-- Css -->

     <link rel="stylesheet" href="<?= URL ?>asset/css/style_pdf.css">

 </head>

 <body id="<?= MEMBER_FACTURE_COMMANDE ?>">

     <main class="container">

         <section class="section-1-pdf">

             <h1>Boutique T-Commerce</h1>

             <p>
                 24 rue de la foret <br>
                 35000 Rennes
             </p>

             <hr>

             <h2>Commande n° <?= $commande_reference; ?></h2>
             <hr>

         </section>

         <section class="section-2-pdf">

             <div class="background-pdf">

                 <div class="information-pdf">

                     <h3>Mon récapitulatif</h3>

                     <hr>

                     <div class="resume-pdf">

                         <div class="fiche-information-pdf">

                             <h3>Information</h3>
                             <hr>

                             <p>Commande passée le <?= $date_commande; ?></p>

                             <p>Référence : <?= $commande_reference ?></p>
                             <p>Facture : <?= $commande_facture ?></p>
                         </div>

                         <div class="fiche-information-pdf">

                             <h3>Adresse de livraison</h3>
                             <hr>

                             <p><?= $adresse_livraison; ?></p>

                         </div>

                         <div class="fiche-information-pdf">

                             <h3>Adresse de facturation</h3>
                             <hr>

                             <p><?= $adresse_livraison; ?></p>

                         </div>

                         <div class="fiche-information-pdf">

                             <h3>Mode de livraison</h3>
                             <hr>

                             <p><?= $livraison; ?></p>

                         </div>

                     </div>

                 </div>

                 <div class="list-pdf clear">

                     <h3>Produits commandés</h3>
                     <hr>

                     <table>

                         <thead>

                             <tr class="table-top-pdf">

                                 <th>Photo</th>
                                 <th>Référence</th>
                                 <th>Produit</th>
                                 <th>Quantité</th>
                                 <th>Prix</th>
                                 <th>Total</th>

                             </tr>

                         </thead>
                         <tbody>

                             <?php while ($detail = $query->fetch(PDO::FETCH_ASSOC)) : extract($detail); ?>

                                 <tr class="table-details-pdf">

                                     <td data-label="Photo">

                                         <figure>
                                             <img src="<?= $photo_produit; ?>">
                                         </figure>

                                     </td>

                                     <td><?= $reference_produit; ?></td>
                                     <td><?= ucfirst($nom_produit); ?></td>
                                     <td><?= $detail_quantite; ?></td>
                                     <td><?= $detail_prix; ?> €</td>
                                     <td><?= $detail_total; ?> €</td>

                                 </tr>

                             <?php endwhile; ?>

                         </tbody>

                     </table>

                     <p class="panier-total-pdf">Total HT : <?= $total_ht; ?>€</p>
                     <p class="panier-total-pdf">Total TVA (20%) : <?= tauxTva($total_ht); ?>€</p>
                     <p class="panier-total-pdf">Total TTC : <?= $total_ttc; ?>€</p>

                 </div>

             </div>

         </section>

     </main>

 </body>

 </html>