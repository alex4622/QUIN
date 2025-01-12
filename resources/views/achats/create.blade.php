<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvel Achat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container-fluid px-3 pb-5 mb-5">
        <div class="d-flex justify-content-between align-items-center py-3">
            <h2 class="h4 mb-0">Nouvel Achat</h2>
            <button onclick="goBack()" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>

        <form action="{{ route('achats.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Fournisseur</label>
                <select name="fournisseur_id" class="form-select" required>
                    <option value="">Sélectionner un fournisseur</option>
                    @foreach ($fournisseurs as $fournisseur)
                        <option value="{{ $fournisseur->id }}">{{ $fournisseur->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Produit</label>
                <select name="produit_id" class="form-select" required id="produit">
                    <option value="">Sélectionner un produit</option>
                    @foreach ($produits as $produit)
                        <option value="{{ $produit->id }}" data-prix="{{ $produit->prix_achat }}">
                            {{ $produit->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Quantité</label>
                <input type="number" name="quantite" class="form-control" required min="1" id="quantite">
            </div>

            <div class="mb-3">
                <label class="form-label">Prix unitaire</label>
                <div class="input-group">
                    <input type="number" name="prix_unitaire" class="form-control" required step="0.01"
                        id="prix">
                    <span class="input-group-text">f</span>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Total</label>
                <div class="input-group">
                    <input type="text" class="form-control" id="total" readonly>
                    <span class="input-group-text">f</span>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Date</label>
                <input type="date" name="date" class="form-control" required value="{{ date('Y-m-d') }}">
            </div>

            <button type="submit" class="btn btn-primary w-100">Enregistrer l'achat</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('produit').addEventListener('change', updatePrix);
        document.getElementById('prix').addEventListener('input', updateTotal);
        document.getElementById('quantite').addEventListener('input', updateTotal);

        function updatePrix() {
            const select = document.getElementById('produit');
            const option = select.options[select.selectedIndex];
            const prix = option.dataset.prix || 0;
            document.getElementById('prix').value = prix;
            updateTotal();
        }

        function updateTotal() {
            const prix = parseFloat(document.getElementById('prix').value) || 0;
            const quantite = parseInt(document.getElementById('quantite').value) || 0;
            document.getElementById('total').value = (prix * quantite).toFixed(2);
        }
        function goBack() {
            if (document.referrer) {
                window.location.href = document.referrer;
            } else {
                window.location.href = "{{ route('dashboard') }}";
            }
        }
    </script>
</body>

</html>
