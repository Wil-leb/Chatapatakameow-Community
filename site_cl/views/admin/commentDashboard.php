<section class="container">
    <h1>Tableau de bord des commentaires</h1>
    
    <?php if(!$_POST) : ?>
        <p>Bienvenue au tableau de bord des commentaires&nbsp;! Cette page permet de voir tous les commentaires et de modérer ceux qui ont été signalés.</p>
    <?php endif; ?>

<!-- COMMENT DELETION MESSAGES ---------------------------------------------------------------------------------------------------->    
    <?php if(empty($commentDelMsg["success"])) : ?>
        <?php if(!empty($commentDelMsg["errors"])) { ?>
            <p class="error"><?= $commentDelMsg["errors"][0] ?></p>
        <?php } ?>
    
    <?php else : ?>
        <ul class="success">
            <?php foreach($commentDelMsg["success"] as $success) : ?>    
                <li><?= $success ?></li>
            <?php endforeach ?>    
        </ul>
    <?php endif; ?>

<!-- ANSWER DELETION MESSAGES ------------------------------------------------------------------------------------------------------->  
    <?php if(empty($answerDelMsg["success"])) : ?>
        <?php if(!empty($answerDelMsg["errors"])) { ?>
            <p class="error"><?= $answerDelMsg["errors"][0] ?></p>
        <?php } ?>
    
    <?php else : ?>
        <ul class="success">
            <?php foreach($answerDelMsg["success"] as $success) : ?>    
                <li><?= $success ?></li>
            <?php endforeach ?>    
        </ul>
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
                        <th>Auteur</th>
                        <th>Titre album</th>
                        <th>Date commentaire</th>
                        <th>Signalements</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach($comments as $comment) : ?>
                        <tr>
                            <td data-label="Référence"><?= htmlspecialchars($comment["id"]) ?></td>
                            <td data-label="Auteur"><?= htmlspecialchars(trim($comment["user_login"])) ?></td>
                            <td data-label="Titre album"><?= htmlspecialchars(trim($comment["album_title"])) ?></td>
                            <td data-label="Date commentaire"><?= htmlspecialchars(trim(strftime("%d/%m/%Y %H:%M:%S", strtotime($comment["post_date"])))) ?></td>
                            <td data-label="Signalements" id="reports-number"><?= htmlspecialchars(trim($comment["reports_number"])) ?></td>
                            <td data-label="Action">
                                <button id="hide-content" value="ON">Afficher le commentaire</button>
                                
                                <dialog open>
                                    <p><?= htmlspecialchars(trim($comment["comment"])) ?></p>

                                    <?php if($comment["reports_number"] >= 10) : ?>
                                        <form action="index.php?p=commentDashboard" method="post" onsubmit="confirmDeletion(event)">
                                            <input type="text" name="commentId" value="<?= htmlspecialchars(trim($comment["id"])) ?>" hidden>
                                            <button class="warning" type="submit" name="adminDelComment"><i class="fas fa-trash-alt"></i>Supprimer</button>
                                        </form>
                                    <?php endif; ?>

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
                        <th>Auteur</th>
                        <th>Titre album</th>
                        <th>Date réponse</th>
                        <th id="reportColumn">Signalements</th>
                        <th>Action</th>
                    </tr>
                </thead>
                
                <tbody>
                    <?php foreach($answers as $answer) : ?>
                    <tr>
                        <td data-label="Référence réponse" id="answerId"><?= htmlspecialchars($answer["id"]) ?></td>
                        <td data-label="Référence commentaire" id="commentId"><?= htmlspecialchars($answer["comment_id"]) ?></td>
                        <td data-label="Auteur"><?= htmlspecialchars(trim($answer["user_login"])) ?></td>
                        <td data-label="Titre album"><?= htmlspecialchars(trim($answer["album_title"])) ?></td>
                        <td data-label="Date réponse"><?= htmlspecialchars(trim(strftime("%d/%m/%Y %H:%M:%S", strtotime($answer["post_date"])))) ?></td>
                        <td data-label="Signalements" id="reports-number"><?= htmlspecialchars(trim($answer["reports_number"])) ?></td>
                        <td data-label="Action">
                            <button id="hide-content" value="ON">Afficher la réponse</button>

                            <dialog open>
                                <p><?= htmlspecialchars(trim($answer["answer"])) ?></p>
                            
                                <?php if($answer["reports_number"] >= 10) : ?>
                                    <form action="index.php?p=commentDashboard" method="post" onsubmit="confirmDeletion(event)">
                                        <input type="text" name="answerId" value="<?= htmlspecialchars(trim($answer["id"])) ?>" hidden>
                                        <input type="text" name="commentId" value="<?= htmlspecialchars(trim($answer["comment_id"])) ?>" hidden>
                                        <button class="warning" type="submit" name="adminDelAnswer"><i class="fas fa-trash-alt"></i>Supprimer</button>
                                    </form>
                                <?php endif; ?>

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