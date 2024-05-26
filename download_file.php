<?php
include("connexion.php");

// Vérifier si les paramètres file_name et user_name sont définis
if (isset($_POST['file_name']) && isset($_POST['user_name'])) {
    // Récupérer les valeurs des paramètres
    $file_name = $_POST['file_name'];
    $user_name = $_POST['user_name'];

    // Requête pour récupérer toutes les données de la table datas correspondant au titre et à l'utilisateur donnés
    $query = "SELECT * FROM datas WHERE Titre = :Titre AND Utilisateur = :Utilisateur ORDER BY ID";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Titre', $file_name);
    $stmt->bindParam(':Utilisateur', $user_name);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Afficher les résultats au format JSON
    echo json_encode($result);
} else {
    // Si les paramètres file_name ou user_name sont manquants, renvoyer un code d'erreur 400 et un message d'erreur
    http_response_code(400);
    echo "Missing file_name or user_name";
}
?>