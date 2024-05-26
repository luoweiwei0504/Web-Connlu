<?php
include("connexion.php");

if (isset($_GET['file_name'])) {
    $file_name = $_GET['file_name'];
} else {
    echo "Veuillez fournir le nom de fichier";
    exit;
}

$query = "SELECT ID, FORM, LEMMA, UPOS, XPOS, FEATS, HEAD, DEPREL, DEPS, MISC FROM datas WHERE Titre = :file_name";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':file_name', $file_name);
$stmt->execute();
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);

header('Content-Type: application/json');
echo json_encode($data);
?>