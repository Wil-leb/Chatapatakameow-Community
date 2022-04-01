<?php
use App\model\{Reports};

$findReports = new Reports();
?>

<section class="container">
    <h1>User dashboard</h1>

    <?php if(!$_POST) : ?>
        <p>Bienvenue au tableau de bord des utilisateurs&nbsp;! Cette page permet de supprimer des utilisateurs, ou d'accéder à une autre page pour modifier leurs informations.</p>
    <?php endif; ?>

<!-- ACCOUNT SUSPENSION MESSAGES ------------------------------------------------------------------------------------------------------>
    <?php if(empty($suspensionMsg["success"])) : ?>
        <?php if(!empty($suspensionMsg["errors"])) { ?>
            <p class="error"><?= $suspensionMsg["errors"][0] ?></p>
        <?php } ?>
    
    <?php else : ?>
        <ul class="success">
            <?php foreach($suspensionMsg["success"] as $success) : ?>    
                <li><?= $success ?></li>
            <?php endforeach ?>    
        </ul>
    <?php endif; ?>

<!-- ACCOUNT REACTIVATION MESSAGES ---------------------------------------------------------------------------------------------------->
    <?php if(empty($reactivationMsg["success"])) : ?>
        <?php if(!empty($reactivationMsg["errors"])) { ?>
            <p class="error"><?= $reactivationMsg["errors"][0] ?></p>
        <?php } ?>
    
    <?php else : ?>
        <ul class="success">
            <?php foreach($reactivationMsg["success"] as $success) : ?>    
                <li><?= $success ?></li>
            <?php endforeach ?>    
        </ul>
    <?php endif; ?>

<!-- USER DELETION MESSAGES ----------------------------------------------------------------------------------------------------------->
    <?php if(empty($userDelMsg["success"])) : ?>
        <?php if(!empty($userDelMsg["errors"])) { ?>
            <p class="error"><?= $userDelMsg["errors"][0] ?></p>
        <?php } ?>
    
    <?php else : ?>
        <ul class="success">
            <?php foreach($userDelMsg["success"] as $success) : ?>    
                <li><?= $success ?></li>
            <?php endforeach ?>    
        </ul>
    <?php endif; ?>
</section>

<section class="container">
    <?php if(!$_POST) : ?>
        <h2>Utilisateurs</h2>
        <table>
            <thead>
                <tr>
                    <th>Adresse électronique</th>
                    <th>Pseudo</th>
                    <th>Publications signalées</th>
                    <th>État du compte</th>
                    <th>Action</th>
                </tr>
            </thead>
            
            <tbody>
                <!-- Finding the users from the database, and displaying them in the table's body -->
                <?php foreach($users as $user) : ?>
                    <?php $reports = $findReports->countUserReports($user["id"]); ?>
                    <tr>
                        <td data-label="Adresse électronique" id="userEmail"><?= htmlspecialchars(trim($user["email"])) ?></td>
                        <td data-label="Pseudo"><?= htmlspecialchars(trim($user["login"])) ?></td>
                        <td data-label="Publications signalées" id="reports-number">
                            <?php if(!$reports) : echo "0"; ?>
                            <?php else : echo htmlspecialchars(trim($reports["totalReports"])) ?>
                            <?php endif; ?>
                            </td>
                        <td data-label="État du compte">
                            <?php if($user["account_suspended"] === "0") : ?>
                                Actif
                            <?php else : ?>
                                Suspendu
                            <?php endif; ?>

                            <div class="deletion tablet">
                                <?php if($user["account_suspended"] === "0" && $reports && $reports["totalReports"] >= "10") : ?>
                                    <form action="" method="post" onsubmit="confirmSuspension(event)">
                                        <input type="text" name="userId" value="<?= htmlspecialchars(trim($user["id"])) ?>" hidden>
                                        <button class="deactivate" type="submit" name="suspendAccount"><i class="fa-solid fa-circle-minus"></i>Suspendre</button>
                                    </form>

                                <?php elseif($user["account_suspended"] === "1") : ?>
                                    <form action="" method="post" onsubmit="confirmReactivation(event)">
                                        <input type="text" name="userId" value="<?= htmlspecialchars(trim($user["id"])) ?>" hidden>
                                        <button class="reactivate" type="submit" name="reactivateAccount"><i class="fa-solid fa-arrow-rotate-right"></i>Réactiver</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td data-label="Action">
                            <div class="deletion tablet">
                                <form action="" method="post" onsubmit="confirmDeletion(event)">
                                <input type="text" name="userId" value="<?= htmlspecialchars(trim($user["id"])) ?>" hidden>
                                    <button class="warning" type="submit" name="deleteUser"><i class="fas fa-trash-alt"></i>Supprimer</button>
                                </form>

                                <p><a href="index.php?p=modifyUser&userId=<?= htmlspecialchars($user["id"])?>"><i class="fas fa-pen"></i>Modifier</a></p>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <nav>
            <ul class="pagination">
                <!-- Lien vers la page précédente (désactivé si on se trouve sur la 1ère page) -->
                <!-- <li class="page-item <?= ($currentPage == 1) ?>">
                    <a href="index.php?p=userDashboard&page=<?= $currentPage - 1 ?>" class="page-link">Précédente</a>
                </li> -->
                <!-- <?php for($page = 1; $page <= $pages; $page++): ?> -->
                <!-- Lien vers chacune des pages (activé si on se trouve sur la page correspondante) -->
                    <li class="page-item <?= ($currentPage == $page) ?>">
                        <a href="index.php?p=userDashboard&page=<?= $page ?>" class="page-link"><?= $page ?></a>
                    </li>
                <?php endfor ?>
                <!-- Lien vers la page suivante (désactivé si on se trouve sur la dernière page) -->
                <!-- <li class="page-item <?= ($currentPage == $pages) ?>">
                    <a href="index.php?p=userDashboard&page=<?= $currentPage + 1 ?>" class="page-link">Suivante</a>
                </li> -->
            </ul>
        </nav>
    <?php endif; ?>
    
    <p class="redirect">Revenir au <a href="index.php?p=dashboard">tableau de bord administrateur</a></p>
</section>