<section class="container">
    <h1>Publication d'album</h1>

    <?php if(!$_POST) : ?>
        <p>Tu peux publier un album grâce au formulaire ci-dessous, si tu as envie de partager ton talent ou des moments que tu as appréciés en jouant à FFXIV&nbsp;!</p>
    <?php endif; ?>

    <?php if(empty($addMessages["success"])) : ?>
        <?php if(!empty($addMessages["errors"])) { ?>
            <ul class="error">
                <?php foreach($addMessages["errors"] as $error) : ?>    
                    <li><?= $error ?></li>
                <?php endforeach ?>    
            </ul>
        <?php } ?>
    
    <?php else : ?>
        <p class="success"><?= $addMessages["success"][0] ?></p>
    <?php endif; ?>
</section>

<section>
    <?php if(!$_POST) : ?>
        <p class="mandatory">Tous les champs sont obligatoires.</p>
    
        <form enctype="multipart/form-data" action="index.php?p=addAlbum" method="post" onsubmit="confirmAlbaddition(event)">
            <div>
                <label for="title">Titre&nbsp;:</label>
                <input type="text" name="title" class="album-title" maxlength="30" title="Saisis 30 caractères maximum, espaces comprises">

                <div>30 caractères restants</div>
                <div></div>
            </div>
            
            <div>
                <label for="description">Description&nbsp;:</label>
                <textarea name="description" class="album-description" rows="5" cols="60" maxlength="200" title="Saisis 200 caractères maximum, espaces comprises"></textarea>

                <div>200 caractères restants</div>
                <div></div>
            </div>

            <div>
                <label for="cover">Couverture&nbsp;:</label>
                <input type="file" class="form-control-file" name="cover" id="album-cover" accept=".jpg, .jpeg, .png" title="Sélectionne un fichier .jpg, .jpeg ou .png ne dépassant pas 3 Mo">

                <div>Aperçu de la nouvelle couverture&nbsp;:</div>
                <div>Taille de la nouvelle couverture&nbsp;:</div>
            </div>
            

            <div>
                <label for="pictures[]">Image(s)&nbsp;:</label>
                <input type="file" class="form-control-file" name="pictures[]" id="album-pictures" multiple accept=".jpg, .jpeg, .png" title="Sélectionne un/des fichier(s) .jpg, .jpeg ou .png ne dépassant pas 30 Mo au total">
                
                <div>Aperçu de l'image / des images&nbsp;:</div>
                <div>Taille de l'image / taille totale des images&nbsp;:</div>
            </div>

            <div class="rules">
                <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules">règlement général</a></label>	
                <input type="checkbox" value="true" name="acceptRules">
            </div>

            <div class="rules">
                <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy">politique de confidentialité</a></label>	
                <input type="checkbox" value="true" name="acceptPolicy">
            </div>
            
            <div>
                <input type="submit" name="postAlbum" value="Publier l'album">
            </div>
        </form>
    <?php endif; ?>

    <p class="redirect">Revenir à la <a href="index.php?p=destinations">page des albums</a></p>
</section>