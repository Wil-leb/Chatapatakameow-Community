<?php
use App\model\{Albums, Comments};

$findThumbs = new Albums();
$findComments = new Comments();
?>

<section class="container">
    <h1><?= htmlspecialchars(trim($albums["title"])) ?></h1>

    <p>Bienvenue sur la page de l'album sélectionné publié par <?= htmlspecialchars(trim($albums["user_login"])) ?>. Bon visionnage et n'hésite pas à voter et à laisser des commentaires&nbsp;! Aucun de tes identifiants ne sera divulgué à des sites tiers. N'hésite pas à consulter notre <a href="index.php?p=privacyPolicy">politique de confidentialité</a> pour plus d'informations.</p>

<!-- ANSWER POST MESSAGES ------------------------------------------------------------------------------------------------------------->
    <?php if(empty($answerMsg["success"])) : ?>
        <?php if(!empty($answerMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($answerMsg["errors"] as $error): ?>    
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        <?php } ?>
    
    <?php else : ?>
        <ul class="success">
            <?php foreach($answerMsg["success"] as $success) : ?>    
                <li><?= $success ?></li>
            <?php endforeach ?>    
        </ul>
    <?php endif; ?>

<!-- COMMENT POST MESSAGES ------------------------------------------------------------------------------------------------------------>
    <?php if(empty($commentMsg["success"])) : ?>
        <?php if(!empty($commentMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($commentMsg["errors"] as $error): ?>    
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        <?php } ?>
    
    <?php else : ?>
        <ul class="success">
            <?php foreach($commentMsg["success"] as $success) : ?>    
                <li><?= $success ?></li>
            <?php endforeach ?>    
        </ul>
    <?php endif; ?>

<!-- ANSWER MODIFICATION MESSAGES ----------------------------------------------------------------------------------------------------->
    <?php if(empty($answModifMsg["success"])) : ?>
        <?php if(!empty($answModifMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($answModifMsg["errors"] as $error): ?>    
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        <?php } ?>
    <?php else : ?>
        <p class="success"><?= $answModifMsg["success"][0] ?></p>
    <?php endif; ?>

<!-- COMMENT MODIFICATION MESSAGES ---------------------------------------------------------------------------------------------------->
    <?php if(empty($comModifMsg["success"])) : ?>
        <?php if(!empty($comModifMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($comModifMsg["errors"] as $error): ?>    
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        <?php } ?>
    <?php else : ?>
        <p class="success"><?= $comModifMsg["success"][0] ?></p>
    <?php endif; ?>

<!-- ANSWER DELETION MESSAGES --------------------------------------------------------------------------------------------------------->
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

<!-- COMMENT DELETION MESSAGES -------------------------------------------------------------------------------------------------------->
    <?php if(empty($commentDelMsg["success"])) : ?>
        <?php if(!empty($commentDelMsg["errors"])) { ?>
            <p class="error"><?= $addMessages["errors"][0] ?></p>
        <?php } ?>
    
    <?php else : ?>
        <ul class="success">
            <?php foreach($commentDelMsg["success"] as $success) : ?>    
                <li><?= $success ?></li>
            <?php endforeach ?>    
        </ul>
    <?php endif; ?>
</section>
    
<section class="container">    
    <?php $comments = $findComments->findAlbumComments($albums["id"]); ?>
    <?php $thumbnails = $findThumbs->findAlbumThumbnails($albums["id"]); ?>

<!-- ALBUM SLIDER --------------------------------------------------------------------------------------------------------------------->
    <article>
        <div class="scroll-slider">
            <div class="albums">
                <div class="content" data-id="<?= htmlspecialchars($albums["id"]) ?>">
                    <?php require "assets/php/Images.php"; ?>
                </div>
            </div>
        </div>

        <div class="action-buttons">
<!-- ALBUM (DIS)LIKE FORM ------------------------------------------------------------------------------------------------------------->
            <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
            <!-- <form id="like-form" method="post"> -->
                <input type="text" name="albumId" value="<?= htmlspecialchars(trim($albums["id"])) ?>" hidden>
                <button type="submit" name="likeAlb" value="like" class="vote-thumb like"><i class="fas fa-thumbs-up"></i><?= htmlspecialchars(trim($albums["likes"]))?></button>
            </form>
            
            <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
            <!-- <form id="dislike-form" method="post"> -->
                <input type="text" name="albumId" value="<?= htmlspecialchars(trim($albums["id"])) ?>" hidden>
                <button type="submit" name="dislikeAlb" value="dislike" class="vote-thumb dislike"><i class="fas fa-thumbs-down"></i><?= htmlspecialchars(trim($albums["dislikes"]))?></button>
            </form>

<!-- ALBUM LINK COPY BUTTON -->
            <button id="copy-link"><i class="fa-solid fa-link"></i>Copier le lien</button>

<!-- ALBUM SHARE BUTTTON -------------------------------------------------------------------------------------------------------------->
            <button id="hide-content" class="share" value="ON"><i class="fa-solid fa-share"></i>Partager</button>

            <dialog class="social-media" open>
                <p><a href="https://fr-fr.facebook.com/" target="blank"><i class="fa-brands fa-facebook"></i></a></p>
                <p><a href="https://twitter.com/" target="blank"><i class="fa-brands fa-twitter"></i></i></a></p>
                <p><a href="https://www.pinterest.fr/" target="blank"><i class="fa-brands fa-pinterest"></i></a></p>
                <p><a href="https://www.instagram.com/" target="blank"><i class="fa-brands fa-instagram"></i></a></p>
                <p><a href="https://www.whatsapp.com/?lang=fr" target="blank"><i class="fa-brands fa-whatsapp"></i></a></p>
                <p><a href="https://discord.com/" target="blank"><i class="fab fa-discord"></i></a></p>

                <button id="close"><i class="far fa-window-close"></i>Fermer</button>
            </dialog>

<!-- ALBUM REPORT FORM ---------------------------------------------------------------------------------------------------------------->
            <form action="" method="post">
                <input type="text" name="albumId" value="<?= htmlspecialchars(trim($albums["id"])) ?>" hidden>
                <button type="submit" name="reportAlb" class="warning report"><i class="fa-solid fa-circle-minus"></i>Signaler</button>
            </form>
        </div>
    </article>

    <h2>Commentaires</h2>

    <?php if(empty($comments)) : ?>
        <p class="no-content">Sois le premier à commenter cet album&nbsp;!</p>
    <?php else : ?>
        <!-- <div> -->
            <?php foreach($comments as $comment) : ?>
                <div class="comment-content">
                    <?php $answers = $findComments->findCommentAnswers(htmlspecialchars(trim($comment["id"]))); ?>
                    <p><?= htmlspecialchars($comment["comment_login"]) ?></p>
                    <p><?= htmlspecialchars(trim(strftime("%d/%m/%Y", strtotime($comment["post_date"])))) ?></p>
                    <p><?= htmlspecialchars(trim($comment["comment"])) ?></p>

                    <?php if($comment["comment_ip"]) : ?>
                        <div class="action-buttons">
<!-- COMMENT DELETION FORM ------------------------------------------------------------------------------------------------------------>
                            <form action="" method="post" onsubmit="confirmDeletion(event)">
                                <input type="text" name="commentId" value="<?= htmlspecialchars(trim($comment["id"])) ?>" hidden>
                                <button class="warning delete" name="deleteComment"><i class="fas fa-trash-alt"></i>Supprimer</button>
                            </form>

<!-- BUTTON TO DISPLAY THE COMMENT MODIFICATION FORM ---------------------------------------------------------------------------------->
                            <button id="hide-content" class="edit" value="ON"><i class="fas fa-pen"></i>Modifier</button>

<!-- COMMENT MODIFICATION FORM -------------------------------------------------------------------------------------------------------->
                            <dialog open>
                                <form action="" method="post" onsubmit="confirmAnsweraddition(event)">
                                    <p class="mandatory">Ce champ est obligatoire.</p>

                                    <textarea name="comment" id="comment" rows="8" cols="40"><?= htmlspecialchars(trim($comment["comment"])) ?></textarea>
                                    <div></div>
                                    
                                    <div class="rules">
                                        <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules" target="blank">règlement général</a></label>	
                                        <input type="checkbox" value="true" name="acceptRules">
                                    </div>

                                    <div class="rules">
                                        <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy" target="blank">politique de confidentialité</a></label>	
                                        <input type="checkbox" value="true" name="acceptPolicy">
                                    </div>

                                    <input type="text" name="commentId" value="<?= htmlspecialchars($comment["id"]) ?>" hidden>
                                    <input type="submit" name="changeComment" value="Modifier le commentaire">
                                </form>

                                <button id="close"><i class="far fa-window-close"></i>Fermer</button>
                            </dialog>
                        </div>
                    <?php endif; ?>

                    <div class="action-buttons">
<!-- COMMENT (DIS)LIKE FORM ----------------------------------------------------------------------------------------------------------->
                        <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
                        <!-- <form id="like-form" method="post"> -->
                            <input type="text" name="commentId" value="<?= htmlspecialchars(trim($comment["id"])) ?>" hidden>
                            <button type="submit" name="likeComm" value="like" class="vote-thumb like"><i class="fas fa-thumbs-up"></i><?= htmlspecialchars(trim($comment["likes"]))?></button>
                        </form>
                
                        <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
                        <!-- <form id="dislike-form" method="post"> -->
                            <input type="text" name="commentId" value="<?= htmlspecialchars(trim($comment["id"])) ?>" hidden>
                            <button type="submit" name="dislikeComm" value="dislike" class="vote-thumb dislike"><i class="fas fa-thumbs-down"></i><?= htmlspecialchars(trim($comment["dislikes"]))?></button>
                        </form>

<!-- BUTTON TO DISPLAY THE ANSWER ADDITION FORM --------------------------------------------------------------------------------------->
                        <button id="hide-content" class="reply" value="ON"><i class="fas fa-reply"></i>Répondre</button>

<!-- ANSWER ADDITION FORM ------------------------------------------------------------------------------------------------------------->
                        <dialog open>
                            <form action="" method="post" onsubmit="confirmAnsweraddition(event)">
                                <p class="mandatory">Tous les champs sont obligatoires.</p>

                                <input type="text" name="email" class="email" placeholder="Email" <?php if($session::online()) : ?> value="<?= $_SESSION["user"]["email"] ?>" <?php endif; ?>>
                                <div></div>

                                <input type="text" name="commentLogin" class="comment-login" placeholder="Pseudo" <?php if($session::online()) : ?> value="<?= $_SESSION["user"]["login"] ?>" <?php endif; ?>>
                                <div></div>

                                <textarea name="answer" id="answer" rows="8" cols="40" placeholder="Réponse"></textarea>
                                <div></div>

                                <div class="rules">
                                    <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules" target="blank">règlement général</a></label>	
                                    <input type="checkbox" value="true" name="acceptRules">
                                </div>

                                <div class="rules">
                                    <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy" target="blank">politique de confidentialité</a></label>	
                                    <input type="checkbox" value="true" name="acceptPolicy">
                                </div>
                                
                                <input type="text" name="commentId" value="<?= htmlspecialchars(trim($comment["id"])) ?>" hidden>
                                <input type="submit" name="postAnswer" value="Publier la réponse">
                            </form>

                            <button id="close"><i class="far fa-window-close"></i>Fermer</button>
                        </dialog>

<!-- COMMENT REPORT FORM -------------------------------------------------------------------------------------------------------------->
                        <form action="" method="post">
                            <input type="text" name="commentId" value="<?= htmlspecialchars(trim($comment["id"])) ?>" hidden>
                            <button type="submit" name="reportComm" class="warning report"><i class="fa-solid fa-circle-minus"></i>Signaler</button>
                        </form>

<!-- BUTTON TO DISPLAY THE ANSWERS ---------------------------------------------------------------------------------------------------->
                        <button id="hide-answers" class="answers" value="ON"><i class="fas fa-caret-right"></i>Réponses</button>
                    </div>

                    <div class="answer-content">
<!-- ANSWERS -------------------------------------------------------------------------------------------------------------------------->
                        <?php if(empty($answers)) : ?>
                            <p class="no-content">Sois le premier à répondre à ce commentaire&nbsp;!</p>
                        <?php else : ?>
                            <?php foreach($answers as $answer) : ?>
                                <p><?= htmlspecialchars($answer["answer_login"]) ?></p>
                                <p><?= htmlspecialchars(trim(strftime("%d/%m/%Y", strtotime($answer["post_date"])))) ?></p>
                                <p><?= htmlspecialchars(trim($answer["answer"])) ?></p>

                                <?php if($answer["answer_ip"]) : ?>
                                    <div class="action-buttons">
<!-- ANSWER DELETION FORM ------------------------------------------------------------------------------------------------------------->
                                        <form action="" method="post" onsubmit="confirmDeletion(event)">
                                            <input type="text" name="answerId" value="<?= htmlspecialchars($answer["id"]) ?>" hidden>
                                            <input type="text" name="commentId" value="<?= htmlspecialchars($answer["comment_id"]) ?>" hidden>
                                            <button class="warning delete" name="deleteAnswer"><i class="fas fa-trash-alt"></i>Supprimer</button>
                                        </form>

<!-- BUTTON TO DISPLAY THE ANSWER MODIFICATION FORM ----------------------------------------------------------------------------------->
                                        <button id="hide-content" class="edit" value="ON"><i class="fas fa-pen"></i>Modifier</button>

<!-- ANSWER MODIFICATION FORM --------------------------------------------------------------------------------------------------------->
                                        <dialog open>
                                            <form action="" method="post" onsubmit="confirmAnsweraddition(event)">
                                                <p class="mandatory">Ce champ est obligatoire.</p>
                                                
                                                <textarea name="answer" id="answer" rows="8" cols="40"><?= htmlspecialchars(trim($answer["answer"])) ?></textarea>
                                                <div></div>

                                                <div class="rules">
                                                    <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules" target="blank">règlement général</a></label>	
                                                    <input type="checkbox" value="true" name="acceptRules">
                                                </div>

                                                <div class="rules">
                                                    <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy" target="blank">politique de confidentialité</a></label>	
                                                    <input type="checkbox" value="true" name="acceptPolicy">
                                                </div>
                                                
                                                <input type="text" name="answerId" value="<?= htmlspecialchars(trim($answer["id"])) ?>" hidden>
                                                <input type="text" name="commentId" value="<?= htmlspecialchars(trim($answer["comment_id"])) ?>" hidden>
                                                <input type="submit" name="changeAnswer" value="Modifier la réponse">
                                            </form>

                                            <button id="close"><i class="far fa-window-close"></i>Fermer</button>
                                        </dialog>
                                    </div>
                                <?php endif; ?>

                                <div class="action-buttons">
<!-- ANSWER (DIS)LIKE FORM ------------------------------------------------------------------------------------------------------------>
                                <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
                                    <!-- <form id="like-form" method="post"> -->
                                        <input type="text" name="answerId" value="<?= htmlspecialchars(trim($answer["id"])) ?>" hidden>
                                        <button type="submit" name="likeAnsw" value="like" class="vote-thumb like"><i class="fas fa-thumbs-up"></i><?= htmlspecialchars(trim($answer["likes"]))?></button>
                                    </form>
                            
                                    <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
                                    <!-- <form id="dislike-form" method="post"> -->
                                        <input type="text" name="answerId" value="<?= htmlspecialchars(trim($answer["id"])) ?>" hidden>
                                        <button type="submit" name="dislikeAnsw" value="dislike" class="vote-thumb dislike"><i class="fas fa-thumbs-down"></i><?= htmlspecialchars(trim($answer["dislikes"]))?></button>
                                    </form>

<!-- ANSWER REPORT FORM --------------------------------------------------------------------------------------------------------------->
                                    <form action="" method="post">
                                        <input type="text" name="answerId" value="<?= htmlspecialchars(trim($answer["id"])) ?>" hidden>
                                        <button type="submit" name="reportAnsw" class="warning report"><i class="fa-solid fa-circle-minus"></i>Signaler</button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <!-- </div> -->
    <?php endif; ?>

    <h3>Commenter l'album</h3>
    <p class="mandatory">Tous les champs sont obligatoires.</p>
      
<!-- COMMENT ADDITION FORM ------------------------------------------------------------------------------------------------------------>
    <form action="" method="post" class="comment-form" onsubmit="confirmCommaddition(event)">
        <input type="text" name="email" class="email" placeholder="Email" <?php if($session::online()) : ?> value="<?= $_SESSION["user"]["email"] ?>" <?php endif; ?>>
        <div></div>

        <label for="commentLogin">Pseudo&nbsp;:</label>
            <input type="text" name="commentLogin" class="comment-login" placeholder="Pseudo" <?php if($session::online()) : ?> value="<?= $_SESSION["user"]["login"] ?>" <?php endif; ?>>
        <div></div>

        <textarea name="comment" id="comment" rows="8" cols="40" placeholder="Commentaire"></textarea>
        <div></div>

        <div class="rules">
            <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules" target="blank">règlement général</a></label>	
            <input type="checkbox" value="true" name="acceptRules">
        </div>

        <div class="rules">
            <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy" target="blank">politique de confidentialité</a></label>	
            <input type="checkbox" value="true" name="acceptPolicy">
        </div>

        <input type="submit" name="postComment" value="Publier le commentaire">
    </form>
    
    <p class="redirect">Revenir à la <a href="index.php?p=albumPublishers">liste des auteurs</a></p>
    <p class="redirect">Revenir à la <a href="index.php?p=home">page d'accueil</a></p>
</section>