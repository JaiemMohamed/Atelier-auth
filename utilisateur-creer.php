<?php
require_once __DIR__ . "/auth.php";
require_once __DIR__ . "/connexion_bdd.php";

$erreur = [];
$succes ="";
$username="";

if ($_SERVER["REQUEST_METHOD"]==="POST"){
    $username = trim($_POST["username"]?? '');
    $password = $_POST['password']?? '';
    $confirmation  = $_POST['confirmation']?? '';

    if ($username === ''){
        $erreur[]="Le nom est obligatoire";
    }elseif(strlen($username)<3){
        $erreur[]="le nom doit contenir ou moins 3 caractére ";
    }

    if(strlen($password)<8){
        $erreur[]="le mot de passe doit contenir ou moins 8 caractére";
    }elseif($password !== $confirmation){
        $erreur[]="Les deux mot de passe ne correspent pas";
    }

    if(count($erreur)===0){
        $sql= "SELECT COUNT(*) FROM utilisateurs WHERE username = :username";
        $stmt = $pdo -> prepare($sql);
        $stmt -> execute(['username'=> $username]);

        if ((int) $stmt-> fetchColumn()>0){
            $erreur[]="Ce nom de utilisateur exist deja ";
        }else{
            $hash = password_hash($password,PASSWORD_DEFAULT);
            $sql = "INSERT INTO utilisateurs(username,password_hash,role)
                    VALUES(:username,:hash,'user')";
            $stmt = $pdo->prepare($sql);
            $stmt ->execute([
                'username'=>$username,
                'hash'=> $hash
            ]);

            $succes = "compte Créé vous pouver vous connecté";
            $username ="";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>
<body>
    <h1>Crée un compte</h1>
    <?php foreach ($erreur as $e):?>
        <p style="color: red;"><?php echo htmlspecialchars($e)?></p>
    <?php endforeach; ?>
    <?php if ($succes !== ""):?>
        <p style="color: green;"><?php echo htmlspecialchars($succes)?></p>
    <?php endif; ?>

    <form method="POST">
        <label for="username">Username :</label>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars("$username") ?>" required>

        <label for="password">Password : </label>
        <input type="password" id="password" name="password"  required>

        <label for="confirmation">Confirmation: </label>
        <input type="password" name="confirmation" id="confirmation" required>

        <button type="submit">s'incrire</button>
    </form>

    <p><a href="connexion.php"> deja un compte ? se connecter</a></p>

</body>
</html>