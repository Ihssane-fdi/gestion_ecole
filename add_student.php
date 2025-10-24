<?php
include('connexion.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Étudiant</title>
</head>
<body>
    <form action="" method="POST">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required><br>

        <label for="cin">cin :</label>
        <input type="text" name="cin" placeholder="AA111111" required><br>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" required><br>

        <label for="numero">Numéro :</label>
        <input type="number" name="numero" placeholder="0612345678" required><br>

        <label for="email">Email :</label>
        <input type="email" name="email" required><br>

        <label for="class">class :</label>
        <input type="text" name="class" placeholder="GINF2" required><br>

        <input type="submit" name="submit" value="Envoyer">
    </form>

    <?php
    if (isset($_POST['submit'])) {
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $cin = htmlspecialchars($_POST['cin']);
        $adresse = htmlspecialchars($_POST['adresse']);
        $numero = htmlspecialchars($_POST['numero']);
        $email = htmlspecialchars($_POST['email']);
        $class = htmlspecialchars($_POST['class']);

       
        $sql = "INSERT INTO student (cin, nom, prenom, email, numero, adresse, class)
                VALUES ('$cin', '$nom', '$prenom', '$email', '$numero', '$adresse', '$class')";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>✅ Données enregistrées avec succès !</p>";
        } else {
            echo "<p style='color:red;'>❌ Erreur : " . $conn->error . "</p>";
        }
    }
    ?>
</body>
</html>
