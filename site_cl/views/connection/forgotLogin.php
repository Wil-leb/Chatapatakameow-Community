<section class="container">
    <h1>Récupération de pseudo</h1> 
    <?php if(empty($forgotLogMsg["success"])) : ?>
        <?php if(!empty($forgotLogMsg["errors"])) {  ?>
            <ul class="error">
                <?php foreach($forgotLogMsg["errors"] as $error) : ?>
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        <?php } ?>
    
        <?php if(!$_POST) : ?>
            <p>Si tu as oublié ton pseudo, tu peux le recevoir par email grâce au formulaire ci-dessous.</p>
            <p class="mandatory">Ce champ est obligatoire.</p>

            <form action="index.php?p=forgotLogin" method="POST">
                <input type="text" name="mail" placeholder="Email">
                
                <input type="submit" name="recoverLogin" value="Recevoir ton pseudo">
            </form>
        <?php endif; ?>

    <?php else : ?>
        <p class="success"><?= $forgotLogMsg["success"][0] ?></p>

        <a href="index.php?p=login">Se connecter</a>
    <?php endif; ?>
</section>