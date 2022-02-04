<?php
use App\model\{Album};

$findAlbums = new Album();
?>

<head>
	<meta name="description" content="CL des xxx &ndash;&nbsp;liste des auteurs de nos albums">
</head>
    
<section class="container">
    <h1>Auteurs</h1>

    <p>Clique sur le titre d'un album d'un des auteurs ce-dessous pour le visionner&nbsp;!</p>

    <?php foreach($users as $user) : ?>
        <?php $albums = $findAlbums->findUserAlbums(htmlspecialchars(trim($user["id"]))); ?>

        <?php if(!empty($albums)) : ?>
            <h2><?= htmlspecialchars(trim($user["login"])) ?></h2>

            <?php foreach($albums as $album) : ?>
                <?php $cover = $findAlbums->findAlbumCover($album["id"]); ?>
                <figure>
                    <div><?php require "assets/php/Covers.php"; ?></div>
                    
                    <figcaption>
                        <p><?= htmlspecialchars(trim($album["title"])) ?></p>
                        <a href="index.php?p=albums&albumId=<?= htmlspecialchars(trim($album["id"])) ?>">Album <?= htmlspecialchars(trim($album["title"])) ?></a>
                    </figcaption>
                </figure>
                
                <br>
            <?php endforeach; ?>
        <?php endif; ?>
    <?php endforeach; ?>

    <p class="redirect">Revenir à la <a href="index.php?p=home">page d'accueil</a></p>
</section>