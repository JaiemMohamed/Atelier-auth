<?php
require_once __DIR__ . "/auth.php";

if (estconnecte()) {
    header("Location: dashboard.php");
} else {
    header("Location: connexion.php");
}
exit;