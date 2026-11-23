<?php
require_once __DIR__ . '/connexion_bdd.php';
require_once __DIR__ . '/auth.php';
ProtegerPage();

$message = "";
$erreur = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $age = filter_input(INPUT_POST, 'age', FILTER_VALIDATE_INT);
    $formation = trim($_POST['formation'] ?? '');
    $moyenne = filter_input(INPUT_POST, 'moyenne', FILTER_VALIDATE_FLOAT);

    
    if (empty($nom) || empty($prenom) || empty($formation)) {
        $erreur = "Tous les champs textuels sont obligatoires.";
    } elseif ($age === false || $age < 16 || $age > 100) {
        $erreur = "L'âge doit être un nombre entier valide compris entre 16 et 100.";
    } elseif ($moyenne === false || $moyenne < 0 || $moyenne > 20) {
        $erreur = "La moyenne doit être un nombre compris entre 0 et 20.";
    } else {
        try {
            $sql = "INSERT INTO etudiants (nom, prenom, age, formation, moyenne) 
                    VALUES (:nom, :prenom, :age, :formation, :moyenne)";
            
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'age' => $age,
                'formation' => $formation,
                'moyenne' => $moyenne
            ]);

            $message = "L'étudiant a été ajouté avec succès !";
        } catch (PDOException $e) {
            $erreur = "Erreur lors de l'insertion dans la base de données.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un étudiant</title>
</head>
<body>
    <h1>Ajouter un nouvel étudiant</h1>

    <?php if (!empty($erreur)): ?>
        <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <p style="color: green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="nom">Nom : </label><br>
        <input id="nom" type="text" name="nom" required><br><br>

        <label for="prenom">Prénom : </label><br>
        <input id="prenom" type="text" name="prenom" required><br><br>

        <label for="age">Âge : </label><br>
        <input id="age" type="number" name="age" min="16" max="100" required><br><br>

        <label for="formation">Formation : </label><br>
        <input id="formation" type="text" name="formation" required><br><br>

        <label for="moyenne">Moyenne : </label><br>
        <input id="moyenne" type="number" name="moyenne" min="0" max="20" step="0.01" required><br><br>

        <button type="submit">Ajouter</button>
    </form>
    
    <br>
    <a href="etudiants.php">Retour à la liste des étudiants</a>
</body>
</html>