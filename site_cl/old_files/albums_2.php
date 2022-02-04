<head>
	<meta name="description" content="Bienvenue sur la page de nos albums. Bon visionnage et n'hésite pas à laisser des commentaires&nbsp;!">
</head>
    
<section class="container">
    <h1>Nos albums</h1>

    <!-- Displaying a message if there is no comment history -->
    <?php if(empty($albums)) : ?>
    <p class="noContent">Aucun album n'a encore été publié.</p>
    <?php else : ?>
    
    <?php foreach($albums as $album) : ?>
    <div class="albums">
        <article>
            <figure>
                <img src="assets/img/albums/<?= htmlspecialchars($album['id']) ?>/cover/<?= htmlspecialchars($album['cover']) ?>" alt="Couverture de l'album <?= htmlspecialchars(trim($album['title'])) ?>">
                
                <figcaption>
                    <h2 data-title="<?= htmlspecialchars(trim($album['title'])) ?>"><?= htmlspecialchars($album['title']) ?></h2>
                    <p data-description="<?= htmlspecialchars(trim($album['description'])) ?>"><?= htmlspecialchars($album['description']) ?></p>
                </figcaption>
            </figure>

            <div class="vote <?= $opinion ?>" id="vote" data-id="<?= htmlspecialchars(trim($album['id'])) ?>" data-ip="<?= $_SERVER['REMOTE_ADDR']?>">
                <div class="vote-bar">
                    <div class="vote-progress" style="width: <?= (htmlspecialchars(trim($album['likes'])) + htmlspecialchars(trim($album['dislikes']))) == 0 ? 100 : round(100 * (htmlspecialchars(trim($album['likes'])) / (htmlspecialchars(trim($album['likes'])) + htmlspecialchars(trim($album['dislikes']))))); ?>%"></div>
                </div>

                <div class="vote-loading">
                    Chargement...
                </div>

                <div class="vote-logos">
                    <!-- <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($album['id'])) ?>&vote=1" method="post"> -->
                        <button name="like" value="like" class="vote-thumb like"><i class="fas fa-thumbs-up"></i> <span id="like-count"><?= htmlspecialchars(trim($album['likes']))?></span></button>
                    <!-- </form> -->
                    
                    <!-- <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($album['id'])) ?>&vote=-1" method="post"> -->
                        <button name="dislike" value="dislike" class="vote-thumb dislike"><i class="fas fa-thumbs-down"></i> <span id="dislike-count"><?= htmlspecialchars(trim($album['dislikes']))?></span></button>
                    <!-- </form> -->
                </div>
            </div>
                    
            <p><a href="index.php?p=albumComments&albumId=<?= htmlspecialchars(trim($album['id'])) ?>">Voir tous les commentaires pour cet album</a></p>
        </article>
    </div>
</section>

<section class="container">
    <h2>Commenter l'album</h2>
    <!-- Paragraph to display as long as the form is not submitted -->
    <?php if(!$_POST) : ?>
    <p class="mandatory">Tous les champs sont obligatoires.</p>
    <?php endif; ?>
    
    <!-- Displaying error messages, if the form was submitted without all the requirements -->
    <?php if(empty($addMessages['success'])) : ?>
    <?php if(!empty($addMessages['errors'])) { ?>
    <ul class="error">
    <?php foreach($addMessages['errors'] as $error): ?>    
        <li><?= $error ?></li>
    <?php endforeach ?>
    </ul>
    <?php } ?>
    
    <!-- Review form -->
    <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($album['id'])) ?>" method="post" onsubmit="confirmCommaddition(event)">
        <div>
            <label for="email">Adresse électronique&nbsp;:</label>
            <input type="text" name="email" id="email" oninput="emailMessage(event)" <?php if($session::online()) : ?> value="<?= $_SESSION['user']['email'] ?>" <?php endif; ?>>

            <div id="emailMessage"></div>
        </div>

        <div>
            <label for="login">Pseudo&nbsp;:</label>
            <input type="text" name="login" id="login" oninput="commentLoginmessage(event)" <?php if($session::online()) : ?> value="<?= $_SESSION['user']['login'] ?>" <?php endif; ?>>

            <div id="loginMessage"></div>
        </div>

        <div>
            <label for="comment">Commentaire&nbsp;:</label>
            <textarea name="comment" id="comment" rows="8" cols="40" maxlength="200" title="Saisis 200 caractères maximum, espaces comprises" oninput="commentMessages(event)"></textarea>

            <div id="abmComment"></div>
        </div>

        <div>
            <input type="text" name="albumId" value="<?= htmlspecialchars(trim($album['id'])) ?>" hidden>
            <input type="text" name="albumTitle" value="<?= htmlspecialchars(trim($album['title'])) ?>" hidden>
            <input type="submit" name="postComment" id="postComment" value="Publier le commentaire">
        </div>
    </form>
    
    <!-- Displaying the success message, if the form was submitted with all the requirements -->
    <?php else : ?>
    <p class="success"><?= $addMessages['success'][0] ?></p>
    <?php endif; ?>

    <p class="redirect">Revenir à la <a href="index.php?p=home">page d'accueil</a></p>
    <?php endforeach; ?>
    <?php endif; ?>
</section>