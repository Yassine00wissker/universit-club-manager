<?php
include('db.php');
$query = "SELECT 
    e.id AS event_id,
    e.date AS event_date,
    a.description AS activity_description,
    a.lieu AS activity_location,
    CONCAT(m.nom, ' ', m.prenom) AS responsable_name
FROM 
    evenment e
JOIN 
    activites a ON e.act_id = a.id
LEFT JOIN 
    rep_act ra ON ra.act_id = a.id
LEFT JOIN 
    membres m ON ra.membre_id = m.id;
";

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
    <h1>Liste des Evenement</h1>

    <table>
        <tr>
            <th>Lieu</th>
            <th>Description</th>
            <th>date</th>
            <th>Membre Responsable</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['activity_location']; ?></td>
            <td><?=$row['activity_description']; ?></td>
            <td><?= $row['event_date']; ?></td>
            <td>
                <?= $row['responsable_name']; ?>
            </td>
            <td><a href="modify_event.php?id=<?= $row['event_id']; ?>">Modifier</a>
            <a href="delete_event.php?id=<?= $row['event_id']; ?>">Supprimer</a>
           
        </tr>
        <?php endwhile; ?>
    </table>

    <br>
    <a href="home.php">Retour à la liste des membres</a>
</body>
</html>
