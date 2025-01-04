<?php
include('db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM membres WHERE id = '$id'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $member = mysqli_fetch_assoc($result);
    } else {
        echo "Membre non trouvé.";
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $role = $_POST['role'];

    $updateQuery = "UPDATE membres SET nom = '$nom', prenom = '$prenom', email = '$email', telephone = '$telephone', role = '$role' WHERE id = '$id'";
    
    if (mysqli_query($conn, $updateQuery)) {
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
    <title>Modifier Membre</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h1>Modifier Membre</h1>

    <form method="POST">
        <label for="nom">Nom:</label>
        <input type="text" name="nom" value="<?= $member['nom']; ?>" required>

        <label for="prenom">Prénom:</label>
        <input type="text" name="prenom" value="<?= $member['prenom']; ?>" required>

        <label for="email">Email:</label>
        <input type="email" name="email" value="<?= $member['email']; ?>" required>

        <label for="telephone">Numéro de téléphone:</label>
        <input type="text" name="telephone" value="<?= $member['telephone']; ?>" required>

        <label for="role">Rôle:</label>
        <select name="role" required>
            <option value="member" <?= $member['role'] == 'member' ? 'selected' : 'member'; ?>>Membre</option>
            <option value="president" <?= $member['role'] == 'president' ? 'selected' : 'member'; ?>>Président</option>
            <option value="Vice-président" <?= $member['role'] == 'Vice-président' ? 'selected' : 'member'; ?>>Vice-président</option>
            <option value="trésorier" <?= $member['role'] == 'trésorier' ? 'selected' : 'member'; ?>>trésorier</option>
            <option value="RH" <?= $member['role'] == 'RH' ? 'selected' : 'member'; ?>>RH</option>
        </select>

        <input type="submit" value="Mettre à jour">
    </form>

    <br>
    <a href="home.php">Retour à la liste des membres</a>
</body>
</html>
