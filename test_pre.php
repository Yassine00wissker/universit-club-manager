<?php
include('db.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $telephone = $_POST['telephone'];

    $query = "SELECT * FROM membres WHERE nom=? AND prenom=? AND telephone=?";
    $stmt = mysqli_prepare($conn, $query);

    mysqli_stmt_bind_param($stmt, "sss", $nom, $prenom, $telephone);

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        header('Location: add_activity.php');
        exit;
    } else {
        echo "Utilisateur introuvable. Veuillez vérifier vos informations.";
    }

    mysqli_stmt_close($stmt);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="index.css">
    <title>Vérification</title>
</head>
<body>
    <h1>Vérification</h1>
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
    <a href="home.php">Accueil</a>
</body>
</html>
