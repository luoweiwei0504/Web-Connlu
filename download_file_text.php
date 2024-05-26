<?php
include("connexion.php");

// Vérifier que les paramètres POST requis sont présents
if (isset($_POST['file_name']) && isset($_POST['user_name'])) {
    // Récupérer les paramètres POST
    $file_name = $_POST['file_name'];
    $user_name = $_POST['user_name'];

    // Préparer et exécuter une requête SQL pour récupérer les FORM de chaque ligne de datas pour un utilisateur et un fichier donnés
    $query = "SELECT FORM FROM datas WHERE Titre = :Titre AND Utilisateur = :Utilisateur ORDER BY ID";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Titre', $file_name);
    $stmt->bindParam(':Utilisateur', $user_name);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Retourner les résultats en JSON
    echo json_encode($result);
} else {
    // Si des paramètres sont manquants, retourner un code d'erreur HTTP 400 et un message d'erreur
    http_response_code(400);
    echo "Missing file_name or user_name";
}
?>