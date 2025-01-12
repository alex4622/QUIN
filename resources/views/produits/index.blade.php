<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Produits</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container-fluid px-3 pb-5 content-area">
        <div class="d-flex justify-content-between align-items-center py-3">
            <h2 class="h4 mb-0">Produits</h2>
            <div>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProduitModal">
                    <i class="fas fa-plus"></i> Nouveau Produit
                </button>
                <button onclick="goBack()" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Retour
                </button>
            </div>

        </div>

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un produit...">
        </div>

        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Stock</th>
                        <th>Prix Achat</th>
                        <th>Prix Vente</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="produitsTable">
                    @foreach ($produits as $produit)
                        <tr>
                            <td>{{ $produit->nom }}</td>
                            <td>{{ $produit->quantite_stock }}</td>
                            <td>{{ number_format($produit->prix_achat, 2) }}</td>
                            <td>{{ number_format($produit->prix_vente, 2) }}</td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editProduitModal{{ $produit->id }}">
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

    <!-- Modal Ajout Produit -->
    <div class="modal fade" id="addProduitModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau Produit</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('produits.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prix d'achat</label>
                            <input type="number" name="prix_achat" class="form-control" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Prix de vente</label>
                            <input type="number" name="prix_vente" class="form-control" step="0.01" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock initial</label>
                            <input type="number" name="quantite_stock" class="form-control" required min="0">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Stock minimum</label>
                            <input type="number" name="stock_minimum" class="form-control" required min="0">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modals Modification Produits -->
    @foreach ($produits as $produit)
        <div class="modal fade" id="editProduitModal{{ $produit->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier Produit</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('produits.update', $produit) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" name="nom" class="form-control" required
                                    value="{{ $produit->nom }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prix d'achat</label>
                                <input type="number" name="prix_achat" class="form-control" step="0.01" required
                                    value="{{ $produit->prix_achat }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prix de vente</label>
                                <input type="number" name="prix_vente" class="form-control" step="0.01" required
                                    value="{{ $produit->prix_vente }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input type="number" name="quantite_stock" class="form-control" required
                                    min="0" value="{{ $produit->quantite_stock }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Stock minimum</label>
                                <input type="number" name="stock_minimum" class="form-control" required
                                    min="0" value="{{ $produit->stock_minimum }}">
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
            const rows = document.getElementById('produitsTable').getElementsByTagName('tr');

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
