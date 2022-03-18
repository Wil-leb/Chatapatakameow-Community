<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<!-- <link rel="icon" href="assets/img/favicon/favicon.ico"> -->
	<link href="https://fonts.googleapis.com/css2?family=Epilogue:wght@300&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Carter+One&family=Roboto:wght@500&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/normalize.css">
	<link rel="stylesheet" href="assets/css/style.css">
	
	<title><?= $title ?></title>
</head>

<body>
    <!-- Footer -->
	<header>
		<div class="container">
			<nav>
				<ul>
                    <?php if(!$session::online()) : ?>
                        <li><a href="index.php?p=register" <?= $https::active("register") ?>>S'inscrire</a></li>
                        <li><a href="index.php?p=login" <?= $https::active("login") ?>>Se connecter</a></li>

                    <?php elseif($session::online() && !$session::admin()) : ?>
                        <li><a href="index.php?p=account" <?= $https::active("account") ?>><i class="fas fa-user-alt"></i>Mon compte</a></li>
                        
                    <?php elseif($session::online() && $session::admin()) : ?>
                        <li><a href="index.php?p=dashboard" <?= $https::active("dashboard") ?>><i class="fas fa-user-lock"></i>Tableau de bord</a></li>
                    <?php endif; ?>

                    <?php if($session::online()) : ?>
                        <li><a href="index.php?p=logout" <?= $https::active("logout") ?>>Se déconnecter</a></li>
                    
                        <?php if($session::member() || $session::admin()) { ?>
                            <li><a href="index.php?p=addAlbum" <?= $https::active("addAlbum") ?>>Publier un album</a></li>
                        <?php } ?>
                    <?php endif; ?>

                    <li><a href="index.php?p=albumPublishers" <?= $https::active("albumPublishers") ?>>Auteurs</a></li>
				</ul>
			</nav>
            
            <div class="logo">
                <a href="index.php?p=home" class="logo"><img src="" alt="Logo xxx"></a>
                <p><strong>Compagnie libre des xxx</strong></p>
            </div>

            <!-- <nav>
				<ul>
					<?php if(!$session::online() || $session::online() && !$session::admin()) : ?>
					    <li><a href="index.php?p=contactForm" <?= $https::active("contact") ?>>Nous contacter</a></li>
					<?php endif; ?>
				</ul>
			</nav> -->
        </div>
	</header>

    <!-- Main content -->
    <main>
        <?php require "views/".$path ?>
    </main>

    <!-- Footer -->
	<footer>
		<div class="container">
            <nav>
                <ul>
                    <li><a href="index.php?p=privacyPolicy" <?= $https::active("privacyPolicy") ?>>Politique de confidentialité</a></li>
                    <!-- <li><a href="index.php?p=cookies" <?= $https::active("cookies") ?>>Politique des cookies</a></li> -->
                    <li><a href="index.php?p=rules" <?= $https::active("rules") ?>>Règlement général</a></li>
                    <li><a href="https://discord.com/" target="blank"><i class="fab fa-discord"></i></a>
                </ul>
            </nav>
        </div>
	</footer>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="./assets/js/ckeditor/ckeditor.js"></script>
    <script>
        CKEDITOR.replaceAll("editor");
        CKEDITOR.config.extraPlugins="emoji";
    </script>
	<script src="./assets/js/function.js"></script>
	<script type="module" src="./assets/js/main.js"></script>
</body>

</html>