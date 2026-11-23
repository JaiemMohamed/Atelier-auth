<?php 
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/connexion_bdd.php";
protgerAdmin();

$id= filter_input(INPUT_GET,'id',FILTER_VALIDATE_INT);

if($id === false || $id === null){
    exit("identifiant invalide");
}

$sql = "SELECT id,username,role FROM utilisateurs WHERE id = :id";
$stmt = $pdo -> prepare($sql);
$stmt -> execute(['id'=> $id]);
$utilisateur = $stmt -> fetch();

if (!$utilisateur){
    exit("Utilisateur introuvable");
}

$erreurs = "";
$succes = "";

if ($_SERVER['REQUEST_METHOD']==="POST"){
    $nouveaurole = $_POST["role"]??'';
    if(!in_array($nouveaurole,["admin","user"],true)){
        $erreurs = "Rôle invalide";
    }elseif((int)$utilisateur['id']=== (int) $_SESSION['user_id']&& $nouveaurole !== 'admin'){
        $erreurs = "Vous ne pouver pas retire votre role d'administrateur";
    }else{
        if($utilisateur['role']==='admin'&& $nouveaurole === "user"){
            $sql="SELECT COUNT(*) FROM utilisateurs
                WHERE role = 'admin' AND actif = 1";
            $nb= (int) $pdo-> query($sql)->fetchColumn();
            if ($nb <= 1){
                $erreurs = "impossible: il doit rester au moins un administrateur actif";
            }
        }
        if($erreurs === ""){
            $sql = "UPDATE utilisateurs SET role = :role WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt -> execute(['role'=> $nouveaurole,'id'=>$utilisateur['id']]);
            $utilisateur['role']= $nouveaurole;
            $succes= 'Role mise à jour ';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier le role </title>
</head>
<body>
    <h1>Modifier le role de l'utilisateur <?php echo htmlspecialchars($utilisateur['username'])?></h1>
    <p><a href="utilisateur.php">Retour ou liste de utilisateur </a></p>

    <?php if ($erreurs !== ''):?>
        <p style="color:red;"><?php echo htmlspecialchars($erreurs)?></p>
    <?php endif;?>
    <?php if ($succes !== ""):?>
        <p style="color:green;"><?php echo htmlspecialchars($succes)?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="role">Nouveau Role:</label>
        <select name="role" id="role">
            <option value="user" <?= $utilisateur['role'] === 'user' ? 'selected': " "?>> user </option>
            <option value= "admin"<?= $utilisateur['role'] === 'admin' ? 'selected': ''?>> admin </option>
        </select>
        <button type="submit">Engister</button>
    </form>

    
</body>
</html>