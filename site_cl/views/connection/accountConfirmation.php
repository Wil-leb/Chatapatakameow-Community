<head>
	<meta name="description" content="CL des xxx &ndash;&nbsp;page d'inscription">
</head>
    
<section class="container">
    <h1>Confirmation de compte</h1>

    <?php if(empty($accountConfMsg["success"])) : ?>
        <?php if(!empty($accountConfMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($accountConfMsg["errors"] as $error) : ?>    
                    <li><?= $error ?></li>
                <?php endforeach; ?>    
            </ul>
        <?php } ?>
    <?php else : ?>
        <p class="success"><?= $accountConfMsg["success"][0] ?></p>
        <p class="redirect"><a href="index.php?p=login">Connexion</a></p>
    <?php endif; ?>

    <?php if(!$_POST) : ?>
        <p>Pour confirmer la création de ton compte, clique sur le bouton ci-dessous.</p>
        
        <form action="" method="post" onsubmit="confirmRegistration(event)">
            <input type="submit" name="confirmRegistration" value="Confirmer la création de mon compte">
        </form>
    <?php endif; ?>
</section>