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


// ===========================
// MENU BURGER MOBILE
// ===========================
const navBurger = document.getElementById('nav_burger');
const navLiens  = document.getElementById('nav_liens');

if (navBurger && navLiens) {
    navBurger.addEventListener('click', function () {
        const isOpen = navLiens.classList.toggle('active');
        navBurger.classList.toggle('active');
        navBurger.setAttribute('aria-expanded', isOpen);
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
        items.forEach(item => {
            item.addEventListener('click', () => {
                const img = item.querySelector('img');
                lightboxImg.src = img.src;
                lightboxImg.alt = img.alt;
                lightbox.classList.add('active');
            });
        });

        lightboxFermer.addEventListener('click', () => {
            lightbox.classList.remove('active');
        });

        lightbox.addEventListener('click', (e) => {
            if (e.target === lightbox) {
                lightbox.classList.remove('active');
            }
        });

        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                lightbox.classList.remove('active');
            }
        });
    }
})();