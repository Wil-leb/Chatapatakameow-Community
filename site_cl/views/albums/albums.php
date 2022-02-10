<?php
use App\model\{Album, Comments};

$findImages = new Album();
$findComments = new Comments();
?>
    
<section class="container">
    <h1>Album <?= htmlspecialchars(trim($albums["title"])) ?></h1>

    <p>Bienvenue sur la page de l'album sélectionné publié par <?= htmlspecialchars(trim($albums["user_login"])) ?>. Bon visionnage et n'hésite pas à voter et à laisser des commentaires&nbsp;! Aucun de tes identifiants ne sera divulgué à des sites tiers. N'hésite pas à consulter notre <a href="index.php?p=privacyPolicy">politique de confidentialité</a> pour plus d'informations.</p>

    <?php if(empty($answerMsg["success"])) : ?>
        <?php if(!empty($answerMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($answerMsg["errors"] as $error): ?>    
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        <?php } ?>
    <?php else : ?>
        <p class="success"><?= $answerMsg["success"][0] ?></p>
    <?php endif; ?>

    <?php if(empty($commentMsg["success"])) : ?>
        <?php if(!empty($commentMsg["errors"])) { ?>
            <ul class="error">
                <?php foreach($commentMsg["errors"] as $error): ?>    
                    <li><?= $error ?></li>
                <?php endforeach ?>
            </ul>
        <?php } ?>
    <?php else : ?>
        <p class="success"><?= $commentMsg["success"][0] ?></p>
    <?php endif; ?>

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

    <?php if(!empty($commentDelMsg["success"])) : ?>
        <p class="success"><?= $commentDelMsg["success"][0] ?></p>
    <?php endif; ?>

    <?php if(!empty($answerDelMsg["success"])) : ?>
        <p class="success"><?= $answerDelMsg["success"][0] ?></p>
    <?php endif; ?>
</section>
    
<section class="container">    
    <?php $comments = $findComments->findAlbumComments($albums["id"]); ?>
    <?php $pictures = $findImages->findAlbumPictures($albums["id"]); ?>

    <article>
        <div class="scroll-slider">
            <div class="albums">
                <div class="content" data-id="<?= htmlspecialchars($albums["id"]) ?>">
                    <?php require "assets/php/Images.php"; ?>
                </div>
            </div>
        </div>

        <div class="vote <?= $opinion ?>">
            <div class="vote-bar">
                <div class="vote-progress" style="width: <?= (htmlspecialchars(trim($albums["likes"])) + htmlspecialchars(trim($albums["dislikes"]))) == 0 ? 100 : round(100 * (htmlspecialchars(trim($albums["likes"])) / (htmlspecialchars(trim($albums["likes"])) + htmlspecialchars(trim($albums["dislikes"]))))); ?>%"></div>
            </div>

            <div class="vote-logos">
                <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
                    <input type="text" name="albumId" value="<?= htmlspecialchars(trim($albums["id"])) ?>" hidden>
                    <input type="text" name="voteValue" value="1" hidden>
                    <button type="submit" name="likeAlb" value="like" class="vote-thumb like"><i class="fas fa-thumbs-up"></i> <?= htmlspecialchars(trim($albums["likes"]))?></button>
                </form>
                
                <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
                    <input type="text" name="albumId" value="<?= htmlspecialchars(trim($albums["id"])) ?>" hidden>
                    <input type="text" name="voteValue" value="-1" hidden>
                    <button type="submit" name="dislikeAlb" value="dislike" class="vote-thumb dislike"><i class="fas fa-thumbs-down"></i> <?= htmlspecialchars(trim($albums["dislikes"]))?></button>
                </form>
            </div>
        </div>
    </article>
    

    <h2>Commentaires</h2>

    <?php if(empty($comments)) : ?>
        <p class="no-content">Sois le premier à commenter cet album&nbsp;!</p>
    <?php else : ?>
        <div>
            <?php foreach($comments as $comment) : ?>
                <div class="comment-content">
                    <?php $answers = $findComments->findCommentAnswers(htmlspecialchars(trim($comment["id"]))); ?>
                    <p><?= htmlspecialchars($comment["user_login"]) ?></p>
                    <p><?= htmlspecialchars(trim(strftime("%d/%m/%Y", strtotime($comment["post_date"])))) ?></p>
                    <p><?= htmlspecialchars(trim($comment["comment"])) ?></p>

                    <?php if($comment["user_ip"]) : ?>
                        <div class="action-buttons">
                            <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post" onsubmit="confirmDeletion(event)">
                                <input type="text" name="commentId" value="<?= htmlspecialchars($comment["id"]) ?>" hidden>
                                <input type="text" name="albumTitle" value="<?= htmlspecialchars(trim($comment["album_title"])) ?>" hidden>
                                <button class="delete" name="deleteComment"><i class="fas fa-trash-alt"></i>Supprimer</button>
                            </form>

                            <button id="hide-content" value="ON"><i class="fas fa-pen"></i>Modifier</button>

                            <dialog open>
                                <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post" onsubmit="confirmAnsweraddition(event)">
                                    <p class="mandatory">Ce champ est obligatoire.</p>

                                    <textarea name="comment" class="comment" rows="8" cols="40"><?= htmlspecialchars(trim($comment["comment"])) ?></textarea>
                                    <div></div>
                                    
                                    <div class="rules">
                                        <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules">règlement général</a></label>	
                                        <input type="checkbox" value="true" name="acceptRules">
                                    </div>

                                    <div class="rules">
                                        <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy">politique de confidentialité</a></label>	
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
                        <div class="vote-logos">
                            <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
                                <input type="text" name="commentId" value="<?= htmlspecialchars(trim($comment["id"])) ?>" hidden>
                                <input type="text" name="voteValue" value="1" hidden>
                                <button type="submit" name="likeComm" value="like" class="vote-thumb like"><i class="fas fa-thumbs-up"></i> <?= htmlspecialchars(trim($comment["likes"]))?></button>
                            </form>
                    
                            <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
                                <input type="text" name="commentId" value="<?= htmlspecialchars(trim($comment["id"])) ?>" hidden>
                                <input type="text" name="voteValue" value="-1" hidden>
                                <button type="submit" name="dislikeComm" value="dislike" class="vote-thumb dislike"><i class="fas fa-thumbs-down"></i> <?= htmlspecialchars(trim($comment["dislikes"]))?></button>
                            </form>
                        </div>

                        <button id="hide-content" value="ON"><i class="fas fa-reply"></i>Répondre</button>

                        <dialog open>
                            <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post" onsubmit="confirmAnsweraddition(event)">
                                <p class="mandatory">Tous les champs sont obligatoires.</p>

                                <input type="text" name="email" class="email" placeholder="Email" <?php if($session::online()) : ?> value="<?= $_SESSION["user"]["email"] ?>" <?php endif; ?>>
                                <div></div>

                                <input type="text" name="commentLogin" class="comment-login" placeholder="Pseudo" <?php if($session::online()) : ?> value="<?= $_SESSION["user"]["login"] ?>" <?php endif; ?>>
                                <div></div>

                                <textarea name="answer" class="answer" rows="8" cols="40" placeholder="Réponse"></textarea>
                                <div></div>

                                <div class="rules">
                                    <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules">règlement général</a></label>	
                                    <input type="checkbox" value="true" name="acceptRules">
                                </div>

                                <div class="rules">
                                    <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy">politique de confidentialité</a></label>	
                                    <input type="checkbox" value="true" name="acceptPolicy">
                                </div>

                                <input type="text" name="commentId" value="<?= htmlspecialchars(trim($comment["id"])) ?>" hidden>
                                <input type="text" name="albumTitle" value="<?= htmlspecialchars(trim($comment["album_title"])) ?>" hidden>
                                <input type="submit" name="postAnswer" value="Publier la réponse">
                            </form>

                            <button id="close"><i class="far fa-window-close"></i>Fermer</button>
                        </dialog>

                        <button id="hide-answers" value="ON"><i class="fas fa-caret-right"></i>Réponses</button>
                    </div>

                    <div class="answer-content">
                        <?php if(empty($answers)) : ?>
                            <p class="no-content">Sois le premier à répondre à ce commentaire&nbsp;!</p>
                        <?php else : ?>
                            <?php foreach($answers as $answer) : ?>
                                <p><?= htmlspecialchars($answer["user_login"]) ?></p>
                                <p><?= htmlspecialchars(trim(strftime("%d/%m/%Y", strtotime($answer["post_date"])))) ?></p>
                                <p><?= htmlspecialchars(trim($answer["answer"])) ?></p>

                                <?php if($answer["user_ip"]) : ?>
                                    <div class="action-buttons">
                                        <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post" onsubmit="confirmDeletion(event)">
                                            <input type="text" name="commentId" value="<?= htmlspecialchars($answer["comment_id"]) ?>" hidden>
                                            <input type="text" name="answerId" value="<?= htmlspecialchars($answer["id"]) ?>" hidden>
                                            <input type="text" name="albumTitle" value="<?= htmlspecialchars($answer["album_title"]) ?>" hidden>
                                            <button class="delete" name="deleteAnswer"><i class="fas fa-trash-alt"></i>Supprimer</button>
                                        </form>

                                        <button id="hide-content" value="ON"><i class="fas fa-pen"></i>Modifier</button>

                                        <dialog open>
                                            <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post" onsubmit="confirmAnsweraddition(event)">
                                                <p class="mandatory">Ce champ est obligatoire.</p>
                                                
                                                <textarea name="answer" class="answer" rows="8" cols="40"><?= htmlspecialchars(trim($answer["answer"])) ?></textarea>
                                                <div></div>

                                                <div class="rules">
                                                    <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules">règlement général</a></label>	
                                                    <input type="checkbox" value="true" name="acceptRules">
                                                </div>

                                                <div class="rules">
                                                    <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy">politique de confidentialité</a></label>	
                                                    <input type="checkbox" value="true" name="acceptPolicy">
                                                </div>
                                                
                                                <input type="text" name="answerId" value="<?= htmlspecialchars(trim($answer["id"])) ?>" hidden>
                                                <input type="submit" name="changeAnswer" value="Modifier la réponse">
                                            </form>

                                            <button id="close"><i class="far fa-window-close"></i>Fermer</button>
                                        </dialog>
                                    </div>
                                <?php endif; ?>

                                <div class="vote-logos">
                                    <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
                                        <input type="text" name="answerId" value="<?= htmlspecialchars(trim($answer["id"])) ?>" hidden>
                                        <input type="text" name="voteValue" value="1" hidden>
                                        <button type="submit" name="likeAnsw" value="like" class="vote-thumb like"><i class="fas fa-thumbs-up"></i> <?= htmlspecialchars(trim($answer["likes"]))?></button>
                                    </form>
                            
                                    <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post">
                                        <input type="text" name="answerId" value="<?= htmlspecialchars(trim($answer["id"])) ?>" hidden>
                                        <input type="text" name="voteValue" value="-1" hidden>
                                        <button type="submit" name="dislikeAnsw" value="dislike" class="vote-thumb dislike"><i class="fas fa-thumbs-down"></i> <?= htmlspecialchars(trim($answer["dislikes"]))?></button>
                                    </form>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <h3>Commenter l'album</h3>
    <p class="mandatory">Tous les champs sont obligatoires.</p>
        
    <form action="index.php?p=albums&albumId=<?= htmlspecialchars(trim($albums["id"])) ?>" method="post" onsubmit="confirmCommaddition(event)">
        <input type="text" name="email" class="email" placeholder="Email" <?php if($session::online()) : ?> value="<?= $_SESSION["user"]["email"] ?>" <?php endif; ?>>
        <div></div>

        <label for="commentLogin">Pseudo&nbsp;:</label>
        <input type="text" name="commentLogin" class="comment-login" placeholder="Pseudo" <?php if($session::online()) : ?> value="<?= $_SESSION["user"]["login"] ?>" <?php endif; ?>>
        <div></div>

        <textarea name="comment" class="comment" rows="8" cols="40" placeholder="Commentaire"></textarea>
        <div></div>

        <div class="rules">
            <label for="acceptRules">J'ai lu et j'accepte le <a href="index.php?p=rules">règlement général</a></label>	
            <input type="checkbox" value="true" name="acceptRules">
        </div>

        <div class="rules">
            <label for="acceptPolicy">J'ai lu et j'accepte la <a href="index.php?p=privacyPolicy">politique de confidentialité</a></label>	
            <input type="checkbox" value="true" name="acceptPolicy">
        </div>

        <input type="text" name="albumTitle" value="<?= htmlspecialchars(trim($albums["title"])) ?>" hidden>
        <input type="submit" name="postComment" value="Publier le commentaire">
    </form>
    
    <p class="redirect">Revenir à la <a href="index.php?p=albumPublishers">liste des auteurs</a></p>
    <p class="redirect">Revenir à la <a href="index.php?p=home">page d'accueil</a></p>
</section>