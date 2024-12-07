<?php
// Inclure la configuration de la base de données
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $titre = htmlspecialchars($_POST['titre']);
    $description = htmlspecialchars($_POST['description']);
    $localisation = htmlspecialchars($_POST['localisation']);
    $duree = htmlspecialchars($_POST['duree']);
    $type_stage = htmlspecialchars($_POST['type_stage']);
    $specialite = htmlspecialchars($_POST['specialite']);
    $ville = htmlspecialchars($_POST['ville']);
    $contrat = htmlspecialchars($_POST['contrat']);
    $disponibilite = htmlspecialchars($_POST['disponibilite']);
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $salaire = $_POST['salaire'];

    // Assumer que l'ID de l'entreprise et du recruteur sont déjà définis (par exemple, via une session ou une authentification)
    $id_entreprise = 1; // Remplacer par la variable de session ou autre méthode
    $id_recruteurs = 1;  // Remplacer par la variable de session ou autre méthode

    // Insertion dans la base de données
    $stmt = $pdo->prepare("INSERT INTO offres 
        (titre, description, localisation, duree, type_stage, specialite, ville, contrat, disponibilite, date_debut, date_fin, salaire, id_entreprise, id_recruteurs)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    // Exécution de la requête avec les données récupérées du formulaire
    $stmt->execute([
        $titre, 
        $description, 
        $localisation, 
        $duree, 
        $type_stage, 
        $specialite, 
        $ville, 
        $contrat, 
        $disponibilite, 
        $date_debut, 
        $date_fin, 
        $salaire, 
        $id_entreprise, 
        $id_recruteurs
    ]);

    // Message de confirmation
    echo "<script>alert('Votre offre de stage a été publiée avec succès.');</script>";
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Stage</title>
    <link rel="stylesheet" href="../style/poserStage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <!-- En-tête -->
    <header>
        <nav class="navbar">
            <div class="logo">StageNow</div>
            <ul class="nav-links">
                <li><a href="../html/index.html">Accueil</a></li>
                <li><a href="../html/recherche.html">Recherche</a></li>
                <li><a href="../html/offre.html" class="active">Offres de stages</a></li>
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

    <!-- Contenu principal -->
    <div class="container">
        <h1>Ajouter une Offre de Stage</h1>
        <form action="poserStage.php" method="POST">
            <!-- Titre du Stage -->
            <label for="titre">Titre du Stage :</label>
            <input type="text" id="titre" name="titre" placeholder="Exemple : Développeur Web Junior" required>

            <!-- Description -->
            <label for="description">Description :</label>
            <textarea id="description" name="description" placeholder="Décrivez les missions et les responsabilités du poste" required></textarea>

            <!-- Localisation -->
            <label for="localisation">Localisation :</label>
            <input type="text" id="localisation" name="localisation" placeholder="Exemple : Paris, France" required>

            <!-- Durée -->
            <label for="duree">Durée (en mois) :</label>
            <input type="number" id="duree" name="duree" placeholder="Exemple : 6" min="1" required>

            <!-- Type de Contrat -->
            <label for="type_stage">Type de Contrat :</label>
            <select id="type_stage" name="type_stage" required>
                <option value="">Sélectionnez...</option>
                <option value="Stage">Stage</option>
                <option value="Alternance">Alternance</option>
                <option value="CDI">CDI</option>
                <option value="CDD">CDD</option>
            </select>

            <!-- Spécialité -->
            <label for="specialite">Spécialité :</label>
            <input type="text" id="specialite" name="specialite" placeholder="Exemple : Marketing" required>

            <!-- Ville -->
            <label for="ville">Ville :</label>
            <input type="text" id="ville" name="ville" placeholder="Exemple : Paris" required>

            <!-- Contrat -->
            <label for="contrat">Type de Contrat :</label>
            <input type="text" id="contrat" name="contrat" placeholder="Exemple : CDI, CDD" required>

            <!-- Disponibilité -->
            <label for="disponibilite">Disponibilité :</label>
            <input type="text" id="disponibilite" name="disponibilite" placeholder="Exemple : Immédiate" required>

            <!-- Date de début -->
            <label for="date_debut">Date de Début :</label>
            <input type="date" id="date_debut" name="date_debut" required>

            <!-- Date de fin -->
            <label for="date_fin">Date de Fin :</label>
            <input type="date" id="date_fin" name="date_fin" required>

            <!-- Rémunération -->
            <label for="salaire">Rémunération (€/mois) :</label>
            <input type="number" id="salaire" name="salaire" placeholder="Exemple : 800" min="0">

            <!-- Bouton d'Envoi -->
            <button type="submit" class="btn">Publier l'Offre</button>
        </form>
    </div>

    <!-- Pied de page -->
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

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
