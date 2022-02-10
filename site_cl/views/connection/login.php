<head>
	<meta name="description" content="CL des xxx &ndash;&nbsp;page de connexion">
</head>
    
<section class="container">
    <h1>Page de connexion</h1>

    <?php if(empty($connectionMsg["success"])) : ?>
        <?php if(!empty($connectionMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($connectionMsg["errors"] as $error) : ?>    
                    <li><?= $error ?></li>
                <?php endforeach; ?>    
            </ul>
        <?php } ?>
    <?php else : ?>
        <p class="success"><?= $connectionMsg["success"][0] ?></p>
        
        <?php if($session::online() && !$session::admin()) : ?>
            <p class="redirect">Aller à la <a href="index.php?p=home">page d'accueil</a></p>
            
        <?php elseif($session::admin()) : ?>
            <p class="redirect">Aller à la <a href="index.php?p=home">page d'accueil</a></p>
            <p class="redirect">Aller au <a href="index.php?p=dashboard">tableau de bord administrateur</a></p>
        <?php endif; ?>
    <?php endif; ?>

    <?php if(!$_POST) : ?>
        <p>Tu peux te connecter grâce au formulaire ci-dessous, si tu es déjà inscrit(e).</p>
        <p class="mandatory">Tous les champs sont obligatoires.</p>
        
        <form action="index.php?p=login" method="post">
            <input type="text" name="login" placeholder="Pseudo" <?= $cookie::checkCookie("login") ?>>

            <a class="forgot-account" href="index.php?p=forgotLogin">Pseudo oublié&nbsp;?</a>

            <input type="password" name="password" placeholder="Mot de passe" <?= $cookie::checkCookie("password") ?>>
            <a class="forgot-account" href="index.php?p=forgotPassword">Mot de passe oublié&nbsp;?</a>
            
            <div class="remember-user">
                <label for="rememberMe">Se souvenir de moi</label>	
                <input type="checkbox" value="true" name="rememberMe">
            </div>

            <input type="submit" name="connect" value="Se connecter">
        </form>
    
        <p>Tu souhaites devenir membre&nbsp;? Clique <a href="index.php?p=register">ici</a> pour t'inscrire&nbsp;!</p>
        <p class="redirect">Revenir à la <a href="index.php?p=home">page d'accueil</a></p>
    <?php endif; ?>
</section>