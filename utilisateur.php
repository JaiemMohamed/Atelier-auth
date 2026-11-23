<?php 
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/connexion_bdd.php";

protgerAdmin();
$sql ="SELECT id,username,role,actif,created_at
        FROM utilisateurs 
        ORDER BY username";

$stmt = $pdo ->query($sql);
$utilisateurs = $stmt -> fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des utilisateurs</title>
</head>
<body>

    <h1>Gestion des utilisateurs</h1>
    <p><a href="dashboard.php">Retour au tableau de board</a></p>
    <p><a href="utilisateur-creer.php">Créer un utilisateur </a></p>

    <table border="1" cellpadding="8">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom d'utilisateur</th>
                <th>Rôle</th>
                <th>Statut</th>
                <th>Créé le </th>
                <th>Actions </th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($utilisateurs as $u):?>
                <tr>
                    <td><?= $u['id'] ?></td>
                    <td><?php echo htmlspecialchars($u['username']) ?></td>
                    <td><?php echo htmlspecialchars($u['role'])?></td>
                    <td><?= $u['actif']? 'Actif':'Inactif' ?></td>
                    <td><?php echo htmlspecialchars($u['created_at']) ?></td>
                    <td>
                        <a href="utilisateur-role.php?id=<?= (int) $u["id"] ?>">Role</a>
                        <a href="utilisateur-satut.php?id=<?= (int) $u["id"] ?>">Activer/Desactiver</a>
                        <a href="utilisateur-supprimer.php?id=<?= (int) $u["id"] ?>">Supprimer</a>
                    </td>
                </tr>
            <?php endforeach;?>
        </tbody>
    </table>
    
</body>
</html>