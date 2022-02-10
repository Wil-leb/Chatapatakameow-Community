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
            <input type="text" name="title" class="album-title" maxlength="30" placeholder="Titre (30 caractères)">
            <div>30 caractères restants</div>
            <div></div>
            
            <textarea name="description" class="album-description" rows="5" cols="60" maxlength="200" placeholder="Description (200 caractères)"></textarea>
            <div>200 caractères restants</div>
            <div></div>

            <label for="cover">Couverture</label>
            <input type="file" class="form-control-file" name="cover" id="album-cover" accept=".jpg, .jpeg, .png" title="Fichier .jp(e)g / .png ne dépassant pas 3 Mo">
            <div>Aperçu couverture</div>
            <div>Taille couverture</div>
            
            <label for="pictures[]">Image(s)</label>
            <input type="file" class="form-control-file" name="pictures[]" id="album-pictures" multiple accept=".jpg, .jpeg, .png" title="Fichier(s) .jp(e)g / .png ne dépassant pas 30 Mo au total">
            <div>Aperçu image(s)</div>
            <div>Taille totale image(s)</div>

            <div class="rules">
                <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules">règlement général</a></label>	
                <input type="checkbox" value="true" name="acceptRules">
            </div>

            <div class="rules">
                <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy">politique de confidentialité</a></label>	
                <input type="checkbox" value="true" name="acceptPolicy">
            </div>
            
            <input type="submit" name="postAlbum" value="Publier l'album">
        </form>
    <?php endif; ?>

    <p class="redirect">Revenir à la <a href="index.php?p=destinations">page des albums</a></p>
</section>