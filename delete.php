<?php
    include("connexion.php");

// Obtenir le nom du fichier et l'utilisateur
    $fileNames = json_decode($_POST['files'], true);
    $Utilisateur = $_POST['Utilisateur'];

    
// Préparation de la suppression de l'enregistrement dans la table "datas".
    $query1 = "DELETE FROM datas WHERE Titre = :fileName AND Utilisateur = :Utilisateur";

    // Préparation de la suppression de l'enregistrement dans la table "Information".
    $query2 = "DELETE FROM Information WHERE Titre = :fileName AND Utilisateur = :Utilisateur";

    // Parcourir le tableau de noms de fichiers et supprimer les enregistrements correspondants.
    foreach ($fileNames as $fileName) {
        // Suppression de l'enregistrement dans la table "datas".
        $stmt1 = $pdo->prepare($query1);
        $stmt1->bindParam(':fileName', $fileName);
        $stmt1->bindParam(':Utilisateur', $Utilisateur);
        $stmt1->execute();

        // Suppression de l'enregistrement dans la table "Information".
        $stmt2 = $pdo->prepare($query2);
        $stmt2->bindParam(':fileName', $fileName);
        $stmt2->bindParam(':Utilisateur', $Utilisateur);
        $stmt2->execute();
    }

    echo "success";
?>