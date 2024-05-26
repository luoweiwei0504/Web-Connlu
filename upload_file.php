<?php
include("connexion.php");

function ajouter_datas($file_path, $file_name, $utilisateur) {
    global $pdo;

    // Lire le contenu du fichier
    $content = file_get_contents($file_path);
    // Diviser le contenu en lignes
    $rows = explode("\n", $content);

    // Préparer la requête d'insertion SQL
    $insert_query = "INSERT INTO datas (Titre,ID,FORM,LEMMA,UPOS,XPOS,FEATS,HEAD,DEPREL,DEPS,MISC,Utilisateur) VALUES (:Titre,:ID,:FORM,:LEMMA,:UPOS,:XPOS,:FEATS,:HEAD,:DEPREL,:DEPS,:MISC,:Utilisateur)";
    $stmt = $pdo->prepare($insert_query);

    // Parcourir toutes les lignes
    foreach ($rows as $row) {
        // Vérifier si la ligne est valide, sinon passer à la suivante
        if (strpos($row, "\t") === false || count(explode("\t", $row)) !== 10) {
            // Ignorer les lignes qui ne respectent pas les critères
            continue;
        }
        
        // Extraire les champs de la ligne
        $fields = explode("\t", $row);

        $field1 = $fields[0];
        $field2 = $fields[1];
        $field3 = $fields[2];
        $field4 = $fields[3];
        $field5 = $fields[4];
        $field6 = $fields[5];
        $field7 = $fields[6];
        $field8 = $fields[7];
        $field9 = $fields[8];
        $field10 = $fields[9];        
        
        // Associer les paramètres de la requête aux valeurs extraites
        $stmt->bindParam(':Titre', $file_name);
        $stmt->bindParam(':ID', $field1);
        $stmt->bindParam(':FORM', $field2);
        $stmt->bindParam(':LEMMA', $field3);
        $stmt->bindParam(':UPOS', $field4);
        $stmt->bindParam(':XPOS', $field5);
        $stmt->bindParam(':FEATS', $field6);
        $stmt->bindParam(':HEAD', $field7);
        $stmt->bindParam(':DEPREL', $field8);
        $stmt->bindParam(':DEPS', $field9);
        $stmt->bindParam(':MISC', $field10);
        $stmt->bindParam(':Utilisateur', $utilisateur);

        // Exécuter la requête et vérifier si elle réussit
        if (!$stmt->execute()) {
            echo "Échec de l'insertion des données";
            exit;
        }
    }
}



    $Utilisateur = $_POST['Utilisateur'];
    $file_name = $_FILES['file']['name'];

    // Vérifier si le fichier existe déjà
    $query = "SELECT * FROM Information WHERE Utilisateur = :Utilisateur AND Titre = :Titre";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Utilisateur', $Utilisateur);
    $stmt->bindParam(':Titre', $file_name);
    $stmt->execute();
    $result = $stmt->fetchAll();

    if (count($result) > 0) {
    // Le fichier existe déjà, retourner un message d'erreur
    echo "Le fichier existe déjà.";
    http_response_code(400); // Retourner un code d'erreur 400
    } else {
    // Le fichier n'existe pas, poursuivre avec la logique d'importation
    if ($_FILES["file"]["error"] == UPLOAD_ERR_OK) {
    $tmp_name = $_FILES["file"]["tmp_name"];
    // Vérifier si l'extension du fichier est .txt
    $file_extension = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
    if ($file_extension === 'txt' || $file_extension === 'conllu') {
    // Appeler la fonction ajouter_datas pour traiter le fichier
    ajouter_datas($tmp_name, $file_name, $Utilisateur);
    // Le traitement du fichier est réussi
    echo "succès";
    } else {
    // Échec de l'importation du fichier
    echo "échec";
    }
    } else {
    // Échec de l'importation du fichier
    echo "échec";
    }
    }
?>
