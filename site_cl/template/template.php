<?php
	if(isset($_COOKIE['accept_cookie'])) {
	   $showcookie = false;
	} else {
	   $showcookie = true;
	}
?>

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
                        <li><a href="index.php?p=account" <?= $https::active("account") ?>><i class="fas fa-user-alt"></i><?= $_SESSION["user"]["login"] ?></a></li>
                        
                    <?php elseif($session::online() && $session::admin()) : ?>
                        <li><a href="index.php?p=dashboard" <?= $https::active("dashboard") ?>><i class="fas fa-user-lock"></i>Page admin</a></li>
                    <?php endif; ?>

                    <?php if($session::online()) : ?>
                        <li>
                            <button id="hide-content" class="notif" value="ON"><i class="fa-solid fa-bell"></i>Notifications <span class="count"></span></button>
                            <dialog open>
                                <div id="notif-content"></div>

                                <button id="close"><i class="far fa-window-close"></i>Fermer</button>
                            </dialog>
                        </li>

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

    <?php if($showcookie) : ?>
        <div class="cookie-alert">
        En poursuivant ta navigation sur ce site, tu acceptes l’utilisation de cookies pour te proposer des contenus et services adaptés à tes centres d’intérêts.<br/><a href="./assets/php/AcceptCookies.php">OK</a>
        </div>
	<?php endif; ?>
	</footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
	<script src="./assets/js/function.js"></script>
	<script type="module" src="./assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            function load_unseen_notification(view = "") {

// // Mettre à jour la vue avec les notifications en utilisant ajaxfunction load_unseen_notification(view = ''){
                $.ajax({
                    url: "./assets/php/FetchComments.php",
                    method: "POST",
                    data: {view: view},
                    dataType: "json",
                    success:function(data) {
                        $("#notif-content").html(data.notification);

                        if(data.unseen_notification > 0) {
                            $(".count").html("(" + data.unseen_notification + ")");
                        }

                        else {
                            $("#notif-content").html('<p class="no-content">Aucune notification pour le moment</p>')
                        }
                    }
                });
            }
 
            load_unseen_notification();

// Soumettre le formulaire et obtenir de nouveaux enregistrements
//             // $(".comment-form").on("submit", function(event) {
//             //     event.preventDefault();

//             //     if($(".email").val() != "" && $(".commentLogin").val() != "" && $("#comment").val() != "") {
//             //         let form_data = $(this).serialize();

//             //         $.ajax({
//             //             url: "./controller/CommentFormController.php",
//             //             method: "POST",
//             //             data: form_data,
//             //             success: function(data){
//             //                 $("#comment_form")[0].reset();
//             //                 load_unseen_notification();
//             //             }
//             //         });
//             //     }
                
//             //     else {
//             //         alert("Veuilles remplir tous les champs");
//             //     }
//             // });

// // Chargement des nouvelles notifications
            $(document).on("click", "#delete-notifications", function() {
                $(".count").html("");
                load_unseen_notification("yes");
            });

            setInterval(function() {
                load_unseen_notification();
            },  5000);
        });
    </script>
</body>

</html>