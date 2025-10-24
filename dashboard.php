<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

// Connexion à la base de données
$host = 'localhost';
$dbname = 'gestion_ecole';
$username = 'root'; // À adapter selon votre configuration
$password = ''; // À adapter selon votre configuration

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Compter les étudiants
    $stmt = $pdo->query("SELECT COUNT(*) FROM student");
    $studentCount = $stmt->fetchColumn();
    
    // Compter les professeurs
    $stmt = $pdo->query("SELECT COUNT(*) FROM prof");
    $profCount = $stmt->fetchColumn();
    
    // Récupérer la liste des étudiants
    $students = $pdo->query("SELECT * FROM student")->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer la liste des professeurs
    $professors = $pdo->query("SELECT * FROM prof")->fetchAll(PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion École - ENSA Tanger</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header {
            background: rgba(255, 255, 255, 0.95);
            padding: 1.5rem 2rem;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            color: #667eea;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo::before {
            content: "🎓";
            font-size: 2rem;
        }

        .header-title {
            flex: 1;
        }

        .header-title h1 {
            font-size: 1.5rem;
            color: #333;
        }

        .header-title p {
            font-size: 0.9rem;
            color: #666;
            margin-top: 0.2rem;
        }

        nav {
            background: rgba(255, 255, 255, 0.9);
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-content a {
            color: #555;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            transition: all 0.3s ease;
        }

        .nav-content a:hover, .nav-content a.active {
            background: #667eea;
            color: white;
        }

        main {
            flex: 1;
            padding: 2rem;
            max-width: 1200px;
            margin: 0 auto;
            width: 100%;
        }

        .card {
            background: white;
            padding: 2rem;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: slideIn 0.5s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
        }

        .page-header h1 {
            color: #333;
        }

        .btn {
            padding: 0.8rem 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.6);
        }

        /* Dashboard Styles */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }

        .stat-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 2rem;
            border-radius: 15px;
            color: white;
            text-align: center;
            box-shadow: 0 10px 30px rgba(102, 126, 234, 0.4);
        }

        .stat-card .icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .stat-card h2 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }

        thead {
            background: #667eea;
            color: white;
        }

        th {
            padding: 1rem;
            text-align: left;
            font-weight: 600;
        }

        td {
            padding: 1rem;
            border-bottom: 1px solid #e0e0e0;
        }

        tr:hover {
            background: #f8f9fa;
        }

        /* Form Styles */
        .form-container {
            background: #f8f9fa;
            padding: 2rem;
            border-radius: 10px;
            margin-bottom: 2rem;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1.5rem;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 0.5rem;
            color: #555;
            font-weight: 500;
        }

        .form-group input,
        .form-group select {
            padding: 0.9rem 1rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 1rem;
            background: white;
        }

        .form-group input:focus,
        .form-group select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        footer {
            background: rgba(255, 255, 255, 0.95);
            padding: 1.5rem 2rem;
            margin-top: auto;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            text-align: center;
            color: #666;
        }

        .hidden {
            display: none;
        }

        .success {
            background: #e8f5e9;
            color: #2e7d32;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .error {
            background: #ffebee;
            color: #c62828;
            padding: 1rem;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .nav-content {
                flex-wrap: wrap;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="header-content">
            <div class="logo">ENSA</div>
            <div class="header-title">
                <h1>Système de Gestion d'École</h1>
                <p>École Nationale des Sciences Appliquées - Tanger</p>
            </div>
        </div>
    </header>

    <nav>
        <div class="nav-content">
            <a href="#" onclick="showPage('accueil')" id="nav-accueil" class="active">🏠 Accueil</a>
            <a href="#" onclick="showPage('etudiants')" id="nav-etudiants">🎓 Étudiants</a>
            <a href="#" onclick="showPage('professeurs')" id="nav-professeurs">📚 Professeurs</a>
            <a href="logout.php">🚪 Déconnexion</a>
        </div>
    </nav>

    <main>
        <!-- Page Accueil -->
        <div id="page-accueil" class="card">
            <h1 style="color: #333; margin-bottom: 1rem;">Tableau de Bord</h1>
            <p style="color: #666; margin-bottom: 2rem;">Bienvenue sur le système de gestion de l'ENSA Tanger</p>
            
            <div class="dashboard-grid">
                <div class="stat-card">
                    <div class="icon">🎓</div>
                    <h2 id="student-count"><?php echo $studentCount; ?></h2>
                    <p>Étudiants inscrits</p>
                </div>
                <div class="stat-card">
                    <div class="icon">📚</div>
                    <h2 id="prof-count"><?php echo $profCount; ?></h2>
                    <p>Professeurs</p>
                </div>
                <div class="stat-card">
                    <div class="icon">👥</div>
                    <h2>5</h2>
                    <p>Filières actives</p>
                </div>
            </div>
        </div>

        <!-- Page Étudiants -->
        <div id="page-etudiants" class="card hidden">
            <div class="page-header">
                <h1>Gestion des Étudiants</h1>
                <button class="btn" onclick="toggleForm('student-form')">➕ Ajouter un étudiant</button>
            </div>

            <?php
            if (isset($_GET['student_added']) && $_GET['student_added'] == 'success') {
                echo '<div class="success">Étudiant ajouté avec succès!</div>';
            }
            if (isset($_GET['student_error'])) {
                echo '<div class="error">Erreur lors de l\'ajout de l\'étudiant: ' . htmlspecialchars($_GET['student_error']) . '</div>';
            }
            ?>

            <div id="student-form" class="form-container hidden">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Nouveau Étudiant</h3>
                <form method="POST" action="add_student.php">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>CIN</label>
                            <input type="text" name="cin" placeholder="Ex: AB123456" required maxlength="8">
                        </div>
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" placeholder="Nom de famille" required maxlength="20">
                        </div>
                        <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" name="prenom" placeholder="Prénom" required maxlength="20">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="exemple@ensa.ma" required maxlength="20">
                        </div>
                        <div class="form-group">
                            <label>Numéro</label>
                            <input type="tel" name="numero" placeholder="Numéro de téléphone" required>
                        </div>
                        <div class="form-group">
                            <label>Adresse</label>
                            <input type="text" name="adresse" placeholder="Adresse complète" required maxlength="50">
                        </div>
                        <div class="form-group">
                            <label>Classe</label>
                            <input type="text" name="class" placeholder="Ex: GI1" required maxlength="9">
                        </div>
                    </div>
                    <button type="submit" class="btn" style="margin-top: 1rem;">💾 Enregistrer</button>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>CIN</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Numéro</th>
                        <th>Adresse</th>
                        <th>Classe</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="students-table">
                    <?php foreach ($students as $student): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($student['cin']); ?></td>
                        <td><?php echo htmlspecialchars($student['nom']); ?></td>
                        <td><?php echo htmlspecialchars($student['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($student['email']); ?></td>
                        <td><?php echo htmlspecialchars($student['numero']); ?></td>
                        <td><?php echo htmlspecialchars($student['adresse']); ?></td>
                        <td><?php echo htmlspecialchars($student['class']); ?></td>
                        <td>
                            <button class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.9rem;">✏️ Modifier</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Page Professeurs -->
        <div id="page-professeurs" class="card hidden">
            <div class="page-header">
                <h1>Gestion des Professeurs</h1>
                <button class="btn" onclick="toggleForm('prof-form')">➕ Ajouter un professeur</button>
            </div>

            <?php
            if (isset($_GET['prof_added']) && $_GET['prof_added'] == 'success') {
                echo '<div class="success">Professeur ajouté avec succès!</div>';
            }
            if (isset($_GET['prof_error'])) {
                echo '<div class="error">Erreur lors de l\'ajout du professeur: ' . htmlspecialchars($_GET['prof_error']) . '</div>';
            }
            ?>

            <div id="prof-form" class="form-container hidden">
                <h3 style="margin-bottom: 1.5rem; color: #333;">Nouveau Professeur</h3>
                <form method="POST" action="add_professor.php">
                    <div class="form-grid">
                        <div class="form-group">
                            <label>CIN</label>
                            <input type="text" name="cin" placeholder="Ex: AB123456" required maxlength="8">
                        </div>
                        <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="nom" placeholder="Nom de famille" required maxlength="20">
                        </div>
                        <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" name="prenom" placeholder="Prénom" required maxlength="20">
                        </div>
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" placeholder="exemple@ensa.ma" required maxlength="20">
                        </div>
                        <div class="form-group">
                            <label>Numéro</label>
                            <input type="tel" name="numero" placeholder="Numéro de téléphone" required>
                        </div>
                        <div class="form-group">
                            <label>Adresse</label>
                            <input type="text" name="adresse" placeholder="Adresse complète" required maxlength="50">
                        </div>
                        <div class="form-group">
                            <label>Département</label>
                            <input type="text" name="departement" placeholder="Ex: Informatique" required maxlength="9">
                        </div>
                        <div class="form-group">
                            <label>Matière</label>
                            <input type="text" name="matiere" placeholder="Ex: Développement Web" required maxlength="29">
                        </div>
                    </div>
                    <button type="submit" class="btn" style="margin-top: 1rem;">💾 Enregistrer</button>
                </form>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>CIN</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Numéro</th>
                        <th>Adresse</th>
                        <th>Département</th>
                        <th>Matière</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="professors-table">
                    <?php foreach ($professors as $professor): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($professor['cin']); ?></td>
                        <td><?php echo htmlspecialchars($professor['nom']); ?></td>
                        <td><?php echo htmlspecialchars($professor['prenom']); ?></td>
                        <td><?php echo htmlspecialchars($professor['email']); ?></td>
                        <td><?php echo htmlspecialchars($professor['numero']); ?></td>
                        <td><?php echo htmlspecialchars($professor['adresse']); ?></td>
                        <td><?php echo htmlspecialchars($professor['departement']); ?></td>
                        <td><?php echo htmlspecialchars($professor['matiere']); ?></td>
                        <td>
                            <button class="btn" style="padding: 0.4rem 0.8rem; font-size: 0.9rem;">✏️ Modifier</button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </main>

    <footer>
        <div class="footer-content">
            <p>&copy; 2025 ENSA Tanger. Tous droits réservés.</p>
        </div>
    </footer>

    <script>
        function showPage(pageName) {
            // Cacher toutes les pages
            document.querySelectorAll('[id^="page-"]').forEach(page => {
                page.classList.add('hidden');
            });
            
            // Retirer la classe active de tous les liens
            document.querySelectorAll('.nav-content a').forEach(link => {
                link.classList.remove('active');
            });
            
            // Afficher la page demandée
            document.getElementById('page-' + pageName).classList.remove('hidden');
            
            // Ajouter la classe active au lien
            document.getElementById('nav-' + pageName).classList.add('active');
        }

        function toggleForm(formId) {
            const form = document.getElementById(formId);
            form.classList.toggle('hidden');
        }
    </script>
</body>
</html>