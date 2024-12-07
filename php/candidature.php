<?php
// Connexion à la base de données
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $phone = htmlspecialchars($_POST['phone']);
    $offre_id = $_POST['offre_id']; // ID de l'offre de stage, transmis depuis le formulaire

    // Gestion du téléchargement du CV
    $cv_name = $_FILES['cv']['name'];
    $cv_tmp_name = $_FILES['cv']['tmp_name'];
    $cv_size = $_FILES['cv']['size'];
    $cv_ext = pathinfo($cv_name, PATHINFO_EXTENSION);

    // Gestion du téléchargement de la lettre de motivation
    $motivation_name = $_FILES['motivation']['name'];
    $motivation_tmp_name = $_FILES['motivation']['tmp_name'];
    $motivation_size = $_FILES['motivation']['size'];
    $motivation_ext = pathinfo($motivation_name, PATHINFO_EXTENSION);

    // Gestion optionnelle du téléchargement de l'image
    $image_path = null;
    if (!empty($_FILES['image']['name'])) {
        $image_name = $_FILES['image']['name'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_size = $_FILES['image']['size'];
        $image_ext = pathinfo($image_name, PATHINFO_EXTENSION);

        $allowed_image_ext = ['jpg', 'jpeg', 'png'];
        if (!in_array($image_ext, $allowed_image_ext) || $image_size > 5 * 1024 * 1024) {
            echo "<script>alert('L\'image doit être au format JPG, JPEG ou PNG et ne pas dépasser 5 Mo.');</script>";
            exit();
        }

        // Déplacer l'image téléchargée dans le répertoire spécifique
        $image_path = 'uploads/images/' . basename($image_name);
        if (!move_uploaded_file($image_tmp_name, $image_path)) {
            echo "<script>alert('Erreur lors de l\'upload de l\'image.');</script>";
            exit();
        }
    }

    // Vérification des extensions et tailles pour le CV et la lettre de motivation
    $allowed_ext = ['pdf', 'doc', 'docx'];
    $max_size = 5 * 1024 * 1024; // 5 Mo

    if (!in_array($cv_ext, $allowed_ext) || $cv_size > $max_size || !in_array($motivation_ext, $allowed_ext) || $motivation_size > $max_size) {
        echo "<script>alert('Les fichiers doivent être au format PDF, DOC ou DOCX et chaque fichier doit être inférieur à 5 Mo.');</script>";
        exit();
    }

    // Déplacer les fichiers téléchargés dans leurs répertoires respectifs
    $cv_path = 'uploads/cvs/' . basename($cv_name);
    $motivation_path = 'uploads/motivations/' . basename($motivation_name);

    if (!move_uploaded_file($cv_tmp_name, $cv_path) || !move_uploaded_file($motivation_tmp_name, $motivation_path)) {
        echo "<script>alert('Erreur lors de l\'enregistrement des fichiers.');</script>";
        exit();
    }

    // Insérer les données dans la table `candidatures`
    $stmt = $pdo->prepare("
        INSERT INTO candidatures (nom, email, phone, cv, motivation, offre_id, image)
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$nom, $email, $phone, $cv_path, $motivation_path, $offre_id, $image_path]);

    echo "<script>alert('Votre candidature a été soumise avec succès.');</script>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Candidature - Professeur Permanent</title>
    <link rel="stylesheet" href="../style/candidature.css"> <!-- Lien vers le fichier CSS -->
    <link href="../style/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Styles spécifiques peuvent être ajoutés ici si nécessaire */
    </style>
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="logo">StageNow</div>
            <ul class="nav-links">
                <li><a href="../html/index.html">Accueil</a></li>
                <li><a href="../html/recherche.html">Recherche</a></li>
                <li><a href="../html/offre.html">Offres de stages</a></li>
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

    <div class="container mt-5">
        <h1 class="text-center mb-4">Formulaire de Candidature - Professeur Permanent</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="name" class="form-label">Nom complet</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="phone" class="form-label">Numéro de téléphone</label>
                <input type="tel" class="form-control" id="phone" name="phone" required>
            </div>
            <div class="mb-3">
                <label for="cv" class="form-label">Télécharger votre CV</label>
                <input type="file" class="form-control" id="cv" name="cv" accept=".pdf,.doc,.docx" required>
            </div>
            <div class="mb-3">
                <label for="motivation" class="form-label">Télécharger votre Lettre de Motivation</label>
                <input type="file" class="form-control" id="motivation" name="motivation" accept=".pdf,.doc,.docx" required>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Télécharger une Image (optionnel)</label>
                <input type="file" class="form-control" id="image" name="image" accept=".jpg,.jpeg,.png">
            </div>
            <input type="hidden" name="offre_id" value="<?php echo $_GET['id']; ?>">
            <button type="submit" class="btn btn-primary">Soumettre ma candidature</button>
        </form>
    </div>

    <!-- Footer -->
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
