

<!DOCTYPE html>
<html>
<head>
    <title>Gestion d'école</title>
</head>

<body>
    <h1>Gestion d'école</h1>

    <h2>Gestion des Étudiants</h2>
    <a href="ajout_etudiant.php">Ajouter un étudiant</a>
    <table border="1">
        <tr>
            <th>CIN</th><th>Nom</th><th>Prénom</th><th>Email</th><th>Numéro</th><th>Adresse</th><th>Groupe</th>
        </tr>
        <?php while($row = $etudiants->fetch_assoc()): ?>
        <tr>
            <td><?= $row['CIN'] ?></td>
            <td><?= $row['nom'] ?></td>
            <td><?= $row['prenom'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['numero'] ?></td>
            <td><?= $row['adresse'] ?></td>
            <td><?= $row['groupe'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <a href="logout.php">Déconnexion</a>
</body>
</html>
