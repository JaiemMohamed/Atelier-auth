<?php 
require_once __DIR__ . "/auth.php";
protgerAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adminstration</title>
</head>
<body>
    <h1>Espace admin </h1>
    <p>Cette page est reservé pour les admin </p>
    <ul>
        <li><a href="utilisateur.php">Geré les utilisateurs </a></li>
        <li><a href="etudiants.php">Geré les etudiants</a></li>
    </ul>
    <p><a href="dashboard.php">Retour au tableau de board </a></p>
    
</body>
</html>