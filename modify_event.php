<?php
include('db.php');

$evenment = [
    'date' => '',
    'membres' => []
];

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
    
    $query = "SELECT a.id, a.date, m.id AS membre_id, m.nom, m.prenom
              FROM evenment a
              LEFT JOIN rep_eve r ON a.id = r.eve_id
              LEFT JOIN membres m ON r.membre_id = m.id
              WHERE a.id = '$id'";
    
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $evenment['membres'] = [];
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['membre_id']) {
                $evenment['membres'][] = [
                    'id' => $row['membre_id'],
                    'nom' => $row['nom'],
                    'prenom' => $row['prenom']
                ];
            }
            $evenment['date'] = $row['date'];
            $evenment['id'] = $row['id'];
        }
    } else {
        echo "Evenement non trouvé.";
        exit;
    }
} else {
    echo "Identifiant d'événement manquant.";
    exit;
}

if (isset($_POST['add_rep_membre'])) {
    $membre_id = mysqli_real_escape_string($conn, $_POST['membre_id']);
    
    $check_query = "SELECT * FROM rep_eve WHERE eve_id = '{$evenment['id']}' AND membre_id = '$membre_id'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error_message = "Ce membre est déjà associé à cet événement.";
    } else {
        $insert_query = "INSERT INTO rep_eve (eve_id, membre_id) VALUES ('{$evenment['id']}', '$membre_id')";
        
        if (mysqli_query($conn, $insert_query)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $evenment['id']);
            exit;
        } else {
            $error_message = "Erreur lors de l'ajout du membre responsable: " . mysqli_error($conn);
        }
    }
}

if (isset($_GET['remove_membre_id'])) {
    $membre_id = mysqli_real_escape_string($conn, $_GET['remove_membre_id']);
    
    $delete_query = "DELETE FROM rep_eve WHERE eve_id = '{$evenment['id']}' AND membre_id = '$membre_id'";
    
    if (mysqli_query($conn, $delete_query)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $evenment['id']);
        exit;
    } else {
        $error_message = "Erreur lors de la suppression du membre responsable: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['add_rep_membre'])) {
    $date = mysqli_real_escape_string($conn, $_POST['date']);
    
    mysqli_begin_transaction($conn);
    
    try {
        $updateActivityQuery = "UPDATE evenment SET date = '$date' WHERE id = '$id'";
        
        if (!mysqli_query($conn, $updateActivityQuery)) {
            throw new Exception("Erreur de mise à jour de l'activité: " . mysqli_error($conn));
        }
        
        mysqli_commit($conn);
        
        header('Location: show_activities.php');
        exit;
    } catch (Exception $e) {
        mysqli_rollback($conn);
        echo $e->getMessage();
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Activité</title>
    <link rel="stylesheet" href="index.css">
</head>
<body>
    <h1>Modifier Evenement</h1>
    
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <label for="date">Date:</label>
        <input type="datetime-local" name="date" value="<?= htmlspecialchars($evenment['date']); ?>" required>
        <input type="submit" value="Mettre à jour l'activité">
    </form>

    <div class="membres-container">
        <h2>Membres Responsables</h2>
        
        <?php if (!empty($evenment['membres'])): ?>
            <div class="membres-list">
                <?php foreach ($evenment['membres'] as $membre): ?>
                    <div>
                        <?= htmlspecialchars($membre['nom'] . ' ' . $membre['prenom']) ?>
                        <a href="?id=<?= $evenment['id'] ?>&remove_membre_id=<?= $membre['id'] ?>" 
                           onclick="return confirm('Voulez-vous vraiment supprimer ce membre responsable ?');">
                            Supprimer
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun membre responsable pour cet événement.</p>
        <?php endif; ?>

        <form method="POST" class="add-membre-form">
            <label for="membre_id">Ajouter un Membre Responsable:</label>
            <select name="membre_id" required>
                <option value="">Sélectionner un membre</option>
                <?php
                $membres_query = "SELECT * FROM membres 
                                  WHERE id NOT IN (
                                      SELECT membre_id FROM rep_eve 
                                      WHERE eve_id = '{$evenment['id']}'
                                  )";
                $membres_result = mysqli_query($conn, $membres_query);
                
                while ($membre = mysqli_fetch_assoc($membres_result)) {
                    echo "<option value='{$membre['id']}'>" 
                         . htmlspecialchars($membre['nom'] . ' ' . $membre['prenom']) 
                         . "</option>";
                }
                ?>
            </select>
            
            <input type="submit" name="add_rep_membre" value="Ajouter">
        </form>
    </div>

    <br>
    <a href="show_event.php">Retour à la liste des activités</a>
</body>
</html>
<?php
mysqli_close($conn);
?>
