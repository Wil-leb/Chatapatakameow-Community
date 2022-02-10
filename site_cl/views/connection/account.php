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
        <h2>Email</h2>
        
        <p class="mandatory">Tous les champs sont obligatoires.</p>
        
        <form action="index.php?p=account" method="post" onsubmit="confirmChange(event)">
            <input type="text" name="currentEmail" placeholder="Email actuel">
            
            <input type="text" name="email" class="email" placeholder="Nouvel email">
            <div></div>
            
            <input type="text" name="confirmEmail" class="confirm-email" placeholder="Confirmation du nouvel email" title="Saisis la même valeur que le champ ci-dessus">
            <div></div>
            
            <input type="submit" name="emailChange" value="Confirmer le changement d'email">
        </form>
    </section>

    <section class="container">    
        <h2>Pseudo</h2>
        
        <p class="mandatory">Tous les champs sont obligatoires.</p>
        
        <form action="index.php?p=account" method="post" onsubmit="confirmChange(event)">
            <input type="text" name="currentLogin" placeholder="Pseudo actuel">
            
            <input type="text" name="login" class="login" minlength="3" maxlength="10" placeholder="Nouveau pseudo (3 à 10 caractères)" title="Saisis trois à dix caractères">
            <div></div>
            
            <input type="text" name="confirmLogin" class="confirm-login" minlength="3" maxlength="10" placeholder="Confirmation du nouveau pseudo" title="Saisis la même valeur que le champ ci-dessus">
            <div></div>
            
            <input type="submit" name="loginChange" value="Confirmer le changement de pseudo">
        </form>
    </section> 

    <section class="container">    
        <h2>Mot de passe</h2>
        
        <p class="mandatory">Tous les champs sont obligatoires.</p>
        
        <form action="index.php?p=account" method="post" onsubmit="confirmChange(event)">
            <input type="password" name="currentPassword" placeholder="Mot de passe actuel">
            
            <input type="password" name="password" class="password" placeholder="Nouveau mot de passe">
 
            <input type="password" name="confirmPassword" class="confirm-password" placeholder="Confirmation du nouveau mot de passe" title="Saisis la même valeur que le champ ci-dessus">
            <div></div>
        
            <input type="submit" name="passwordChange" value="Confirmer le changement de mot de passe">
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