<?php
include('db.php');

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    $query = "SELECT * FROM membres WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    if (mysqli_num_rows($result) > 0) {
        $member = mysqli_fetch_assoc($result);
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $deleteQuery = "DELETE FROM membres WHERE id = '$id'";
            if (mysqli_query($conn, $deleteQuery)) {
                header('Location: home.php');
            } else {
                echo "Erreur: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Membre non trouvé.";
        exit;
    }
} else {
    echo "ID de membre non spécifié.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer un Membre</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h1>Supprimer un Membre</h1>

    <p>Êtes-vous sûr de vouloir supprimer le membre suivant ?</p>

    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
        </tr>
        <tr>
            <td><?= $member['nom']; ?></td>
            <td><?= $member['prenom']; ?></td>
            <td><?= $member['email']; ?></td>
            <td><?= $member['role']; ?></td>
        </tr>
    </table>

    <form method="POST">
        <input type="submit" value="Supprimer">
        <a href="home.php">Annuler</a>
    </form>
</body>
</html>
