<?php
require_once __DIR__ . '/connexion_bdd.php';
require_once __DIR__. '/auth.php';
ProtegerPage();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

    if ($id) {
        try {
 
            $sql = "DELETE FROM etudiants WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $id]);

            header('Location: etudiants.php');
            exit;
        } catch (PDOException $e) {
            exit("Erreur lors de la suppression de l'étudiant.");
        }
    } else {
        exit("Identifiant d'étudiant invalide.");
    }
} else {

    header('Location: etudiants.php');
    exit;
}