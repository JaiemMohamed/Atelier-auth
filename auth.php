<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}
function estconnecte(): bool
{
    return isset($_SESSION['connecte']) && $_SESSION['connecte'] === true;
}

function utilisateurcourant(): ?array{
    if (!estconnecte()){
        return null;
    }
    return [
        'id' => $_SESSION['user_id' ]?? null,
        'username'=> $_SESSION['username'] ?? '',
        'role'=> $_SESSION['role'] ?? 'user'
    ];
}
function estAdmin(): bool{
    return estconnecte() && ($_SESSION['role'] ?? '') === "admin";
}

function ProtegerPage(): void {
    if (!estconnecte()){
        header('Location: connexion.php');
        exit;
    }
}

function protgerAdmin(): void{
    if (!estAdmin()){
        header("Location: dashboard.php");
        exit;
    }
}