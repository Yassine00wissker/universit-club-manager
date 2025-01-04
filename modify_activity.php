<?php
include('db.php');
$activity = [
    'description' => '',
    'lieu' => '',
    'membres' => [] 
];

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id = mysqli_real_escape_string($conn, $_GET['id']);
        $query = "SELECT a.id, a.lieu, a.description, m.id AS membre_id, m.nom, m.prenom
              FROM activites a
              LEFT JOIN rep_act r ON a.id = r.act_id
              LEFT JOIN membres m ON r.membre_id = m.id
              WHERE a.id = '$id'";
    
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $activity['membres'] = [];
        while ($row = mysqli_fetch_assoc($result)) {
            if ($row['membre_id']) {
                $activity['membres'][] = [
                    'id' => $row['membre_id'],
                    'nom' => $row['nom'],
                    'prenom' => $row['prenom']
                ];
            }
            $activity['description'] = $row['description'];
            $activity['lieu'] = $row['lieu'];
            $activity['id'] = $row['id'];
        }
    } else {
        echo "Activité non trouvée.";
        exit;
    }
} else {
    echo "Identifiant d'activité manquant.";
    exit;
}

if (isset($_POST['add_rep_membre'])) {
    $membre_id = mysqli_real_escape_string($conn, $_POST['membre_id']);
    
    $check_query = "SELECT * FROM rep_act WHERE act_id = '{$activity['id']}' AND membre_id = '$membre_id'";
    $check_result = mysqli_query($conn, $check_query);
    
    if (mysqli_num_rows($check_result) > 0) {
        $error_message = "Ce membre est déjà associé à cette activité.";
    } else {
        $insert_query = "INSERT INTO rep_act (act_id, membre_id) VALUES ('{$activity['id']}', '$membre_id')";
        
        if (mysqli_query($conn, $insert_query)) {
            header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $activity['id']);
            exit;
        } else {
            $error_message = "Erreur lors de l'ajout du membre responsable: " . mysqli_error($conn);
        }
    }
}

if (isset($_GET['remove_membre_id'])) {
    $membre_id = mysqli_real_escape_string($conn, $_GET['remove_membre_id']);
    
    $delete_query = "DELETE FROM rep_act WHERE act_id = '{$activity['id']}' AND membre_id = '$membre_id'";
    
    if (mysqli_query($conn, $delete_query)) {
        header("Location: " . $_SERVER['PHP_SELF'] . "?id=" . $activity['id']);
        exit;
    } else {
        $error_message = "Erreur lors de la suppression du membre responsable: " . mysqli_error($conn);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !isset($_POST['add_rep_membre'])) {
    $lieu = mysqli_real_escape_string($conn, $_POST['lieu']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    
    mysqli_begin_transaction($conn);
    
    try {
        $updateActivityQuery = "UPDATE activites SET lieu = '$lieu', description = '$description' WHERE id = '$id'";
        
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
    <h1>Modifier Activité</h1>
    
    <?php if (isset($error_message)): ?>
        <p style="color: red;"><?= htmlspecialchars($error_message) ?></p>
    <?php endif; ?>
    
    <form method="POST">
        <label for="lieu">Lieu:</label>
        <input type="text" name="lieu" value="<?= htmlspecialchars($activity['lieu']); ?>" required>
        
        <label for="description">Description:</label>
        <input type="text" name="description" value="<?= htmlspecialchars($activity['description']); ?>" required>
        
        <input type="submit" value="Mettre à jour l'activité">
    </form>

    <div class="membres-container">
        <h2>Membres Responsables</h2>
        
        <?php if (!empty($activity['membres'])): ?>
            <div class="membres-list">
                <?php foreach ($activity['membres'] as $membre): ?>
                    <div>
                        <?= htmlspecialchars($membre['nom'] . ' ' . $membre['prenom']) ?>
                        <a href="?id=<?= $activity['id'] ?>&remove_membre_id=<?= $membre['id'] ?>" 
                           onclick="return confirm('Voulez-vous vraiment supprimer ce membre responsable ?');">
                            Supprimer
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Aucun membre responsable pour cette activité.</p>
        <?php endif; ?>

        <form method="POST" class="add-membre-form">
            <label for="membre_id">Ajouter un Membre Responsable:</label>
            <select name="membre_id" required>
                <option value="">Sélectionner un membre</option>
                <?php
                // Fetch all membres not already associated with this activity
                $membres_query = "SELECT * FROM membres 
                                  WHERE id NOT IN (
                                      SELECT membre_id FROM rep_act 
                                      WHERE act_id = '{$activity['id']}'
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
    <a href="show_activities.php">Retour à la liste des activités</a>
</body>
</html>
<?php
// Close the database connection
mysqli_close($conn);
?>