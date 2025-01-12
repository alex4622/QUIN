<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Stocks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container-fluid px-3 pb-5 content-area">
        <div class="d-flex justify-content-between align-items-center py-3">
            <h2 class="h4 mb-0">Gestion des Stocks</h2>
            <button onclick="goBack()" class="btn btn-outline-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($produitsAlerte->count() > 0)
            <div class="alert alert-warning">
                <h5 class="alert-heading">⚠️ Alertes de stock bas</h5>
                <ul class="mb-0">
                    @foreach ($produitsAlerte as $produit)
                        <li>{{ $produit->nom }} ({{ $produit->quantite_stock }} restants)</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un produit...">
        </div>

        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Produit</th>
                        <th>Stock actuel</th>
                        <th>Stock min</th>
                        <th>Prix achat</th>
                        <th>Prix vente</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="stockTable">
                    @foreach ($produits as $produit)
                        <tr class="{{ $produit->quantite_stock <= $produit->stock_minimum ? 'table-warning' : '' }}">
                            <td>{{ $produit->nom }}</td>
                            <td>{{ $produit->quantite_stock }}</td>
                            <td>{{ $produit->stock_minimum }}</td>
                            <td>{{ number_format($produit->prix_achat, 2) }}</td>
                            <td>{{ number_format($produit->prix_vente, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#ajusterModal{{ $produit->id }}">
                                    <i class="fas fa-edit"></i> 
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.bottom-nav')

    <!-- Modals Ajustement Stock -->
    @foreach ($produits as $produit)
        <div class="modal fade" id="ajusterModal{{ $produit->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Ajuster le stock - {{ $produit->nom }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('stocks.ajuster', $produit) }}" method="POST">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Type d'opération</label>
                                <select name="type" class="form-select" required>
                                    <option value="ajout">Ajouter au stock</option>
                                    <option value="retrait">Retirer du stock</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Quantité</label>
                                <input type="number" name="quantite" class="form-control" required min="1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stock minimum</label>
                                <input type="number" name="stock_minimum" class="form-control"
                                    value="{{ $produit->stock_minimum }}" min="0">
                                <small class="text-muted">Seuil d'alerte de stock bas</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.getElementById('stockTable').getElementsByTagName('tr');

            for (let row of rows) {
                const nom = row.getElementsByTagName('td')[0].textContent.toLowerCase();
                if (nom.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

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
