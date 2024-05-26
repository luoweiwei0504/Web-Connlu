
<?php
// Inclure le fichier de connexion à la base de données
include("connexion.php");

// Vérifier si le nom de fichier est fourni dans la requête GET
if (isset($_GET['file_name'])) {
    // Récupérer le nom de fichier
    $file_name = $_GET['file_name'];

    // Préparer la requête SQL pour récupérer les informations sur les données
    $query = "SELECT ID, FORM, HEAD, DEPREL FROM datas WHERE Titre = :file_name";

    // Exécuter la requête SQL
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':file_name', $file_name);
    $stmt->execute();

    // Récupérer les données et les renvoyer en format JSON
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($data);
} else {
    // Renvoyer un message d'erreur si le nom de fichier n'est pas fourni
    echo "Paramètre de nom de fichier manquant";
}
?>