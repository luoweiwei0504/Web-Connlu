
<?php
include("connexion.php");

// Vérifie si le titre et l'utilisateur sont fournis
if (isset($_POST['Titre']) && isset($_POST['Utilisateur'])) {
    $titre = $_POST['Titre'];
    $utilisateur = $_POST['Utilisateur'];

    // Requête pour récupérer les informations de fichier
    $select_query = "SELECT Annee, Langage, ID, FORM, LEMMA, UPOS, XPOS, FEATS, HEAD, DEPREL, DEPS, MISC FROM Information WHERE Titre = :Titre AND Utilisateur = :Utilisateur";
    $stmt = $pdo->prepare($select_query);

    $stmt->bindParam(':Titre', $titre);
    $stmt->bindParam(':Utilisateur', $utilisateur);

    // Exécution de la requête
    if ($stmt->execute()) {
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data) {
            $conllInfo = [];
            for ($i = 2; $i < 12; $i++) {
                $conllInfo[] = (int)$data[array_keys($data)[$i]];
            }
            $result = [
                'Annee' => $data['Annee'],
                'Langage' => $data['Langage'],
                'ConllInfo' => $conllInfo
            ];
            echo json_encode($result);
        } else {
            echo json_encode(["error" => "Aucune information de fichier correspondante trouvée"]);
        }
    } else {
        echo json_encode(["error" => "Échec de la recherche d'informations de fichier"]);
    }
} else {
    echo json_encode(["error" => "Données manquantes"]);
}
?>