<?php
require_once __DIR__ . '/connexion_bdd.php';
require_once __DIR__ . "/auth.php";
ProtegerPage();

$message = "";
$erreur = "";
$etudiant = null;


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    exit("Identifiant d'étudiant invalide ou manquant.");
}


$sqlSelect = "SELECT id, nom, prenom, age, formation, moyenne
              FROM etudiants
              WHERE id = :id";
$stmtSelect = $pdo->prepare($sqlSelect);
$stmtSelect->execute(['id' => $id]);
$etudiant = $stmtSelect->fetch();

if (!$etudiant) {
    exit("Aucun étudiant trouvé avec cet identifiant.");
}

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
            $sqlUpdate = "UPDATE etudiants
                          SET nom = :nom, prenom = :prenom, age = :age, formation = :formation, moyenne = :moyenne
                          WHERE id = :id";
            
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'age' => $age,
                'formation' => $formation,
                'moyenne' => $moyenne,
                'id' => $id
            ]);

            $message = "Les modifications ont été enregistrées avec succès !";


            $etudiant['nom'] = $nom;
            $etudiant['prenom'] = $prenom;
            $etudiant['age'] = $age;
            $etudiant['formation'] = $formation;
            $etudiant['moyenne'] = $moyenne;
        } catch (PDOException $e) {
            $erreur = "Erreur lors de la mise à jour de l'étudiant.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier un étudiant</title>
</head>
<body>
    <h1>Modifier les informations de l'étudiant</h1>

    <?php if (!empty($erreur)): ?>
        <p style="color: red;"><?= htmlspecialchars($erreur) ?></p>
    <?php endif; ?>

    <?php if (!empty($message)): ?>
        <p style="color: green;"><?= htmlspecialchars($message) ?></p>
    <?php endif; ?>


    <form method="POST">

        <input type="hidden" name="id" value="<?= (int) $etudiant['id'] ?>">

        <label for="nom">Nom : </label><br>
        <input id="nom" type="text" name="nom" value="<?= htmlspecialchars($etudiant['nom']) ?>" required><br><br>

        <label for="prenom">Prénom : </label><br>
        <input id="prenom" type="text" name="prenom" value="<?= htmlspecialchars($etudiant['prenom']) ?>" required><br><br>

        <label for="age">Âge : </label><br>
        <input id="age" type="number" name="age" min="16" max="100" value="<?= (int) $etudiant['age'] ?>" required><br><br>

        <label for="formation">Formation : </label><br>
        <input id="formation" type="text" name="formation" value="<?= htmlspecialchars($etudiant['formation']) ?>" required><br><br>

        <label for="moyenne">Moyenne : </label><br>
        <input id="moyenne" type="number" name="moyenne" min="0" max="20" step="0.01" value="<?= htmlspecialchars((string) $etudiant['moyenne']) ?>" required><br><br>

        <button type="submit">Enregistrer les modifications</button>
    </form>
    
    <br>
    <a href="etudiants.php">Retour à la liste des étudiants</a>
</body>
</html>