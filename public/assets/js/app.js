// 1. Modale de suppression : copie le code et la désignation lus dans les attributs data-*
const modalSupprimer = document.getElementById('modalSupprimer');
if (modalSupprimer) {
    modalSupprimer.addEventListener('show.bs.modal', function (event) {
        const bouton = event.relatedTarget;
        document.getElementById('modalCode').value = bouton.dataset.code;
        document.getElementById('modalDesignation').textContent = bouton.dataset.designation;
    });
}

// 2. Recherche en direct : fetch() vers l'API JSON (?controller=api&action=produits)
const grille = document.getElementById('grille');
const champ = document.getElementById('recherche');
const filtre = document.getElementById('filtreCat');

if (grille && champ) {
    let minuteur;
    const lancer = () => {
        clearTimeout(minuteur);
        minuteur = setTimeout(async () => {
            const params = new URLSearchParams({ q: champ.value, cat: filtre.value });
            const reponse = await fetch(grille.dataset.api + '&' + params);
            afficher(await reponse.json());
        }, 250);   // attend 250 ms après la dernière frappe
    };
    champ.addEventListener('input', lancer);
    filtre.addEventListener('change', lancer);

    // textContent (jamais innerHTML) : aucune faille XSS côté JavaScript
    function afficher(produits) {
        grille.replaceChildren();
        if (produits.length === 0) {
            const p = document.createElement('p');
            p.className = 'text-muted';
            p.textContent = 'Aucun produit trouvé.';
            grille.append(p);
            return;
        }
        for (const p of produits) {
            const col = document.createElement('div');
            col.className = 'col-sm-6 col-lg-4';
            const stock = p.qte > 0 ? 'En stock (' + p.qte + ')' : 'Rupture de stock';

            const card = document.createElement('div');
            card.className = 'card h-100 shadow-sm';
            const body = document.createElement('div');
            body.className = 'card-body';
            const badge = document.createElement('span');
            badge.className = 'badge text-bg-secondary mb-2';
            badge.textContent = p.categorie;
            const titre = document.createElement('h5');
            titre.className = 'card-title';
            titre.textContent = p.designation;
            const prix = document.createElement('p');
            prix.className = 'fs-4 mb-1';
            prix.textContent = p.prix + ' DT';
            const st = document.createElement('p');
            st.className = (p.qte > 0 ? 'text-success' : 'text-danger') + ' small mb-0';
            st.textContent = stock;
            body.append(badge, titre, prix, st);

            const footer = document.createElement('div');
            footer.className = 'card-footer bg-white border-0';
            const form = document.createElement('form');
            form.method = 'post';
            form.action = grille.dataset.action;
            for (const [nom, valeur] of [['csrf_token', grille.dataset.csrf], ['code', p.code]]) {
                const input = document.createElement('input');
                input.type = 'hidden'; input.name = nom; input.value = valeur;
                form.append(input);
            }
            const bouton = document.createElement('button');
            bouton.className = 'btn btn-primary w-100';
            bouton.textContent = 'Ajouter au panier';
            bouton.disabled = p.qte <= 0;
            form.append(bouton);
            footer.append(form);

            card.append(body, footer);
            col.append(card);
            grille.append(col);
        }
    }
}

// 3. Graphique du tableau de bord (Chart.js)
const graphe = document.getElementById('grapheCA');
if (graphe && window.Chart) {
    new Chart(graphe, {
        type: 'bar',
        data: {
            labels: JSON.parse(graphe.dataset.labels),
            datasets: [{ label: "Chiffre d'affaires (DT)", data: JSON.parse(graphe.dataset.values), backgroundColor: '#0d6efd' }]
        },
        options: { plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true } } }
    });
}
