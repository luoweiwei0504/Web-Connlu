<?php
// inclure le code de connexion à la base de données
include("connexion.php");

// Vérifier si le nom d'utilisateur est renseigné
if (isset($_POST['Utilisateur'])) {
    $utilisateur = $_POST['Utilisateur'];

    // Définir les colonnes à rechercher
    $conllColumns = ['ID', 'FORM', 'LEMMA', 'UPOS', 'XPOS', 'FEATS', 'HEAD', 'DEPREL', 'DEPS', 'MISC'];

    $results = [];

    // Parcourir toutes les colonnes
    foreach ($conllColumns as $column) {
        // Préparer la requête SQL pour récupérer les fichiers correspondant à chaque colonne
        $query = "SELECT Titre FROM Information WHERE Utilisateur = :Utilisateur AND $column = 1";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':Utilisateur', $utilisateur);
        $stmt->execute();
        $files = $stmt->fetchAll(PDO::FETCH_COLUMN);
        $results[] = ['conll' => $column, 'files' => $files];
    }

    // Retourner les résultats au format JSON
    echo json_encode($results);
} else {
    // Retourner une réponse d'erreur si le nom d'utilisateur est manquant
    http_response_code(400);
    echo "Données manquantes";
}
?>