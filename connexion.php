<?php 
session_start();
require_once __DIR__ . '/connexion_bdd.php';

$erreur = '';

if($_SERVER['REQUEST_METHOD']==='POST'){

    $username = trim($_POST['username']?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === ''){
        $erreur = "Veuillez remplir tous les champs";
    }else{
        $sql = "SELECT id, username,password_hash,role,actif
        FROM utilisateurs
        WHERE username = :username";

        $stmt = $pdo ->prepare($sql);
        $stmt ->execute(['username'=> $username]);

        $utilisateur = $stmt->fetch();

        if($utilisateur && (int) $utilisateur['actif']=== 1 && password_verify($password,$utilisateur["password_hash"])){
            session_regenerate_id(true);
            $_SESSION["connecte"]=true;
            $_SESSION["user_id"]=(int) $utilisateur["id"];
            $_SESSION['username']=$utilisateur['username'];
            $_SESSION['role']=$utilisateur['role'];

            header('Location: dashboard.php');
            exit;

        }else{
            $erreur = 'Identifiants incorrects';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connextion</title>
</head>
<body>
    <h1>Connexion</h1>

    <?php if ($erreur !==''):?>
        <p style="color:red;"><?php echo htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8'); ?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="username">Nom d'utilisateur : </label>  
        <input id="username" type="text" name="username" required>

        <label for="password">Password : </label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Se connecter</button>
    </form>
        <p><a href="utilisateur-creer.php">tu n'a pas du compte ? S'incrire </a></p>
</body>
</html>