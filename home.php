<?php
include('db.php');
$query = "SELECT * FROM membres"; 
if (isset($_GET['role'])) {
    $role = $_GET['role'];
    if ($role != "") {
        $query .= " WHERE role = '$role'";
    }
}
$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Membres</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <nav>
        <div class="logo">Gestion des Membres</div>
        <ul class="nav-links">
            <li><a href="add_member.php">Ajouter un Membre</a></li>
            <li><a href="test_pre.php">Ajouter une Activité</a></li>
            <li><a href="show_activities.php">Afficher Activité</a></li>
            <li><a href="add_event.php">Ajouter Evenement</a></li>
            <li><a href="show_event.php">Afficher Evenement</a></li>
        </ul>
    </nav>
    <h2><center>Membres<center></h2>
    <form method="GET">
        <label for="role">Filtrer par rôle:</label>
        <select name="role" id="role">
            <option value="">Tous</option>
            <option value="member">Membre</option>
            <option value="president">Président</option>
            <option value="Vice-président">Vice-président</option>
            <option value="trésorier ">Trésorier</option>
            <option value="RH">RH</option>
        </select>
        <input type="submit" value="Filtrer">
    </form>

    <table>
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Role</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>
            <td><?= $row['nom']; ?></td>
            <td><?= $row['prenom']; ?></td>
            <td><?= $row['email']; ?></td>
            <td><?= $row['role']; ?></td>
            <td>
                <a href="modify_member.php?id=<?= $row['id']; ?>">Modifier</a>
                <a href="delete_member.php?id=<?= $row['id']; ?>">Supprimer</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <a href="download_pdf.php">Télécharger fichier en PDF</a>
</body>
</html>
