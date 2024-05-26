<?php
include("connexion.php");

// obtenir les paramètre de username et password
$username = isset($_POST['username']) ? $_POST['username'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';

// consulter l'utilisateur correspond avec le nom et mdp obtenus dans la base de données
$query = "SELECT nom FROM Login WHERE nom = :username AND mdp = :password";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':username', $username);
$stmt->bindParam(':password', $password);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user) {
    // si on trouve l'utilisateur correspond, ajoute à success de true et revenir à true
    $response = array(
        'success' => true,
        'username' => $user['nom']
    );
} else {
    // si l'utilisateur n'existe pas, ajoute à success de false
    $response = array(
        'success' => false
    );
}

// revenir par l'action de JSON
header('Content-Type: application/json');
echo json_encode($response);
?>
