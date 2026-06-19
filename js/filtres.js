document.addEventListener('DOMContentLoaded', function() {

    document.getElementById('btn-toggle-filtres').addEventListener('click', function() {
        var panel = document.getElementById('filtres-panel');
        panel.style.display = panel.style.display === 'none' ? 'block' : 'none';
    });
     
    document.getElementById('btn-filtrer').addEventListener('click', function() {
        var destination = document.getElementById('filtre-destination').value; 
        var prixMax = document.getElementById('filtre-prix-max').value;
        var duree = document.getElementById('filtre-duree').value;

        var url = BASE_URL + 'api/filtrer-sejours.php?';
        if (destination) url += 'destination=' + destination +'&';
        if (prixMax) url += 'prix_max=' + prixMax + '&';
        if (duree) url += 'duree=' + duree + '&';

        fetch(url)
            .then(function(response) { return response.json(); })
            .then(function(sejours) {
                var grille = document.getElementById('sejours-grid');
                grille.textContent = '';

                if (sejours.length === 0) {
                    var p =document.createElement('p');
                    p.className = 'text-center';
                    p.textContent = 'Aucun séjour trouvé.';
                    grille.appendChild(p);
                } else {
                    for (var i = 0; i < sejours.length; i++) {
                        var s = sejours[i];

                        var wrapper = document.createElement('div');
                        wrapper.className = 'sejour-wrapper';

                        var card = document.createElement('div');
                        card.className = 'sejour-card';

                        var img = document.createElement('img');
                        img.src = BASE_URL + 'images/' + s.image;
                        img.alt = s.titre;
                        img.className = 'sejour-image';
                        card.appendChild(img);

                        var lien = document.createElement('a');
                        lien.href = BASE_URL + 'pages/detail-sejour.php?id=' + s.sejour_id;
                        lien.className = 'btn btn-dark mt-2';
                        lien.textContent = 'Voir le détail';
                        card.appendChild(lien);  
                        
                        var titre = document.createElement('h3');
                        titre.className = 'sejour-titre';
                        titre.textContent = s.titre;
                        card.appendChild(titre);

                        var desc = document.createElement('p');
                        desc.textContent = s.description;
                        card.appendChild(desc);

                        var prix = document.createElement('p');
                        prix.className ='prix';
                        prix.textContent = s.prix_formate + ' € par personne, ' + s.duree_nuits + ' nuits.';
                        card.appendChild(prix);

                        wrapper.appendChild(card);
                        grille.appendChild(wrapper);
                    }
                }
            });

    });


document.getElementById('btn-reset').addEventListener('click', function() {
    document.getElementById('filtre-destination').value = '';
    document.getElementById('filtre-prix-max').value = '';
    document.getElementById('filtre-duree').value = '';
    document.getElementById('btn-filtrer').click();
   });

});