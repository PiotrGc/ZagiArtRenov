// Demande confirmation avant les actions destructrices (remplace les onclick="" en ligne,
// bloqués par la Content-Security-Policy).
document.querySelectorAll('form[data-confirmer]').forEach(function (form) {
    form.addEventListener('submit', function (e) {
        if (!confirm(form.dataset.confirmer)) {
            e.preventDefault();
        }
    });
});
