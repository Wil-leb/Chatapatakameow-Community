<section class="container">
    <h1>Tableau de bord des commentaires</h1>
    
    <?php if(!$_POST) : ?>
        <p>Bienvenue au tableau de bord des commentaires&nbsp;! Cette page permet de supprimer des commentaires / réponses, si leur contenu est indésirable par exemple.</p>
    <?php endif; ?>

    <?php if(!empty($commentDelMsg["success"])) : ?>
        <p class="success"><?= $commentDelMsg["success"][0] ?></p>
    <?php endif; ?>

    <?php if(!empty($answerDelMsg["success"])) : ?>
        <p class="success"><?= $answerDelMsg["success"][0] ?></p>
    <?php endif; ?>
</section>

<section class="container">
    <?php if(!$_POST) : ?>
        <h2>Commentaires</h2>

        <?php if(empty($comments)) : ?>
            <p class="no-content">Aucun commentaire n'a encore été publié.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Référence</th>
                        <th>Pseudo</th>
                        <th>Titre album</th>
                        <th>Date commentaire</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach($comments as $comment) : ?>
                        <tr>
                            <td data-label="Référence"><?= htmlspecialchars($comment["id"]) ?></td>
                            <td data-label="Pseudo"><?= htmlspecialchars(trim($comment["user_login"])) ?></td>
                            <td data-label="Titre album"><?= htmlspecialchars(trim($comment["album_title"])) ?></td>
                            <td data-label="Date commentaire"><?= htmlspecialchars(trim(strftime("%d/%m/%Y %H:%M:%S", strtotime($comment["post_date"])))) ?></td>
                            <td data-label="Action">
                                <button id="hide-content" value="ON">Afficher le commentaire</button>
                                
                                <dialog open>
                                    <p><?= htmlspecialchars(trim($comment["comment"])) ?></p>

                                    <form action="index.php?p=commentDashboard&commentId=<?= htmlspecialchars($comment["id"]) ?>" method="post" onsubmit="confirmDeletion(event)">
                                        <input type="text" name="userLogin" value="<?= htmlspecialchars(trim($comment["user_login"])) ?>" hidden>
                                        <button class="delete" type="submit" name="adminDelComment"><i class="fas fa-trash-alt"></i>Supprimer</button>
                                    </form>

                                    <button id="close"><i class="far fa-window-close"></i>Fermer</button>
                                </dialog>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</section>

<section class="container">
    <?php if(!$_POST) : ?>
        <h2>Réponses aux commentaires</h2>
    
        <?php if(empty($answers)) : ?>
            <p class="no-content">Aucune réponse n'a encore été publiée.</p>
        <?php else : ?>
            <table>
                <thead>
                    <tr>
                        <th>Référence réponse</th>
                        <th>Référence commentaire</th>
                        <th>Pseudo</th>
                        <th>Titre album</th>
                        <th>Date réponse</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach($answers as $answer) : ?>
                    <tr>
                        <td data-label="Référence réponse" id="answerId"><?= htmlspecialchars($answer["id"]) ?></td>
                        <td data-label="Référence commentaire" id="commentId"><?= htmlspecialchars($answer["comment_id"]) ?></td>
                        <td data-label="Pseudo"><?= htmlspecialchars(trim($answer["user_login"])) ?></td>
                        <td data-label="Titre album"><?= htmlspecialchars(trim($answer["album_title"])) ?></td>
                        <td data-label="Date réponse"><?= htmlspecialchars(trim(strftime("%d/%m/%Y %H:%M:%S", strtotime($answer["post_date"])))) ?></td>
                        <td data-label="Action">
                            <button id="hide-content" value="ON">Afficher la réponse</button>

                            <dialog open>
                                <p><?= htmlspecialchars(trim($answer["answer"])) ?></p>
                            
                                <form action="index.php?p=commentDashboard&commentId=<?= htmlspecialchars($answer["comment_id"]) ?>" method="post" onsubmit="confirmDeletion(event)">
                                    <input type="text" name="answerId" value="<?= htmlspecialchars($answer["id"]) ?>" hidden>
                                    <input type="text" name="albumTitle" value="<?= htmlspecialchars($answer["album_title"]) ?>" hidden>
                                    <button class="delete" type="submit" name="adminDelAnswer"><i class="fas fa-trash-alt"></i>Supprimer</button>
                                </form>

                                <button id="close"><i class="far fa-window-close"></i>Fermer</button>
                            </dialog>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
    
    <p class="redirect">Revenir au <a href="index.php?p=dashboard">tableau de bord administrateur</a></p>
</section>