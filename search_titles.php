<?php
    include("connexion.php");

    // Récupérer l'option de recherche et la valeur de recherche envoyées via POST
    $searchOption = $_POST['searchOption'];
    $searchValue = $_POST['searchValue'];
    $Utilisateur = $_POST['Utilisateur'];

    // Préparer la requête SQL pour rechercher les fichiers correspondants
    $query = "SELECT DISTINCT Titre FROM datas WHERE $searchOption LIKE :searchValue AND Utilisateur = :Utilisateur";

    // Exécuter la requête SQL avec les valeurs de paramètres
    $stmt = $pdo->prepare($query);
    $searchValue = "%$searchValue%";
    $stmt->bindParam(':searchValue', $searchValue);
    $stmt->bindParam(':Utilisateur', $Utilisateur);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Extraire les titres des fichiers de la réponse
    $titles = array_map(function($row) {
        return $row['Titre'];
    }, $result);

    // Créer la réponse avec les informations de la requête SQL et les titres des fichiers correspondants
    $response = [
        'query' => $query,
        'searchValue' => $searchValue,
        'Utilisateur' => $Utilisateur,
        'titles' => $titles
    ];

    // Envoyer la réponse en format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
?>