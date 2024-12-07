document.addEventListener("DOMContentLoaded", () => {
  const offresContainer = document.querySelector(".offers");
  const filtersForm = document.querySelector(".filters");

  const chargerOffres = async (filters = {}) => {
    try {
      const params = new URLSearchParams(filters).toString();
      const response = await fetch(`get_offres.php?${params}`);
      const data = await response.json();
      afficherOffres(data.offres);
    } catch (error) {
      console.error("Erreur lors du chargement des offres :", error);
    }
  };

  const afficherOffres = (offres) => {
    offresContainer.innerHTML = "";

    if (offres.length === 0) {
      offresContainer.innerHTML = "<p>Aucune offre trouvée.</p>";
      return;
    }

    offres.forEach((offre) => {
      const offreHTML = `
        <article class="offer">
          <a href="details.php?id=${offre.id}" class="offer-link">
            <div class="offer-header">
              <h3>${offre.titre}</h3>
              <p>${offre.entreprise} - ${offre.ville}</p>
            </div>
            <div class="offer-tags">
              <span>${offre.specialite}</span>
              <span>${offre.duree}</span>
            </div>
            <div class="offer-footer">
              <p>${offre.contrat} | Début: ${new Date(
        offre.date_debut
      ).toLocaleDateString()}</p>
            </div>
          </a>
        </article>
      `;
      offresContainer.innerHTML += offreHTML;
    });
  };

  filtersForm.addEventListener("change", () => {
    const filters = {
      specialite: document.getElementById("specialite").value,
      ville: document.getElementById("ville").value,
      duree: document.getElementById("duree").value,
      contrat: document.getElementById("contrat").value,
    };
    chargerOffres(filters);
  });

  // Réinitialiser les filtres et recharger les offres sans filtres appliqués
  filtersForm.addEventListener("reset", () => {
    chargerOffres(); // Charger les offres sans filtres
  });

  chargerOffres(); // Charger les offres au chargement initial
});
