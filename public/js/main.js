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

    function ouvrirFormulaire(focusChamp) {
        formulaire.hidden = false;
        btnToggle.textContent = 'Fermer le formulaire';
        btnToggle.setAttribute('aria-expanded', 'true');
        formulaire.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        // Place le focus dans le formulaire pour les utilisateurs clavier / lecteur d'écran
        if (focusChamp) {
            document.getElementById('avis_nom').focus({ preventScroll: true });
        }
    }

    function fermerFormulaire() {
        formulaire.hidden = true;
        btnToggle.textContent = 'Laisser un avis';
        btnToggle.setAttribute('aria-expanded', 'false');
        btnToggle.focus();
    }

    btnToggle.addEventListener('click', function () {
        formulaire.hidden ? ouvrirFormulaire(true) : fermerFormulaire();
    });

    if (btnAnnuler) {
        btnAnnuler.addEventListener('click', fermerFormulaire);
    }

    // Ouvrir automatiquement après soumission (paramètre GET injecté en data attribute)
    const body = document.body;
    if (body.dataset.avisEnvoye === '1' || body.dataset.avisErreur === '1') {
        ouvrirFormulaire(false);
    }
}

// ===========================
// COMPTEUR DE CARACTÈRES
// ===========================
if (textarea && compteur) {
    textarea.addEventListener('input', function () {
        const len = this.value.length;
        compteur.textContent = len;
        compteur.classList.toggle('compteur_limite', len >= 450);
    });
}

}); // fin DOMContentLoaded


// ===========================
// MENU BURGER MOBILE
// ===========================
const navBurger = document.getElementById('nav_burger');
const navLiens  = document.getElementById('nav_liens');

if (navBurger && navLiens) {
    function basculerMenu(ouvrir) {
        navLiens.classList.toggle('active', ouvrir);
        navBurger.classList.toggle('active', ouvrir);
        navBurger.setAttribute('aria-expanded', ouvrir ? 'true' : 'false');
        navBurger.setAttribute('aria-label', ouvrir ? 'Fermer le menu' : 'Ouvrir le menu');
    }

    navBurger.addEventListener('click', function () {
        basculerMenu(!navLiens.classList.contains('active'));
    });

    // Échap referme le menu et rend le focus au bouton
    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && navLiens.classList.contains('active')) {
            basculerMenu(false);
            navBurger.focus();
        }
    });
}

// ===========================
// CARROUSEL RÉALISATIONS + LIGHTBOX
// ===========================
(function () {
    const track           = document.querySelector('.galerie_track');
    const lightbox         = document.getElementById('lightbox');
    const lightboxImg      = document.getElementById('lightbox_img');
    const lightboxFermer   = document.getElementById('lightbox_fermer');
    const items             = document.querySelectorAll('.galerie_item');
    const btnPrev           = document.querySelector('.galerie_prev');
    const btnNext           = document.querySelector('.galerie_next');

    if (!track || items.length === 0) return; // pas de carrousel sur cette page

    let index = 0;

    function getGap() {
        return parseFloat(getComputedStyle(track).gap) || 0;
    }

    function getVisibleCount() {
        const viewportWidth = track.parentElement.getBoundingClientRect().width;
        const itemWidth = items[0].getBoundingClientRect().width;
        const gap = getGap();
        return Math.max(1, Math.round((viewportWidth + gap) / (itemWidth + gap)));
    }

    function update() {
        const visibles = getVisibleCount();
        const maxIndex = Math.max(0, items.length - visibles);
        index = Math.min(index, maxIndex);

        const itemWidth = items[0].getBoundingClientRect().width;
        const gap = getGap();
        track.style.transform = `translateX(-${index * (itemWidth + gap)}px)`;

        // Les photos hors champ sont retirées de l'ordre de tabulation et des lecteurs d'écran
        items.forEach((item, i) => {
            const visible = i >= index && i < index + visibles;
            item.querySelector('button').tabIndex = visible ? 0 : -1;
            item.setAttribute('aria-hidden', visible ? 'false' : 'true');
        });

        if (btnPrev) btnPrev.disabled = index === 0;
        if (btnNext) btnNext.disabled = index >= maxIndex;
    }

    if (btnPrev) {
        btnPrev.addEventListener('click', () => {
            index = Math.max(0, index - 1);
            update();
        });
    }

    if (btnNext) {
        btnNext.addEventListener('click', () => {
            const maxIndex = Math.max(0, items.length - getVisibleCount());
            index = Math.min(maxIndex, index + 1);
            update();
        });
    }

    window.addEventListener('resize', update);
    update();

    // Lightbox
    if (lightbox && lightboxImg && lightboxFermer) {
        let declencheur = null; // bouton qui a ouvert la lightbox, pour y rendre le focus

        function ouvrirLightbox(bouton) {
            const img = bouton.querySelector('img');
            declencheur = bouton;
            lightboxImg.src = img.src;
            lightboxImg.alt = img.alt;
            lightbox.hidden = false;
            lightbox.classList.add('active');
            document.body.classList.add('lightbox_ouverte');
            lightboxFermer.focus();
        }

        function fermerLightbox() {
            if (lightbox.hidden) return;
            lightbox.classList.remove('active');
            lightbox.hidden = true;
            document.body.classList.remove('lightbox_ouverte');
            if (declencheur) declencheur.focus();
        }

        items.forEach(item => {
            const bouton = item.querySelector('button');
            bouton.addEventListener('click', () => ouvrirLightbox(bouton));
        });

        lightboxFermer.addEventListener('click', fermerLightbox);

        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                fermerLightbox();
            }
        });

        document.addEventListener('keydown', (e) => {
            if (lightbox.hidden) return;
            if (e.key === 'Escape') {
                fermerLightbox();
            }
            // Le seul élément focusable de la lightbox est le bouton Fermer : on y garde le focus
            if (e.key === 'Tab') {
                e.preventDefault();
                lightboxFermer.focus();
            }
        });
    }
})();
