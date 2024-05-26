
<?php
include("connexion.php");

function update_information($pdo, $titre, $utilisateur)
{
    // Rechercher toutes les données correspondant au titre et à l'utilisateur donnés dans la table datas
    $query = "SELECT * FROM datas WHERE Titre = :Titre AND Utilisateur = :Utilisateur";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':Titre', $titre);
    $stmt->bindParam(':Utilisateur', $utilisateur);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Initialiser un tableau pour stocker si chaque information Conll est vide ou non
    $conll_info = [
        'ID' => 0,
        'FORM' => 0,
        'LEMMA' => 0,
        'UPOS' => 0,
        'XPOS' => 0,
        'FEATS' => 0,
        'HEAD' => 0,
        'DEPREL' => 0,
        'DEPS' => 0,
        'MISC' => 0
    ];

    // Parcourir les résultats de la requête pour vérifier si chaque information Conll est vide ou non
    foreach ($result as $row) {
        foreach ($conll_info as $key => $value) {
            if ($row[$key] != '_') {
                $conll_info[$key] = 1;
            }
        }
    }

    // Mettre à jour la table Information
    $update_query = "UPDATE Information SET ID = :ID, FORM = :FORM, LEMMA = :LEMMA, UPOS = :UPOS, XPOS = :XPOS, FEATS = :FEATS, HEAD = :HEAD, DEPREL = :DEPREL, DEPS = :DEPS, MISC = :MISC WHERE Titre = :Titre AND Utilisateur = :Utilisateur";
    $stmt = $pdo->prepare($update_query);

    // Lier les paramètres
    foreach ($conll_info as $key => $value) {
        $stmt->bindParam(':' . $key, $conll_info[$key]);
    }
    $stmt->bindParam(':Titre', $titre);
    $stmt->bindParam(':Utilisateur', $utilisateur);

    // Exécuter l'opération de mise à jour
    if (!$stmt->execute()) {
        echo json_encode([
            'status' => 'failed',
            'message' => 'Échec de la mise à jour des données',
            'debugInfo' => 'Avant l\'exécution de l\'opération de mise à jour'
        ]);
        exit;
    }
}

// Vérifier si les données requises sont présentes dans le formulaire ($_POST)
if (isset($_POST['Titre']) && isset($_POST['Annee']) && isset($_POST['Langage']) && isset($_POST['Utilisateur'])) {
    // Récupérer les données du formulaire
    $titre = $_POST['Titre'];
    $annee = $_POST['Annee'];
    $langage = $_POST['Langage'];
    $utilisateur = $_POST['Utilisateur'];

    // Préparer la requête d'insertion des données dans la table Information
    $insert_query = "INSERT INTO Information (Titre, Annee, Langage, Utilisateur) VALUES (:Titre, :Annee, :Langage, :Utilisateur)";
    $stmt = $pdo->prepare($insert_query);

    // Lier les paramètres de la requête aux valeurs récupérées
    $stmt->bindParam(':Titre', $titre);
    $stmt->bindParam(':Annee', $annee);
    $stmt->bindParam(':Langage', $langage);
    $stmt->bindParam(':Utilisateur', $utilisateur);

    // Exécuter la requête d'insertion
    if (!$stmt->execute()) {
        // Si l'exécution échoue, afficher un message d'erreur et les informations de débogage
        $error = $stmt->errorInfo();
        echo json_encode([
            'status' => 'failed',
            'message' => 'Échec de l\'insertion des données',
            'debugInfo' => 'Avant l\'exécution de l\'opération d\'insertion',
            'error' => $error[2]
        ]);
        exit;
    } else {
        // Si l'exécution réussit, mettre à jour les informations et afficher un message de succès
        update_information($pdo, $titre, $utilisateur);
        echo json_encode([
            'status' => 'success',
            'message' => 'Insertion des données réussie',
            'debugInfo' => 'Insertion des données réussie'
        ]);
    }
    } else {
    // Si les données requises sont manquantes, afficher un message d'erreur
    echo json_encode([
    'status' => 'failed',
    'message' => 'Données requises manquantes',
    'debugInfo' => 'Données requises manquantes'
    ]);
}
?>