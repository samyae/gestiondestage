<?php
// Connexion à la base de données
$dsn = 'mysql:host=localhost;dbname=gestionDeStage;charset=utf8';
$username = 'root';
$password = '';
try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérification et récupération de l'ID de l'offre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int)$_GET['id'];

    // Requête pour récupérer les détails de l'offre et les informations de l'entreprise
    $query = "
        SELECT 
            o.titre, o.type_stage, o.specialite, o.ville, o.duree, o.contrat, o.disponibilite, 
            o.date_debut, o.date_fin, o.description, o.salaire, e.nom AS nom_entreprise, 
            e.secteur, e.taille, e.localisation, e.telephone, e.email, e.image
        FROM offres o
        JOIN entreprises e ON o.id_entreprise = e.id
        WHERE o.id = :id
    ";
    $stmt = $pdo->prepare($query);
    $stmt->execute(['id' => $id]);
    $offre = $stmt->fetch();

    if (!$offre) {
        die("Offre introuvable.");
    }
} else {
    die("ID d'offre invalide.");
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'offre - <?= htmlspecialchars($offre['titre']) ?></title>
    <link rel="stylesheet" href="../style/styleOffre.css">
    <link href="../style/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f9f9f9;
            color: #333;
        }

        .navbar {
            background: linear-gradient(75deg, #6D5B98, #8B79DA);
            padding: 1rem 3rem;
            border-bottom-left-radius: 30px;
            border-bottom-right-radius: 30px;
        }

        .navbar .logo {
            font-size: 2rem;
            font-weight: 700;
            color: #FFD700;
        }

        .container {
            margin-top: 40px;
            max-width: 1200px;
        }

        h1 {
            color: #6D5B98;
            margin-bottom: 20px;
        }

        .details, .job-description {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }

        .job-description {
            margin-top: 20px;
        }

        .btn-primary {
            background-color: #FFD700;
            border-color: #FFD700;
            color: #6D5B98;
            font-weight: bold;
            padding: 10px 20px;
            border-radius: 30px;
            transition: background-color 0.3s ease, color 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #6D5B98;
            color: #fff;
        }

        .image-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .image-container img {
            max-width: 100%;
            border-radius: 10px;
            height: auto;
        }

        footer {
            background: linear-gradient(75deg, #6D5B98, #8B79DA);
            color: white;
            text-align: center;
            padding: 20px;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
        }
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">StageNow</div>
            <ul class="nav-links">
                <li><a href="../html/index.html">Accueil</a></li>
                <li><a href="../html/recherche.html">Recherche</a></li>
                <li><a href="../html/offres.html" class="active">Offres de stages</a></li>
            </ul>
            <div class="auth-buttons">
                <a href="../html/inscription.html" class="btn btn-signup">
                    <i class="fas fa-user-plus"></i> Inscription
                </a>
                <a href="../html/seconnecter.html" class="btn btn-login">
                    <i class="fas fa-sign-in-alt"></i> Connexion
                </a>
            </div>
        </nav>
    </header>

    <div class="container">
        <h1><?= htmlspecialchars($offre['titre']) ?></h1>
        <div class="row">
            <div class="col-lg-4">
                <!-- Résumé de l'offre -->
                <div class="details">
                    <h5>Résumé du poste</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Type : <?= htmlspecialchars($offre['type_stage']) ?></li>
                        <li class="list-group-item">Ville : <?= htmlspecialchars($offre['ville']) ?></li>
                        <li class="list-group-item">Contrat : <?= htmlspecialchars($offre['contrat']) ?></li>
                        <li class="list-group-item">Durée : <?= htmlspecialchars($offre['duree']) ?> mois</li>
                        <li class="list-group-item">Salaire : <?= htmlspecialchars($offre['salaire']) ?> €</li>
                        <li class="list-group-item">Date de début : <?= htmlspecialchars($offre['date_debut']) ?></li>
                        <li class="list-group-item">Date de fin : <?= htmlspecialchars($offre['date_fin']) ?></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-8">
                <!-- Image de l'entreprise -->
                <div class="image-container">
                    <img src="<?= htmlspecialchars($offre['image']) ?>" alt="Image de l'entreprise">
                </div>
                <!-- Description -->
                <div class="job-description">
                    <h5>Description de l'offre</h5>
                    <p><?= nl2br(htmlspecialchars($offre['description'])) ?></p>

                    <h5>Entreprise</h5>
                    <p><strong><?= htmlspecialchars($offre['nom_entreprise']) ?></strong></p>
                    <p>Secteur : <?= htmlspecialchars($offre['secteur']) ?></p>
                    <p>Localisation : <?= htmlspecialchars($offre['localisation']) ?></p>
                </div>
                <button class="btn btn-primary mt-4" onclick="window.location.href='candidature.php?offre_id=<?= $id ?>';">Postuler</button>
            </div>
        </div>
    </div>

    <footer class="bg-dark text-white mt-5 p-4">
        <div class="footer-sections">
            <div class="about">
                <h4>À propos</h4>
                <ul>
                    <li><a href="../html/quiSommesNous.html" class="text-white">Qui sommes-nous</a></li>
                    <li><a href="../html/mission.html" class="text-white">Notre mission</a></li>
                    <li><a href="../html/index.html#team" class="text-white">Équipe</a></li>
                </ul>
            </div>
            <div class="quick-links">
                <h4>Liens rapides</h4>
                <ul>
                    <li><a href="../html/index.html" class="text-white">Accueil</a></li>
                    <li><a href="../html/offre.html" class="text-white">Offres d'emploi</a></li>
                    <li><a href="../html/faq.html" class="text-white">FAQ</a></li>
                </ul>
            </div>
            <div class="social-media">
                <h4>Suivez-nous</h4>
                <ul>
                    <li><a href="https://www.facebook.com" target="_blank" class="text-white"><i class="fab fa-facebook-f"></i> Facebook</a></li>
                    <li><a href="https://www.instagram.com" target="_blank" class="text-white"><i class="fab fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom text-center mt-3">
            <p>&copy; 2024 MonSite. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
