<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des données du formulaire
    $cin = $_POST['cin'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $numero = $_POST['numero'];
    $adresse = $_POST['adresse'];
    $departement = $_POST['departement'];
    $matiere = $_POST['matiere'];
    
    // Connexion à la base de données
    $host = 'localhost';
    $dbname = 'gestion_ecole';
    $username = 'root'; // À adapter selon votre configuration
    $password = ''; // À adapter selon votre configuration
    
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Insertion du professeur
        $stmt = $pdo->prepare("INSERT INTO prof (cin, nom, prenom, email, numero, adresse, departement, matiere) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$cin, $nom, $prenom, $email, $numero, $adresse, $departement, $matiere]);
        
        header('Location: dashboard.php?prof_added=success');
        exit();
        
    } catch (PDOException $e) {
        $error = $e->getMessage();
        header('Location: dashboard.php?prof_error=' . urlencode($error));
        exit();
    }
} else {
    header('Location: dashboard.php');
    exit();
}
?>