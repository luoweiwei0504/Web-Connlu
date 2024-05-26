<?php
    // Inclure le fichier de connexion à la base de données
    include("connexion.php");

    // Récupérer le nom d'utilisateur
    $Utilisateur = $_POST['Utilisateur'];

    // Préparer la requête SQL pour récupérer les informations des fichiers de l'utilisateur triées par année
    $query = "SELECT Annee, Titre FROM Information WHERE Utilisateur = :Utilisateur ORDER BY Annee";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Utilisateur', $Utilisateur);

    // Exécuter la requête SQL
    if ($stmt->execute()) {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Regrouper les fichiers par année dans un tableau
        $filesByYear = [];
        foreach ($result as $row) {
            $year = $row['Annee'];
            $title = $row['Titre'];

            if (!isset($filesByYear[$year])) {
                $filesByYear[$year] = [];
            }

            $filesByYear[$year][] = $title;
        }

        // Construire le tableau de sortie pour le JSON
        $output = [];
        foreach ($filesByYear as $year => $files) {
            $output[] = [
                'year' => $year,
                'files' => $files
            ];
        }

        // Envoyer la réponse JSON
        header('Content-Type: application/json');
        echo json_encode($output);
    } else {
        // Envoyer un message d'erreur en cas de problème avec la requête SQL
        http_response_code(500);
        echo "Erreur lors de la récupération des informations des fichiers";
    }
?>
