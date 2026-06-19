document.addEventListener('DOMContentLoaded', function() {

    var nbPersonnes = document.getElementById('nb_personnes');
    var totalEstime = document.getElementById('total_estime');

    function calculerTotal() {
        var nb = parseInt(nbPersonnes.value) || 1;
        var total = PRIX_PERSONNE * nb;

        // Réduction 10% si 5 personnes ou plus
        if (nb >= 5) {
            total = total * 0.90;
        }

        totalEstime.value = total.toFixed(2).replace('.', ',') + ' €';
    }

    // Calculer dès le chargement
    calculerTotal();

    // Recalculer à chaque changement
    nbPersonnes.addEventListener('input', function() {
        calculerTotal();
    });
});