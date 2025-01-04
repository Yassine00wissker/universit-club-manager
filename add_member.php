<?php
include('db.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $role = $_POST['role'];

    $query = "INSERT INTO membres (nom, prenom, email, telephone, role) VALUES ('$nom', '$prenom', '$email', '$telephone', '$role')";
    if (mysqli_query($conn, $query)) {
        header('Location: home.php');
    } else {
        echo "Erreur: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Membre</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h1>Ajouter un Membre</h1>
    <form method="POST">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" required>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" required>

        <label for="email">Email:</label>
        <input type="email" name="email" required>

        <label for="telephone">Numéro de téléphone:</label>
        <input type="text" name="telephone" required>

        <label for="role">Rôle:</label>
        <select name="role">
            <option value="member">Membre</option>
            <option value="president">Président</option>
            <option value="Vice-président">Vice-président</option>
            <option value="trésorier ">trésorier</option>
            <option value="RH">RH</option>
        </select>

        <input type="submit" value="Ajouter">
        <a href="home.php">Home</a>
    </form>
</body>
</html>
