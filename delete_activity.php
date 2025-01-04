<?php
include('db.php');

if (isset($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query = "SELECT * FROM activites WHERE id = '$id'";
    $result = mysqli_query($conn, $query);
    
    if (mysqli_num_rows($result) > 0) {
        $activity = mysqli_fetch_assoc($result);
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['confirm'])) {
            $deleteRepActQuery = "DELETE FROM rep_act WHERE act_id = '$id'";
            mysqli_query($conn, $deleteRepActQuery);

            $deleteQuery = "DELETE FROM activites WHERE id = '$id'";
            if (mysqli_query($conn, $deleteQuery)) {
                header('Location: home.php');
                exit();
            } else {
                echo "Erreur: " . mysqli_error($conn);
            }
        }
    } else {
        echo "Activité non trouvée.";
        exit;
    }
} else {
    echo "ID d'activité non spécifié.";
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Supprimer Activité</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h1>Supprimer Activité</h1>
    <p>Êtes-vous sûr de vouloir supprimer l'activité suivante ?</p>
    <table>
        <tr>
            <th>Lieu</th>
            <th>Description</th>
            <th>Membre Responsable</th>
        </tr>
        <tr>
            <td><?= htmlspecialchars($activity['lieu']); ?></td>
            <td><?= htmlspecialchars($activity['description']); ?></td>
            <td>
                <?php
                $membre_query = "SELECT nom, prenom FROM membres m 
                                 JOIN rep_act ra ON m.id = ra.membre_id 
                                 WHERE ra.act_id = '$id'";
                $membre_result = mysqli_query($conn, $membre_query);
                if ($membre = mysqli_fetch_assoc($membre_result)) {
                    echo htmlspecialchars($membre['nom'] . " " . $membre['prenom']);
                } else {
                    echo "Aucun membre";
                }
                ?>
            </td>
        </tr>
    </table>
    <form method="POST">
        <input type="submit" name="confirm" value="Supprimer">
        <a href="home.php">Annuler</a>
    </form>
</body>
</html>