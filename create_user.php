<?php
include("connexion.php");

// obtenir les paramètre de nom et mdp
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// vérifier si l'utilisateur existe déjà ou pas
$query = "SELECT * FROM Login WHERE nom = :username";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    // si l'utilisateur existe déjà
    $response = array(
        'success' => false,
        'message' => 'Le nom d\'utilisateur existe déjà.',
    );
} else {
    // ajouter les donées de nouveau utilisateur à la base de donées
    $query = "INSERT INTO Login (nom, mdp) VALUES (:username, :password)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $password);
    $stmt->execute();

    $response = array(
        'success' => true,
    );
}

// revenir à l'action du code JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
