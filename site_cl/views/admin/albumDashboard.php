<?php
use App\model\{Album};

$findImages = new Album();
?>

<section class="container">
    <h1>Tableau de bord des albums</h1>

    <?php if(!$_POST) : ?>
        <p>Bienvenue au tableau de bord des albums&nbsp;! Cette page permet de supprimer l'intégralité d'un album choisi, ou d'accéder à une autre page pour le modifier ou n'en supprimer qu'une partie.</p>
    <?php endif; ?>

    <?php if(!empty($deleteMessages["success"])) : ?>
        <p class="success"><?= $deleteMessages["success"][0] ?></p>
    <?php endif; ?>
</section>


<section class="container">
    <?php if(!$_POST) : ?>
        <h2>Albums</h2>
        
        <?php if(empty($albums)) : ?>
            <p class="no-content">Aucun album n'a encore été publié.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Auteur</th>
                        <th>Titre</th>
                        <th>Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach($albums as $album) : ?>
                        <?php $cover = $findImages->findAlbumCover(htmlspecialchars(trim($album["id"]))); ?>
                        <?php $pictures = $findImages->findAlbumPictures(htmlspecialchars(trim($album["id"]))); ?>

                        <tr>
                            <td data-label="Auteur"><?= htmlspecialchars(trim($album["user_login"])) ?></td>
                            <td data-label="Titre"><?= htmlspecialchars(trim($album["title"])) ?></td>
                            <td data-label="Date" id="description"><?= htmlspecialchars(trim(strftime("%d/%m/%Y %H:%M:%S", strtotime($album["post_date"])))) ?></td>
                            <td data-label="Action">
                                <div class="deletion">
                                    <form action="index.php?p=albumDashboard&albumId=<?= htmlspecialchars(trim($album["id"])) ?>" method="post" onsubmit="confirmDeletion(event)">
                                        <input type="text" name="cover" value="<?= htmlspecialchars(trim($cover["cover_name"])) ?>" hidden>
                                        <input type="text" name="picture" value="<?php foreach($pictures as $picture) : ?> <?= htmlspecialchars(trim($picture["picture_name"])) ?> <?php endforeach; ?>" hidden>
                                        <button class="delete" type="submit" name="deleteAlbum"><i class="fas fa-trash-alt"></i>Supprimer</button>
                                    </form>

                                    <p><a href="index.php?p=modifyAlbum&albumId=<?= htmlspecialchars($album["id"])?>"><i class="fas fa-pen"></i>Modifier</a></p>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>

    <p class="redirect">Revenir au <a href="index.php?p=dashboard">tableau de bord administrateur</a></p>
</section>