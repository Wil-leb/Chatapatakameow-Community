<section class="container">
    <h1>User dashboard</h1>

    <?php if(!$_POST) : ?>
        <p>Bienvenue au tableau de bord des utilisateurs&nbsp;! Cette page permet de supprimer des utilisateurs, ou d'accéder à une autre page pour modifier leurs informations.</p>
    <?php endif; ?>

    <?php if(!empty($userDelMsg["success"])) : ?>
        <p class="success"><?= $userDelMsg["success"][0] ?></p>
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
                    <th>Action</th>
                </tr>
            </thead>
            
            <tbody>
                <!-- Finding the users from the database, and displaying them in the table's body -->
                <?php foreach($users as $user) : ?>
                    <tr>
                        <td data-label="Adresse électronique" id="userEmail"><?= htmlspecialchars(trim($user["email"])) ?></td>
                        <td data-label="Pseudo"><?= htmlspecialchars(trim($user["login"])) ?></td>
                        <td data-label="Action" class="deletion">
                            <div class="deletion">
                                <form action="index.php?p=userDashboard&userId=<?= htmlspecialchars(trim($user["id"])) ?>" method="post" onsubmit="confirmDeletion(event)">
                                    <button class="delete" type="submit" name="deleteUser"><i class="fas fa-trash-alt"></i>Supprimer</button>
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