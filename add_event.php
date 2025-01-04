<?php
include('db.php');
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $act_id = mysqli_real_escape_string($conn, $_POST['act_id']);
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    $membre_id = mysqli_real_escape_string($conn, $_POST['membre_id']);
    
    mysqli_begin_transaction($conn);
    
    try {
        $query_activites = "INSERT INTO evenment (date, act_id) VALUES ('$date', '$act_id')";
        if (!mysqli_query($conn, $query_activites)) {
            throw new Exception("Erreur lors de l'insertion de l'activité: " . mysqli_error($conn));
        }
        
        $eve_id = mysqli_insert_id($conn);
        
        $query_rep_act = "INSERT INTO rep_eve (eve_id, membre_id) VALUES ('$eve_id', '$membre_id')";
        if (!mysqli_query($conn, $query_rep_act)) {
            throw new Exception("Erreur lors de l'association du membre: " . mysqli_error($conn));
        }
        
        mysqli_commit($conn);
        
        header('Location: home.php?message=activite_ajoutee');
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        error_log($e->getMessage());
        echo "Une erreur s'est produite. Veuillez réessayer.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une Activité</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h1>Ajouter une Evenement</h1>
    <form method="POST">
    
        <label for="date">date:</label>
        <input type="datetime-local" name="date" required>
        <label for="membre_id">Activite:</label>
        <select name="act_id" required>
            <option value="" disabled selected>-- Sélectionner un membre --</option>
            <?php
            $result = mysqli_query($conn, "SELECT * FROM activites");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>" . htmlspecialchars($row['description']) . "</option>";
            }
            ?>
        </select>
        
        
        <label for="membre_id">Membre Responsable:</label>
        <select name="membre_id" required>
            <option value="" disabled selected>-- Sélectionner un membre --</option>
            <?php

$result = mysqli_query($conn, "SELECT * FROM membres");
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<option value='{$row['id']}'>" . htmlspecialchars($row['nom']) . " " . htmlspecialchars($row['prenom']) . "</option>";
            }
            ?>
        </select>
        
        <input type="submit" value="Ajouter Activité">
    </form>
    <br>
    <a href="home.php">Accueil</a>
</body>
</html>