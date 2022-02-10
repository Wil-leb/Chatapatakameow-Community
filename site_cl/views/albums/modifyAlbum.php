<?php require "./controller/AlbumFormController.php"; ?>

<section class="container">
    <h1>Modification d'album</h1>

    <?php if(!$_POST) : ?>
        <p>Bienvenue à la page de modification d'album&nbsp;! Ici, tu pourras modifier le contenu de l'album choisi et lui retirer ou ajouter des images.</p>
    <?php endif; ?>

    <?php if(empty($modifMessages["success"])) : ?>
        <?php if(!empty($modifMessages["errors"])) { ?>
            <ul class="error">
                <?php foreach($modifMessages["errors"] as $error) : ?>    
                    <li><?= $error ?></li>
                <?php endforeach; ?>    
            </ul>
        <?php } ?>
    
    <?php else : ?>
        <p class="success"><?= $modifMessages["success"][0] ?></p>
    <?php endif; ?>
    
    <?php if(!empty($deleteMessages["success"])) : ?>
        <p class="success"><?= $deleteMessages["success"][0] ?></p>
    <?php endif; ?>

    <?php if(empty($replaceMessages["success"])) : ?>
        <?php if(!empty($replaceMessages["errors"])) { ?>
            <ul class="error">
                <?php foreach($replaceMessages["errors"] as $error) : ?>    
                    <li><?= $error ?></li>
                <?php endforeach; ?>    
            </ul>
        <?php } ?>
    
    <?php else : ?>
        <p class="success"><?= $replaceMessages["success"][0] ?></p>
    <?php endif; ?>

    <?php if(empty($extraPicMessages["success"])) : ?>
        <?php if(!empty($extraPicMessages["errors"])) { ?>
            <ul class="error">
                <?php foreach($extraPicMessages["errors"] as $error) : ?>    
                    <li><?= $error ?></li>
                <?php endforeach; ?>    
            </ul>
        <?php } ?>
    
    <?php else : ?>
        <p class="success"><?= $extraPicMessages["success"][0] ?></p>
    <?php endif; ?>
</section>

<?php if(!$_POST) : ?>
    <section class="container">
        <h2>Description de l'album</h2>

        <p class="mandatory">Tous les champs sont obligatoires. Modifie le contenu désiré et soumets le formulaire pour mettre l'album à jour.</p>
        
        <form enctype="multipart/form-data" action="index.php?p=modifyAlbum&albumId=<?= htmlspecialchars($findAlbum["id"]) ?>" method="post" onsubmit="confirmChange(event)">
            <input type="text" name="title" class="album-title" value="<?= htmlspecialchars(trim($findAlbum["title"])) ?>"  maxlength="30" placeholder="Titre (30 caractères)">
            <div><?= 30 - strlen(htmlspecialchars(trim($findAlbum["title"]))) ?> caractères restants</div>
            <div></div>
            
            <textarea name="description" class="album-description" rows="5" cols="60" maxlength="200" placeholder="Description (200 caractères)"><?= htmlspecialchars(trim($findAlbum["description"])) ?></textarea>
            <div><?= 200 - strlen(htmlspecialchars(trim($findAlbum["description"]))) ?> caractères restants</div>
            <div></div>

            <div class="cover-modif">
                <p>Couverture actuelle</p>
                <?php require "assets/php/CoverReplacement.php"; ?>
            </div>

            <label for="cover">Nouvelle couverture</label>
            <input type="text" name="coverName" value="<?= htmlspecialchars(trim($currentCover["cover_name"])) ?>" hidden>
            <input type="file" name="cover" id="album-cover" accept=".jpg, .jpeg, .png" title="Fichier .jp(e)g / .png ne dépassant pas 3 Mo"">
            <div>Aperçu nouvelle couverture</div>
            <div>Taille nouvelle couverture</div>

            <div class="rules">
                <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules">règlement général</a></label>	
                <input type="checkbox" value="true" name="acceptRules">
            </div>

            <div class="rules">
                <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy">politique de confidentialité</a></label>	
                <input type="checkbox" value="true" name="acceptPolicy">
            </div>
            
            <input type="submit" name="albumChanges" value="Confirmer la/les modification(s)">
        </form>
    </section>

    <section class="container">
        <h2>Image(s) actuelle(s) de l'album</h2>
        
        <?php if(empty($currentPictures)) : ?>
            <p class="no-content">Cet album ne contient aucune image.</p>
        <?php else : ?>
            <table class="picture-replacement">
                <thead>
                    <tr>
                        <th>Image(s) actuelle(s)</th>
                        <th>Remplacement d'image</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach($currentPictures as $currentPicture) : ?>
                        <tr>
                            <td data-label="Image" class="picture-modif">
                                <?php require "assets/php/ImageReplacement.php"; ?>
                                <form action="index.php?p=modifyAlbum&albumId=<?= htmlspecialchars($findAlbum["id"]) ?>" method="post" onsubmit="confirmDeletion(event)">
                                    <input type="text" name="pictureId" value="<?= htmlspecialchars($currentPicture["id"]) ?>" hidden>
                                    <input type="text" name="pictureName" value="<?= htmlspecialchars(trim($currentPicture["picture_name"])) ?>" hidden>
                                    <button class="delete" type="submit" name="deletePicture"><i class="fas fa-trash-alt"></i>Supprimer</button>
                                </form>
                            </td>
                            <td data-label="Remplacement d'image" class="change">
                                <br>
                                <p class="mandatory">Ce champ est obligatoire.</p>

                                <form enctype="multipart/form-data" action="index.php?p=modifyAlbum&albumId=<?= htmlspecialchars($findAlbum["id"]) ?>" method="post" onsubmit="confirmChange(event)">
                                    <label for="newPicture">Nouvelle image</label>
                                    <input type="file" name="newPicture" id="new-picture" accept=".jpg, .jpeg, .png" title="Fichier .jp(e)g / .png. Le total des images existantes et remplacées ne doit pas dépasser 30 Mo">

                                    <div>Aperçu nouvelle image</div>
                                    <div>Taille nouvelle image</div>
                                    <div>Taille totale images existantes et remplacées</div>

                                    <div class="rules">
                                        <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules">règlement général</a></label>	
                                        <input type="checkbox" value="true" name="acceptRules">
                                    </div>

                                    <div class="rules">
                                        <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy">politique de confidentialité</a></label>	
                                        <input type="checkbox" value="true" name="acceptPolicy">
                                    </div>

                                    <input type="text" name="pictureId" value="<?= htmlspecialchars($currentPicture["id"]) ?>" hidden>
                                    <input type="text" name="currentName" value="<?= htmlspecialchars(trim($currentPicture["picture_name"])) ?>" hidden>
                                    <input type="submit" name="pictureChange" value="Confirmer le remplacement d'image">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </section>
<?php endif; ?>

<section class="container">
    <?php if(!$_POST) : ?>
        <h2>Ajouter une/des image(s) à l'album</h2>

        <p class="mandatory">Ce champ est obligatoire.</p>
            
        <form enctype="multipart/form-data" action="index.php?p=modifyAlbum&albumId=<?= htmlspecialchars($findAlbum["id"]) ?>" method="post" onsubmit="confirmPicaddition(event)">
            <label for="extraPictures[]">Image(s) supplémentaire(s)</label>
            <input type="file" class="form-control-file" name="extraPictures[]" id="extra-pictures" multiple accept=".jpg, .jpeg, .png" title="Sélectionne un/des fichier(s) .jpg, .jpeg ou .png. Le total des images existantes et des images ajoutées ne doit pas dépasser 30 Mo">

            <div>Aperçu image(s) supplémentaire(s)</div>
            <div>Taille totale images existantes et supplémentaire(s)</div>

            <div class="rules">
                <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules">règlement général</a></label>	
                <input type="checkbox" value="true" name="acceptRules">
            </div>

            <div class="rules">
                <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy">politique de confidentialité</a></label>	
                <input type="checkbox" value="true" name="acceptPolicy">
            </div>
            
            <div>
                <input type="submit" name="pictureAddition" value="Confirmer l'ajout d'image(s)">
            </div>
        </form>
    <?php endif; ?>
        
    <?php if($session::admin()) : ?>
        <p class="redirect">Revenir au <a href="index.php?p=albumDashboard">tableau de bord des albums</a></p>
        <p class="redirect">Revenir au <a href="index.php?p=dashboard">tableau de bord administrateur</a></p>
    <?php endif; ?>

    <?php if($session::online() && !$session::admin()) : ?>
        <p class="redirect">Revenir à <a href="index.php?p=account">mon compte</a></p>
        <p class="redirect">Revenir à la <a href="index.php?p=home">page d'accueil</a></p>
    <?php endif; ?>

    <p class="redirect">Aller à <a href="index.php?p=albums&albumId=<?= htmlspecialchars($findAlbum["id"]) ?>">l'album modifié</a></p>
</section>