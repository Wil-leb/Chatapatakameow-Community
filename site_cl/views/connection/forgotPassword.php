<section class="container">
    <h1>Récupération de mot de passe</h1> 
    <?php if(empty($forgotPassMsg["success"])) : ?>
        <?php if(!empty($forgotPassMsg["errors"])) {  ?>
            <ul class="error">
                <?php foreach($forgotPassMsg["errors"] as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        <?php } ?>
    
        <?php if(!$_POST) : ?>
            <p>Si tu as oublié ton mot de passe, tu peux en recevoir un nouveau par email grâce au formulaire ci-dessous.</p>
            <p class="mandatory">Ce champ est obligatoire.</p>

            <form action="index.php?p=forgotPassword" method="POST">
                <input type="text" name="mail" placeholder="Email">
                
                <input type="submit" name="recoverPassword" value="Recevoir un nouveau mot de passe">
            </form>
        <?php endif; ?>

    <?php else : ?>
        <p class="success"><?= $forgotPassMsg["success"][0] ?></p>
        
        <a href="index.php?p=login">Se connecter</a>
    <?php endif; ?>
</section>