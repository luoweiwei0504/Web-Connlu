<?php
    include("connexion.php");

    // obtenir les paramètre de nom et mdp
    $username = isset($_POST['username']) ? $_POST['username'] : '';
    $new_password = isset($_POST['new_password']) ? $_POST['new_password'] : '';

    // vérifier si l'utilisateur existe déjà
    $check_query = "SELECT * FROM Login WHERE nom = :username";
    $check_stmt = $pdo->prepare($check_query);
    $check_stmt->bindParam(':username', $username);
    $check_stmt->execute();

    if ($check_stmt->rowCount() > 0) {
        // si l'utilisateur existe, mettre à jour le mdp
        $update_query = "UPDATE Login SET mdp = :new_password WHERE nom = :username";
        $update_stmt = $pdo->prepare($update_query);
        $update_stmt->bindParam(':username', $username);
        $update_stmt->bindParam(':new_password', $new_password);
        $update_stmt->execute();

        // mettre à jour avec succès
        echo json_encode([
            'success' => true,
            'message' => 'Mot de passe modifié avec succès.',
        ]);
    } else {
        // si l'utilisateur existe pas, fait une alerte
        echo json_encode([
            'success' => false,
            'message' => "Nom d'utilisateur inexistant.",
        ]);
    }
?>