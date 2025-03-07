<?php
session_start();
include 'config.php';

$teacher_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $exercice_type = $_POST['exercice_type'];
    $class_level = $_POST['class_level'];
    $min_value = $_POST['min_value'];
    $max_value = $_POST['max_value'];
    $exercice_name = $_POST['exercice_name'];

    // Gérer le téléchargement de l'image
    $target_dir = "IMG/";
    $target_file = $target_dir . basename($_FILES["exercice_image"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    if (getimagesize($_FILES["exercice_image"]["tmp_name"]) !== false) {
        if (move_uploaded_file($_FILES["exercice_image"]["tmp_name"], $target_file)) {
            // Ajouter l'exercice à la base de données
            $stmt = $pdo->prepare("INSERT INTO exercices (exercice_type, class_level, min_value, max_value, exercice_name, exercice_image, created_by) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$exercice_type, $class_level, $min_value, $max_value, $exercice_name, $target_file, $teacher_id]);

            $exercice_id = $pdo->lastInsertId();

            // Associer aux élèves
            $stmt = $pdo->prepare("SELECT id FROM users WHERE teacher_id = ? AND class_level = ? AND role = 'enfant'");
            $stmt->execute([$teacher_id, $class_level]);
            $students = $stmt->fetchAll();

            foreach ($students as $student) {
                $stmt = $pdo->prepare("INSERT INTO exercices (exercice_id, student_id) VALUES (?, ?)");
                $stmt->execute([$exercice_id, $student['id']]);
            }

            echo "Exercice créé et attribué aux élèves avec succès!";
        } else {
            echo "Erreur lors du téléchargement de l'image.";
        }
    } else {
        echo "Le fichier n'est pas une image.";
    }
}
?>

<form method="POST" enctype="multipart/form-data">
    <label for="exercice_type">Type d'exercice :</label>
    <select name="exercice_type">
        <option value="multiplication">Multiplication</option>
        <option value="addition">Addition</option>
        <option value="soustraction">Soustraction</option>
        <option value="division">Division</option>
    </select>

    <label for="class_level">Classe de l'élève :</label>
    <select name="class_level">
        <option value="CP">CP</option>
        <option value="CE1">CE1</option>
        <option value="CE2">CE2</option>
        <option value="CM1">CM1</option>
        <option value="CM2">CM2</option>
    </select>

    <label for="min_value">Valeur minimale :</label>
    <input type="number" name="min_value" required>

    <label for="max_value">Valeur maximale :</label>
    <input type="number" name="max_value" required>

    <label for="exercice_name">Nom de l'exercice :</label>
    <input type="text" name="exercice_name" required>

    <label for="exercice_image">Image de l'exercice :</label>
    <input type="file" name="exercice_image" accept="image/*" required>

    <button type="submit">Créer l'exercice</button>
</form>
