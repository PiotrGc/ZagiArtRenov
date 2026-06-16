document.addEventListener('DOMContentLoaded', function () {

// ===========================
// TOGGLE FORMULAIRE AVIS
// ===========================
const btnToggle  = document.getElementById('btn_toggle_avis');
const btnAnnuler = document.getElementById('btn_annuler_avis');
const formulaire = document.getElementById('avis_formulaire');
const textarea   = document.getElementById('avis_commentaire');
const compteur   = document.getElementById('compteur_actuel');

if (btnToggle && formulaire) {

    function ouvrirFormulaire() {
        formulaire.style.display = 'block';
        btnToggle.textContent = '— Fermer';
        formulaire.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function fermerFormulaire() {
        formulaire.style.display = 'none';
        btnToggle.textContent = '+ Laisser un avis';
    }

    btnToggle.addEventListener('click', function () {
        formulaire.style.display === 'none' ? ouvrirFormulaire() : fermerFormulaire();
    });

    if (btnAnnuler) {
        btnAnnuler.addEventListener('click', fermerFormulaire);
    }

    // Ouvrir automatiquement après soumission (paramètre GET injecté en data attribute)
    const body = document.body;
    if (body.dataset.avisEnvoye === '1' || body.dataset.avisErreur === '1') {
        ouvrirFormulaire();
    }
}

// ===========================
// COMPTEUR DE CARACTÈRES
// ===========================
if (textarea && compteur) {
    textarea.addEventListener('input', function () {
        const len = this.value.length;
        compteur.textContent = len;
        if (len >= 480) {
            compteur.style.color = '#ef4444';
        } else if (len >= 400) {
            compteur.style.color = '#f59e0b';
        } else {
            compteur.style.color = '';
        }
    });
}

// ===========================
// CARTE LEAFLET (page contact)
// ===========================
if (document.getElementById('map')) {
    const map = L.map('map').setView([48.8566, 2.3522], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    L.marker([48.8566, 2.3522])
        .addTo(map)
        .bindPopup('ZAR — Paris & Île-de-France')
        .openPopup();
}

// ===========================
// MASQUAGE MESSAGE CONFIRMATION
// ===========================
const message = document.getElementById('message_confirmation');
if (message) {
    setTimeout(function () {
        message.style.transition = 'opacity 0.4s ease';
        message.style.opacity = '0';
        setTimeout(function () { message.style.display = 'none'; }, 400);
    }, 4000);
}

}); // fin DOMContentLoaded
