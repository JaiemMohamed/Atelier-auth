<?php
require_once __DIR__ . '/connexion_bdd.php';
require_once __DIR__ . '/auth.php';
ProtegerPage();

$sql = "SELECT id, nom, prenom, age, formation, moyenne
        FROM etudiants
        ORDER BY nom, prenom";

$stmt = $pdo->query($sql);
$etudiants = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Liste des étudiants</title>
</head>
<body>
    <h1>Liste des étudiants</h1>
    <p>
        <a href="ajouter.php">Ajouter un étudiant</a>
        | <a href="dashboard.php">Retour au tableau de bord</a>
    </p>
    <table border="1" cellpadding="8">
        <thead>
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Âge</th>
        <th>Formation</th>
        <th>Moyenne</th>
        <th>Actions</th> 
    </tr>
</thead>
<tbody>
    <?php foreach ($etudiants as $etudiant): ?>
    <tr>
        <td><?= (int) $etudiant['id'] ?></td>
        <td><?= htmlspecialchars($etudiant['nom']) ?></td>
        <td><?= htmlspecialchars($etudiant['prenom']) ?></td>
        <td><?= (int) $etudiant['age'] ?></td>
        <td><?= htmlspecialchars($etudiant['formation']) ?></td>
        <td><?= htmlspecialchars((string) $etudiant['moyenne']) ?></td>
        <td>
            <a href="modifier.php?id=<?= (int) $etudiant['id'] ?>">Modifier</a>
            <form action="supprimer.php" method="POST" style="display:inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?');">
                <input type="hidden" name="id" value="<?= (int) $etudiant['id'] ?>">
                <button type="submit" style="color: red; background: none; border: none; cursor: pointer; padding: 0; text-decoration: underline;">Supprimer</button>
            </form>
        </td>
    </tr>
    <?php endforeach; ?>
</tbody>
    </table>
    <br>
    <?php

        $sqlStats = "SELECT 
                COUNT(*) AS nombre_etudiants,
                AVG(moyenne) AS moyenne_classe,
                MAX(moyenne) AS meilleure_moyenne,
                MIN(moyenne) AS plus_faible_moyenne
             FROM etudiants";

        $stmtStats = $pdo->query($sqlStats);
    $stats = $stmtStats->fetch();
    ?>


    <div style="background: #eef2f3; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
        <h3>Statistiques de la classe</h3>
        <ul>
            <li>Nombre total d'étudiants : <strong><?= (int) $stats['nombre_etudiants'] ?></strong></li>
            <li>Moyenne générale de la classe : <strong><?= number_format((float) $stats['moyenne_classe'], 2, ',', ' ') ?> / 20</strong></li>
            <li>Meilleure moyenne : <strong><?= number_format((float) $stats['meilleure_moyenne'], 2, ',', ' ') ?> / 20</strong></li>
            <li>Plus faible moyenne : <strong><?= number_format((float) $stats['plus_faible_moyenne'], 2, ',', ' ') ?> / 20</strong></li>
        </ul>
    </div>
</body>
</html>