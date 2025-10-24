<?php
include('connexion.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire Professeur</title>
</head>
<body>
    <h2>Formulaire d'inscription - Professeur</h2>

    <form action="" method="POST">
        <label for="cin">CIN :</label>
        <input type="text" name="cin" placeholder="AA111111" required>
        <br><br>

        <label for="nom">Nom :</label>
        <input type="text" name="nom" required>
        <br><br>

        <label for="prenom">Prénom :</label>
        <input type="text" name="prenom" required>
        <br><br>

        <label for="email">Email :</label>
        <input type="email" name="email" required>
        <br><br>

        <label for="numero">Numéro :</label>
        <input type="text" name="numero" placeholder="0612345678" required>
        <br><br>

        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" required>
        <br><br>

        <label for="departement">Département :</label>
        <input type="text" name="departement" placeholder="Informatique" required>
        <br><br>

        <label for="matiere">Matière :</label>
        <input type="text" name="matiere" placeholder="Programmation Web" required>
        <br><br>

        <input type="submit" name="submit" value="Enregistrer">
    </form>

    <?php
    if (isset($_POST['submit'])) {

    
        $cin = htmlspecialchars($_POST['cin']);
        $nom = htmlspecialchars($_POST['nom']);
        $prenom = htmlspecialchars($_POST['prenom']);
        $email = htmlspecialchars($_POST['email']);
        $numero = htmlspecialchars($_POST['numero']);
        $adresse = htmlspecialchars($_POST['adresse']);
        $departement = htmlspecialchars($_POST['departement']);
        $matiere = htmlspecialchars($_POST['matiere']);

        $sql = "INSERT INTO prof (cin, nom, prenom, email, numero, adresse, departement, matiere)
                VALUES ('$cin', '$nom', '$prenom', '$email', '$numero', '$adresse', '$departement', '$matiere')";

        if ($conn->query($sql) === TRUE) {
            echo "<p style='color:green;'>✅ Données enregistrées avec succès !</p>";
        } else {
            echo "<p style='color:red;'>❌ Erreur : " . $conn->error . "</p>";
        }
    }
    ?>
</body>
</html>
