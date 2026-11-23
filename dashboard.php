<?php 
require_once __DIR__ . "/auth.php";
ProtegerPage();

$utilisateur = utilisateurcourant();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h1>Tableau de board</h1>
    <p> Bienvinue <?php echo htmlspecialchars($utilisateur['username']) ?> </p>

    <p> Role : <?php echo htmlspecialchars($utilisateur['role'])?></p>
    <ul>
        <li><a href="etudiants.php">Gestion des etudiants</a></li>
        <?php if (estAdmin()): ?>
            <li><a href="admin.php">Administration</a></li>
            <li><a href="utilisateur.php">Gestion des utilisateur </a></li>
        <?php endif; ?>
        <li><a href="deconnexion.php">Deconnecté</a></li>

    </ul>

</body>
</html>