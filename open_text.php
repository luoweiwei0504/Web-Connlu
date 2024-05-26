<?php
include("connexion.php");

// Vérifie si le paramètre 'file_name' a été fourni
if (isset($_GET['file_name'])) {
    $file_name = $_GET['file_name'];
} else {
    echo "Nom de fichier non spécifié";
    exit;
}

// Prépare la requête SQL pour récupérer les FORMs du fichier
$query = "SELECT FORM FROM datas WHERE Titre = :file_name";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':file_name', $file_name);

// Exécute la requête SQL
if ($stmt->execute()) {
    $lemmas = [];
    while ($row = $stmt->fetch()) {
        $lemmas[] = $row['FORM'];
    }
    // Concatène les FORMs en une chaîne de caractères séparés par un espace
    echo implode(" ", $lemmas);
} else {
    echo "Échec de la requête";
}
?>