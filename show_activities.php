<?php
include('db.php');

$query = "SELECT a.id, a.description, a.lieu, membres.nom, membres.prenom
          FROM activites a
          JOIN rep_act ON a.id = rep_act.act_id
          JOIN membres ON rep_act.membre_id = membres.id";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Afficher les Activités</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h1>Liste des Activités</h1>

    <table>
        <tr>
            <th>Lieu</th>
            <th>Description</th>
            <th>Membre Responsable</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['lieu']; ?></td>
            <td><?=$row['description']; ?></td>
            <td>
                <?= $row['nom'] . " " . $row['prenom']; ?>
            </td>
            <td>
                <a href="modify_activity.php?id=<?= $row['id']; ?>">Modifier</a>
                <a href="delete_activity.php?id=<?= $row['id']; ?>">Supprimer</a>

            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="home.php">Retour à la liste des membres</a>
</body>
</html>
