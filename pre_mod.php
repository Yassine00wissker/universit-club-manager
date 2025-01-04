<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = mysqli_real_escape_string($conn, $_POST['nom']);
    $prenom = mysqli_real_escape_string($conn, $_POST['prenom']);
    $telephone = mysqli_real_escape_string($conn, $_POST['telephone']);
    $query = "SELECT * FROM membres WHERE nom='$nom' AND prenom='$prenom' AND telephone='$telephone'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        header('Location: modify_activity.php');
        exit;
    } else {
        echo "Utilisateur introuvable. Veuillez vérifier vos informations.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Vérification - Modifier Activité</title>
</head>
<body>
    <h1>Vérification pour Modifier Activité</h1>
    <form method="POST">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" required>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" required>

        <label for="telephone">Numéro de téléphone:</label>
        <input type="text" name="telephone" required>

        <input type="submit" value="Vérifier">
    </form>

    <br>
    <a href="home.php">Retour à l'accueil</a>
</body>
</html>
