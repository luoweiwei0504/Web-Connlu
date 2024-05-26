<?php
    include("connexion.php");

    // Obtenir la variable Utilisateur à partir des paramètres de la requête
    $Utilisateur = isset($_GET['Utilisateur']) ? $_GET['Utilisateur'] : '';

    // Interroger la base de données pour connaître tous les titres associés à un utilisateur donné
    $query = "SELECT Titre FROM Information WHERE Utilisateur = :Utilisateur";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Utilisateur', $Utilisateur);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Convertir le résultat en un tableau de noms de fichiers
    $files = array_map(function($row) {
        return $row['Titre'];
    }, $result);

    // Retourne un tableau de noms de fichiers encodés en JSON
    header('Content-Type: application/json');
    echo json_encode($files);
?>
