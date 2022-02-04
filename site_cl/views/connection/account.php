<?php
use App\model\{Album};

$findImages = new Album();
?>

<section class="container">
    <h1>Ton compte</h1>

    <?php if(empty($emailMsg["success"])) : ?>
        <?php if(!empty($emailMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($emailMsg["errors"] as $error): ?>    
                    <li><?= $error ?></li>
                <?php endforeach; ?>    
            </ul>
        <?php } ?>
    <?php else : ?>
        <p class="success"><?= $emailMsg["success"][0] ?></p>
    <?php endif; ?>

    <?php if(empty($loginMsg["success"])) : ?>
        <?php if(!empty($loginMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($loginMsg["errors"] as $error): ?>    
                    <li><?= $error ?></li>
                <?php endforeach; ?>    
            </ul>
        <?php } ?>
    <?php else : ?>
        <p class="success"><?= $loginMsg["success"][0] ?></p>
    <?php endif; ?>

    <?php if(empty($passwordMsg["success"])) : ?>
        <?php if(!empty($passwordMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($passwordMsg["errors"] as $error): ?>    
                    <li><?= $error ?></li>
                <?php endforeach; ?>    
            </ul>
        <?php } ?>
    
    <?php else : ?>
        <p class="success"><?= $passwordMsg["success"][0] ?></p>
    <?php endif; ?>

    <?php if(!empty($deleteMessages["success"])) : ?>
        <p class="success"><?= $deleteMessages["success"][0] ?></p>
    <?php endif; ?>
</section>


<?php if(!$_POST) : ?>
    <section class="container">    
        <h2>Adresse électronique</h2>
        
        <p class="mandatory">Tous les champs sont obligatoires.</p>
        
        <form action="index.php?p=account" method="post" onsubmit="confirmChange(event)">
            <div>
                <label for="currentEmail">Adresse électronique actuelle&nbsp;:</label>
                <input type="text" name="currentEmail">
            </div>
            
            <div>
                <label for="email">Nouvelle adresse électronique&nbsp;:</label>
                <input type="text" name="email" class="email">

                <div></div>
            </div>
            
            <div>
                <label for="confirmEmail">Confirme ta nouvelle adresse électronique&nbsp;:</label>
                <input type="text" name="confirmEmail" class="confirm-email" title="Saisis la même valeur que le champ ci-dessus">

                <div></div>
            </div>
            
            <div>
                <input type="submit" name="emailChange" value="Confirmer le changement d'adresse électronique">
            </div>
        </form>
    </section>

    <section class="container">    
        <h2>Pseudo</h2>
        
        <p class="mandatory">Tous les champs sont obligatoires.</p>
        
        <form action="index.php?p=account" method="post" onsubmit="confirmChange(event)">
            <div>
                <label for="currentLogin">Pseudo actuel&nbsp;:</label>
                <input type="text" name="currentLogin">
            </div>
            
            <div>
                <label for="login">Nouveau pseudo&nbsp;:</label>
                <input type="text" name="login" class="login" minlength="3" maxlength="10" title="Saisis trois à dix caractères">

                <div></div>
            </div>
            
            <div>
                <label for="confirmLogin">Confirme ton nouveau pseudo&nbsp;:</label>
                <input type="text" name="confirmLogin" class="confirm-login" minlength="3" maxlength="10" title="Saisis la même valeur que le champ ci-dessus">

                <div></div>
            </div>
            
            <div>
                <input type="submit" name="loginChange" value="Confirmer le changement de pseudo">
            </div>
        </form>
    </section> 

    <section class="container">    
        <h2>Mot de passe</h2>
        
        <p class="mandatory">Tous les champs sont obligatoires.</p>
        
        <form action="index.php?p=account" method="post" onsubmit="confirmChange(event)">
            <div>
                <label for="currentPassword">Mot de passe actuel&nbsp;:</label>
                <input type="password" name="currentPassword">
            </div>
            
            <div>
                <label for="password">Nouveau mot de passe&nbsp;:</label>
                <input type="password" name="password" class="password">
            </div>
            
            <div>
                <label for="confirmPassword">Confirme ton nouveau mot de passe&nbsp;:</label>
                <input type="password" name="confirmPassword" class="confirm-password" title="Saisis la même valeur que le champ ci-dessus">

                <div></div>
            </div>
            
            <div>
                <input type="submit" name="passwordChange" value="Confirmer le changement de mot de passe">
            </div>
        </form>
    </section>
<?php endif; ?>

<section class="container">
    <?php if(!$_POST) : ?>
        <h2>Tes albums</h2>
        
        <?php if(empty($albums)) : ?>
            <p class="noContent">Tu n'as pas encore publié d'album.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Date</th>
                        <th>Supprimer</th>
                        <th>Modifier</th>
                    </tr>
                </thead>
                            
                <tbody>
                    <?php foreach($albums as $album) : ?>
                        <?php $cover = $findImages->findAlbumCover(htmlspecialchars(trim($album["id"]))); ?>
                        <?php $pictures = $findImages->findAlbumPictures(htmlspecialchars(trim($album["id"]))); ?>
                        <tr>
                            <td data-label="Titre"><?= htmlspecialchars(trim($album["title"])) ?></td>
                            <td data-label="Date" id="description"><?= htmlspecialchars(trim($album["post_date"])) ?></td>
                            <td data-label="Supprimer" class="deletion">
                                <form action="index.php?p=account&albumId=<?= htmlspecialchars($album["id"]) ?>" method="post" onsubmit="confirmDeletion(event)">
                                    <input type="text" name="cover" value="<?= htmlspecialchars(trim($cover["cover_name"])) ?>" hidden>
                                    <input type="text" name="picture" value="<?php foreach($pictures as $picture) : ?> <?= htmlspecialchars(trim($picture["picture_name"])) ?> <?php endforeach; ?>" hidden>
                                    <button class="delete" type="submit" name="deleteAlbum">Supprimer l'album</button>
                                </form>
                            </td>
                            <td data-label="Modifier">
                                <a href="index.php?p=modifyAlbum&albumId=<?= htmlspecialchars($album["id"])?>">Modifier l'album</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>

    <p class="redirect">Revenir à la <a href="index.php?p=home">page d'accueil</a></p>
</section>