<section class="container">
    <h1>Modification d'utilisateur</h1>

    <?php if(!$_POST) : ?>
        <p>Bienvenue à la page de modification d'utilisateur&nbsp;! Ici, tu pourras modifier les identifiants de l'utilisateur choisi, et lui octroyer ou retirer les droits d'administration du site.</p>
    <?php endif; ?>

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

    <?php if(empty($roleMsg["success"])) : ?>
            <?php if(!empty($roleMsg["errors"])) { ?>
                <ul class="error">
                    <?php foreach($roleMsg["errors"] as $error): ?>    
                        <li><?= $error ?></li>
                    <?php endforeach; ?>    
                </ul>
            <?php } ?>
        
    <?php else : ?>
        <p class="success"><?= $roleMsg["success"][0] ?></p>
    <?php endif; ?>
</section>

<?php if(!$_POST) : ?>
    <section class="container">    
        <h2>Adresse électronique</h2>

        <p class="mandatory">Tous les champs sont obligatoires.</p>
        
        <form action="index.php?p=modifyUser&userId=<?= htmlspecialchars($findUser["id"]) ?>" method="post" onsubmit="confirmChange(event)">
            <div>
                <label for="currentEmail">Adresse électronique actuelle&nbsp;:</label>
                <input type="text" name="currentEmail" value="<?= htmlspecialchars($findUser["email"]) ?>" readonly>
            </div>
            
            <div>
                <label for="email">Nouvelle adresse électronique demandée&nbsp;:</label>
                <input type="text" name="email" class="email">

                <div></div>
            </div>
            
            <div>
                <label for="confirmEmail">Confirmer la nouvelle adresse électronique&nbsp;:</label>
                <input type="text" name="confirmEmail" class="confirm-email" title="Saisis la même valeur que le champ ci-dessus" >

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
        
        <form action="index.php?p=modifyUser&userId=<?= htmlspecialchars($findUser["id"]) ?>" method="post" onsubmit="confirmChange(event)">
            <div>
                <label for="currentLogin">Pseudo actuel&nbsp;:</label>
                <input type="text" name="currentLogin" value="<?= htmlspecialchars($findUser["login"]) ?>" readonly>
            </div>
            
            <div>
                <label for="login">Nouveau pseudo demandé&nbsp;:</label>
                <input type="text" name="login" class="login" minlength="3" maxlength="10" title="Saisis trois à dix caractères">

                <div></div>
            </div>
            
            <div>
                <label for="confirmLogin">Confirmer le nouveau pseudo&nbsp;:</label>
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
            
        <form action="index.php?p=modifyUser&userId=<?= htmlspecialchars($findUser["id"]) ?>" method="post" onsubmit="confirmChange(event)">
            <div>
                <label for="password">Nouveau mot de passe demandé&nbsp;:</label>
                <input type="password" name="password" class="password">
            </div>
            
            <div>
                <label for="confirmPassword">Confirmer le nouveau mot de passe&nbsp;:</label>
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
    <?php if($findUser["login"] !== "WilleB") : ?>
        <?php if(!$_POST) : ?>
            <h2>Rôle</h2>
            
            <p class="mandatory">Ce champ est obligatoire.</p>
            
            <form action="index.php?p=modifyUser&userId=<?= htmlspecialchars($findUser["id"]) ?>" method="post" onsubmit="confirmChange(event)">
                <div>
                    <label for="role">Rôle de l'utilisateur&nbsp;:</label>

                    <select name="role" title="Clique pour choisir un rôle">
                        <option value="" selected>[choix du rôle]</option>
                        <option value="0">Visiteur non membre de la CL</option>
                        <option value="1">Membre de la CL sans droits d'administration</option>
                        <option value="2">Membre de la CL avec droits d'aministration</option>
                    </select>
                </div>
                
                <div>
                    <input type="submit" name="roleChange" value="Confirmer le changement de rôle">
                </div>
            </form>
        <?php endif; ?>
    <?php endif; ?>
    
    <p class="redirect">Revenir au <a href="index.php?p=userDashboard">tableau de bord des utilisateurs</a></p>
    <p class="redirect">Revenir au <a href="index.php?p=dashboard">tableau de bord administrateur</a></p>
</section>