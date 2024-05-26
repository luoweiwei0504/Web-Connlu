<?php
    include("connexion.php");

    $Utilisateur = $_POST['Utilisateur'];

    $query = "SELECT Langage, Titre FROM Information WHERE Utilisateur = :Utilisateur ORDER BY Langage";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Utilisateur', $Utilisateur);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $filesByYear = [];
    foreach ($result as $row) {
        $year = $row['Langage'];
        $title = $row['Titre'];

        if (!isset($filesByYear[$year])) {
            $filesByYear[$year] = [];
        }

        $filesByYear[$year][] = $title;
    }

    $output = [];
    foreach ($filesByYear as $year => $files) {
        $output[] = [
            'year' => $year,
            'files' => $files
        ];
    }

    header('Content-Type: application/json');
    echo json_encode($output);
?>
