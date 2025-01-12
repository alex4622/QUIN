<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Achats</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container-fluid px-3 pb-5 mb-5">
        <div class="d-flex justify-content-between align-items-center py-3">
            <h2 class="h4 mb-0">Achats</h2>
            <div>
                <a href="{{ route('achats.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nouvel Achat
                </a>
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
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un achat...">
        </div>

        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Fournisseur</th>
                        <th>Produit</th>
                        <th>Qte</th>
                        <th>P.U.</th>
                        <th>M.Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="achatsTable">
                    @foreach ($achats as $achat)
                        <tr>
                            <td>{{ $achat->created_at->format('Y-m-d') }}</td>
                            <td>{{ $achat->fournisseur->nom }}</td>
                            <td>{{ $achat->produit->nom }}</td>
                            <td>{{ $achat->quantite }}</td>
                            <td>{{ number_format($achat->prix_unitaire) }} f</td>
                            <td>{{ number_format($achat->montant_total) }} f</td>
                            <td>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editAchatModal{{ $achat->id }}">
                                    <i class="fas fa-edit"></i> 
                                </button>
                                <form action="{{ route('achats.destroy', $achat->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet achat ?')">
                                        <i class="fas fa-trash"></i> 
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @include('partials.bottom-nav')

    @foreach ($achats as $achat)
        <div class="modal fade" id="editAchatModal{{ $achat->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier Achat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('achats.update', $achat) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Fournisseur</label>
                                <select name="fournisseur_id" class="form-select" required>
                                    @foreach ($fournisseurs as $fournisseur)
                                        <option value="{{ $fournisseur->id }}"
                                            {{ $achat->fournisseur_id == $fournisseur->id ? 'selected' : '' }}>
                                            {{ $fournisseur->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Produit</label>
                                <select name="produit_id" class="form-select" required>
                                    @foreach ($produits as $produit)
                                        <option value="{{ $produit->id }}"
                                            {{ $achat->produit_id == $produit->id ? 'selected' : '' }}>
                                            {{ $produit->nom }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Quantité</label>
                                <input type="number" name="quantite" class="form-control"
                                    value="{{ $achat->quantite }}" required min="1">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Prix Unitaire</label>
                                <input type="number" name="prix_unitaire" class="form-control"
                                    value="{{ $achat->prix_unitaire }}" required min="0" step="0.01">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" class="form-control" value="{{ $achat->date }}"
                                    required>
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
            const rows = document.getElementById('achatsTable').getElementsByTagName('tr');

            for (let row of rows) {
                const date = row.getElementsByTagName('td')[0].textContent.toLowerCase();
                const fournisseur = row.getElementsByTagName('td')[1].textContent.toLowerCase();
                const produit = row.getElementsByTagName('td')[2].textContent.toLowerCase();
                const quantite = row.getElementsByTagName('td')[3].textContent.toLowerCase();
                const prixUnitaire = row.getElementsByTagName('td')[4].textContent.toLowerCase();
                const montantTotal = row.getElementsByTagName('td')[5].textContent.toLowerCase();

                if (date.includes(searchValue) ||
                    fournisseur.includes(searchValue) ||
                    produit.includes(searchValue) ||
                    quantite.includes(searchValue) ||
                    prixUnitaire.includes(searchValue) ||
                    montantTotal.includes(searchValue)) {
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
