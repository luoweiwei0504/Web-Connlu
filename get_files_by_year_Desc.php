<?php
    // inclure le code de connexion à la base de données
    include("connexion.php");

    // récupérer le nom d'utilisateur
    $Utilisateur = $_POST['Utilisateur'];

    // préparer la requête SQL pour récupérer les fichiers de l'utilisateur triés par année
    $query = "SELECT Annee, Titre FROM Information WHERE Utilisateur = :Utilisateur ORDER BY Annee DESC";

    // exécuter la requête SQL
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Utilisateur', $Utilisateur);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // regrouper les fichiers par année
    $filesByYear = [];
    foreach ($result as $row) {
        $year = $row['Annee'];
        $title = $row['Titre'];

        if (!isset($filesByYear[$year])) {
            $filesByYear[$year] = [];
        }

        $filesByYear[$year][] = $title;
    }

    // préparer la réponse JSON
    $output = [];
    foreach ($filesByYear as $year => $files) {
        $output[] = [
            'year' => $year,
            'files' => $files
        ];
    }

    // envoyer la réponse JSON
    header('Content-Type: application/json');
    echo json_encode($output);
?>
