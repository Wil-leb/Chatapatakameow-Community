<head>
	<meta name="description" content="CL des xxx &ndash;&nbsp;page d'inscription">
</head>
    
<section class="container">
    <h1>Page d'inscription</h1>

    <?php if(empty($registrationMsg["success"])) : ?>
        <?php if(!empty($registrationMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($registrationMsg["errors"] as $error): ?>    
                    <li><?= $error ?></li>
                <?php endforeach ?>    
            </ul>
        <?php } ?>
    <?php else : ?>
        <p class="success"><?= $registrationMsg["success"][0] ?></p>
        <p class="redirect"><a href="index.php?p=login">Connexion</a></p>
    <?php endif; ?>

    <?php if(!$_POST) : ?>
        <p>Tu peux t'inscrire sur notre site grâce au formulaire ci-dessous, si ce n'est pas déjà fait.</p>
        <p class="mandatory">Tous les champs sont obligatoires.</p>
        
        <form action="index.php?p=register" method="post" onsubmit="confirmRegistration(event)">
            <input type="text" name="email" class="email" placeholder="Email">
            <div></div>

            <input type="text" name="login" class="login" minlength="3" maxlength="10" placeholder="Pseudo (3 à 10 caractères)">
            <div></div>
            
            <input type="password" name="password" class="password" placeholder="Mot de passe">

            <input type="password" name="confirmPassword" class="confirm-password" placeholder="Confirmation du mot de passe" title="Saisis la même valeur que le champ ci-dessus">
            <div></div>
            
            <div class="rules">
                <p><em>En cochant les deux cases ci-dessous&nbsp;:</em></p>
                <p><em>- Tu reconnais avoir lu dans son intégralité le règlement général du site et sa politique de condidentialité.</em></p>
                <p><em>- Tu t'engages à respecter sans réserve le règlement.</em></p>
                <p><em>- Tu accordes aux administrateurs de ce site le droit de supprimer ou éditer n'importe quel contenu publié par tes soins à tout moment.</em></p>
            </div>

            <div class="rules">
                <label for="acceptRules">J'ai lu et j'accepte le <a target="blank" href="index.php?p=rules">règlement général</a></label>	
                <input type="checkbox" value="true" name="acceptRules">
            </div>

            <div class="rules">
                <label for="acceptPolicy">J'ai lu et j'accepte la <a target="blank" href="index.php?p=privacyPolicy">politique de confidentialité</a></label>	
                <input type="checkbox" value="true" name="acceptPolicy">
            </div>

            <<input type="submit" name="register" value="S'inscrire">
        </form>
        
        <p>Déjà membre&nbsp;? clique <a href="index.php?p=login">ici</a> pour te connecter&nbsp;!</p>
        <p class="redirect">Revenir à la <a href="index.php?p=home">page d'accueil</a></p>
    <?php endif; ?>
</section>