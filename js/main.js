// Carte interactive — uniquement sur la page contact
if (document.getElementById('map')) {
    const map = L.map('map').setView([48.8566, 2.3522], 11);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap'
    }).addTo(map);

    L.marker([48.8566, 2.3522])
        .addTo(map)
        .bindPopup('Zagiart Renov — Paris & Île-de-France')
        .openPopup();
}