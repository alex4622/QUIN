<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Ventes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container-fluid px-3 pb-5 mb-5">
        <div class="d-flex justify-content-between align-items-center py-3">
            <h2 class="h4 mb-0">Ventes</h2>
            <div>
                <a href="{{ route('ventes.create') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-plus"></i> Nouvelle Vente
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
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher une vente...">
        </div>

        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Client</th>
                        <th>M. Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="ventesTable">
                    @foreach ($ventes as $vente)
                        <tr>
                            <td>{{ $vente->created_at->format('Y-m-d') }}</td>
                            <td>{{ $vente->client->nom }}</td>
                            <td>{{ number_format($vente->montant_total) }}</td>
                            <td>
                                <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#viewVenteModal{{ $vente->id }}">
                                    <i class="fas fa-eye"></i>
                                </button>
                                <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#editVenteModal{{ $vente->id }}">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form action="{{ route('ventes.destroy', $vente->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?')">
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

    @foreach ($ventes as $vente)
        <div class="modal fade" id="editVenteModal{{ $vente->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier Vente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('ventes.update', $vente) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label class="form-label">Client</label>
                                    <select name="client_id" class="form-select" required>
                                        @foreach ($clients as $client)
                                            <option value="{{ $client->id }}"
                                                {{ $vente->client_id == $client->id ? 'selected' : '' }}>
                                                {{ $client->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Date</label>
                                    <input type="date" name="date" class="form-control"
                                        value="{{ $vente->date }}" required>
                                </div>
                            </div>

                            <div id="produitsContainer{{ $vente->id }}">
                                @foreach ($vente->lignes as $index => $ligne)
                                    <div class="row mb-3 produit-ligne">
                                        <div class="col-md-5">
                                            <select name="produits[{{ $index }}][produit_id]"
                                                class="form-select" required>
                                                @foreach ($produits as $produit)
                                                    <option value="{{ $produit->id }}"
                                                        {{ $ligne->produit_id == $produit->id ? 'selected' : '' }}>
                                                        {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-5">
                                            <input type="number" name="produits[{{ $index }}][quantite]"
                                                class="form-control" value="{{ $ligne->quantite }}" required
                                                min="1" placeholder="Quantité">
                                        </div>
                                        <div class="col-md-2">
                                            <button type="button" class="btn btn-danger btn-sm remove-produit">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <button type="button" class="btn btn-secondary btn-sm"
                                onclick="ajouterProduit({{ $vente->id }})">
                                Ajouter un produit
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal de visualisation -->
        <div class="modal fade" id="viewVenteModal{{ $vente->id }}">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Détails de la Vente</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <p><strong>Client:</strong> {{ $vente->client->nom }}</p>
                                <p><strong>Date:</strong> {{ $vente->created_at->format('d/m/Y') }}</p>
                                <p><strong>Montant Total:</strong> {{ number_format($vente->montant_total) }} FCFA</p>
                            </div>
                        </div>

                        <h6 class="mt-4">Produits</h6>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Produit</th>
                                        <th>Quantité</th>
                                        <th>Prix unitaire</th>
                                        <th>Sous-total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($vente->lignes as $ligne)
                                        <tr>
                                            <td>{{ $ligne->produit->nom }}</td>
                                            <td>{{ $ligne->quantite }}</td>
                                            <td>{{ number_format($ligne->prix_unitaire) }}</td>
                                            <td>{{ number_format($ligne->quantite * $ligne->prix_unitaire) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.getElementById('ventesTable').getElementsByTagName('tr');

            for (let row of rows) {
                const date = row.getElementsByTagName('td')[0].textContent.toLowerCase();
                const client = row.getElementsByTagName('td')[1].textContent.toLowerCase();
                const montant = row.getElementsByTagName('td')[2].textContent.toLowerCase();

                if (date.includes(searchValue) ||
                    client.includes(searchValue) ||
                    montant.includes(searchValue)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            }
        });

        function ajouterProduit(venteId) {
            const container = document.getElementById('produitsContainer' + venteId);
            const index = container.children.length;

            const template = `
                <div class="row mb-3 produit-ligne">
                    <div class="col-md-5">
                        <select name="produits[${index}][produit_id]" class="form-select" required>
                            @foreach ($produits as $produit)
                                <option value="{{ $produit->id }}">
                                    {{ $produit->nom }} (Stock: {{ $produit->quantite_stock }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-5">
                        <input type="number" name="produits[${index}][quantite]"
                            class="form-control" required min="1" placeholder="Quantité">
                    </div>
                    <div class="col-md-2">
                        <button type="button" class="btn btn-danger btn-sm remove-produit">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>
            `;

            container.insertAdjacentHTML('beforeend', template);
        }

        document.addEventListener('click', function(e) {
            if (e.target.matches('.remove-produit') || e.target.closest('.remove-produit')) {
                const button = e.target.matches('.remove-produit') ? e.target : e.target.closest('.remove-produit');
                button.closest('.produit-ligne').remove();
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
