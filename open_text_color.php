<?php
include("connexion.php");

if (isset($_GET['file_name'])) {
    $file_name = $_GET['file_name'];

    // Préparer la requête SQL pour sélectionner les colonnes FORM et UPOS
    $query = "SELECT FORM, UPOS FROM datas WHERE Titre = :file_name";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':file_name', $file_name);

    // Exécuter la requête SQL et récupérer les résultats
    if ($stmt->execute()) {
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($results);
    } else {
        // Afficher un message d'erreur si la requête SQL a échoué
        echo "Échec de la récupération des données.";
    }
} else {
    // Afficher un message d'erreur si le paramètre file_name n'a pas été fourni
    echo "Erreur de paramètre.";
}
?>