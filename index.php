<?php

require_once 'config/database.php';

// Connection a la base de données
require_once 'config/database.php';

$db = Database::getConnection();

// Confirmation de la connexion
if ($db) {
    echo "Connection à la base de données réussie!";
} else {
    echo "Echec de la connexion à la base de données.";
}
