<?php
use App\model\{Albums};

$albums = new Albums();
?>

<head>
	<meta name="description" content="CL des xxx &ndash;&nbsp;liste des auteurs de nos albums">
</head>
    
<section class="container">
    <h1>Auteurs</h1>

    <?php $findAlbums = $albums->findAllAlbums(); ?>

    <?php if(empty($findAlbums)) : ?>
        <p>Aucun album n'a encore été publié</p>
    
    <?php else : ?>
        <p>Clique sur le titre d'un album d'un des auteurs ci-dessous pour le visionner&nbsp;!</p>

        <?php foreach($users as $user) : ?>
            <?php $userAlbums = $albums->findUserAlbums(htmlspecialchars(trim($user["id"]))); ?>

            <?php if(!empty($userAlbums)) : ?>
                <h2><?= htmlspecialchars(trim($user["login"])) ?></h2>

                <?php foreach($userAlbums as $userAlbum) : ?>
                    <?php $cover = $albums->findAlbumCover($userAlbum["id"]); ?>
                    <figure>
                        <div><?php require "assets/php/Covers.php"; ?></div>
                        
                        <figcaption>
                            <p><?= htmlspecialchars(trim($userAlbum["title"])) ?></p>
                            <a href="index.php?p=albums&albumId=<?= htmlspecialchars(trim($userAlbum["id"])) ?>">Album <?= htmlspecialchars(trim($userAlbum["title"])) ?></a>
                        </figcaption>
                    </figure>
                    
                    <br>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    <p class="redirect">Revenir à la <a href="index.php?p=home">page d'accueil</a></p>
</section>