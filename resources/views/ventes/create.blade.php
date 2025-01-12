<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Vente</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container-fluid px-3">
        <div class="d-flex justify-content-between align-items-center py-3">
            <h2 class="h4 mb-0">Nouvelle Vente</h2>
            <button onclick="goBack()" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('ventes.store') }}" method="POST" id="venteForm">
            @csrf
            <div class="mb-3">
                <label class="form-label">Client</label>
                <select name="client_id" class="form-select" required>
                    <option value="">Sélectionner un client</option>
                    @foreach ($clients as $client)
                        <option value="{{ $client->id }}">{{ $client->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" value="{{ date('Y-m-d') }}" required>
            </div>

            <div id="produits-container">
                <!-- Les lignes de produits seront ajoutées ici -->
            </div>

            <button type="button" class="btn btn-outline-primary w-100 mb-3" onclick="ajouterProduit()">
                <i class="fas fa-plus"></i> Ajouter un produit
            </button>

            <button type="submit" class="btn btn-primary w-100">Enregistrer la vente</button>
        </form>

        @if (session('error'))
            <div class="alert alert-danger mb-3">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-3">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>

    <!-- Template pour une ligne de produit -->
    <template id="ligne-produit-template">
        <div class="card mb-3 ligne-produit">
            <div class="card-body">
                <div class="d-flex justify-content-between mb-2">
                    <h6 class="card-title">Produit</h6>
                    <button type="button" class="btn btn-outline-danger btn-sm" onclick="supprimerProduit(this)">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
                <div class="mb-2">
                    <select name="produits[0][produit_id]" class="form-select produit-select" required
                        onchange="updatePrixUnitaire(this)">
                        <option value="">Sélectionner un produit</option>
                        @foreach ($produits as $produit)
                            <option value="{{ $produit->id }}" data-prix="{{ $produit->prix_vente }}">
                                {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="mb-2">
                            <label class="form-label">Quantité</label>
                            <input type="number" name="produits[0][quantite]" class="form-control quantite" required
                                min="1" onchange="updateMontant(this)">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-2">
                            <label class="form-label">Prix unitaire</label>
                            <input type="text" class="form-control prix-unitaire" readonly>
                        </div>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label">Montant</label>
                    <input type="text" class="form-control montant" readonly>
                </div>
            </div>
        </div>
    </template>

    <script>
        function ajouterProduit() {
            const template = document.getElementById('ligne-produit-template');
            const container = document.getElementById('produits-container');
            const clone = template.content.cloneNode(true);

            // Mettre à jour les indices
            const index = container.children.length;
            clone.querySelectorAll('[name^="produits[0]"]').forEach(input => {
                input.name = input.name.replace('produits[0]', `produits[${index}]`);
            });

            container.appendChild(clone);
        }

        function supprimerProduit(button) {
            button.closest('.ligne-produit').remove();
        }

        function updatePrixUnitaire(select) {
            const ligne = select.closest('.ligne-produit');
            const prix = select.options[select.selectedIndex].dataset.prix;
            ligne.querySelector('.prix-unitaire').value = prix;
            updateMontant(select);
        }

        function updateMontant(element) {
            const ligne = element.closest('.ligne-produit');
            const quantite = ligne.querySelector('.quantite').value;
            const prix = ligne.querySelector('.prix-unitaire').value;
            const montant = quantite * prix;
            ligne.querySelector('.montant').value = montant ? montant.toFixed(2) : '';
        }

        // Ajouter une première ligne de produit au chargement
        document.addEventListener('DOMContentLoaded', function() {
            ajouterProduit();
        });
        function goBack() {
            if (document.referrer) {
                window.location.href = document.referrer;
            } else {
                window.location.href = "{{ route('dashboard') }}";
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
