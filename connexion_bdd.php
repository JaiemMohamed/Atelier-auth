<?php

$host = getenv('DB_HOST') ?: 'localhost';
$dbname = getenv('DB_NAME') ?: 'gestion_etudiants';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : 'Mysjaiem2006';

$dsn= "mysql:host=$host;dbname=$dbname;charset=utf8mb4";

try{
    $pdo = new PDO($dsn, $username, $password);

    $pdo ->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    $pdo ->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_ASSOC);

    } catch(PDOException $e){
        exit("Erreur de connexion a la base de donnée");
    }
