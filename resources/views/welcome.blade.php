<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Stock Mobile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .bottom-nav {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: white;
            box-shadow: 0 -2px 10px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .content-area {
            margin-bottom: 70px;
            padding-bottom: 20px;
        }

        .card {
            margin-bottom: 15px;
        }

        .stats-card {
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .table-responsive {
            max-height: 300px;
            overflow-y: auto;
        }

        .bottom-nav .dropdown-menu {
            width: 100vw;
            position: fixed;
            bottom: 70px;
            left: 0;
            margin: 0;
            padding: 15px;
            border-radius: 15px 15px 0 0;
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
            border: none;
        }

        .bottom-nav .dropdown-item {
            padding: 12px 15px;
            font-size: 1.1em;
            border-radius: 10px;
            margin-bottom: 5px;
        }

        .bottom-nav .dropdown-item:hover {
            background-color: #f8f9fa;
        }

        .bottom-nav .dropdown-item i {
            width: 25px;
            margin-right: 10px;
            color: #6c757d;
        }
    </style>
</head>

<body>
    <div class="container-fluid px-3 content-area">
        <!-- En-tête avec menu déroulant -->
        <div class="d-flex justify-content-between align-items-center py-3">
            <h2 class="h4 mb-0">Gestion Stock</h2>
            <div class="dropdown">
                <button class="btn btn-light" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-ellipsis-v"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="{{ route('ventes.pdf') }}">Exporter PDF</a></li>
                    <li><a class="dropdown-item" href="#" data-bs-toggle="modal"
                            data-bs-target="#filterModal">Filtres</a></li>
                </ul>
            </div>
        </div>

        <!-- Cartes statistiques -->
        <div class="row g-2">
            <div class="col-6">
                <div class="card bg-primary text-white stats-card">
                    <div class="card-body text-center">
                        <h6 class="card-title mb-0">Ventes</h6>
                        <strong>{{ number_format($totalVentes) }} f</strong>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card bg-success text-white stats-card">
                    <div class="card-body text-center">
                        <h6 class="card-title mb-0">Achats</h6>
                        <strong>{{ number_format($totalAchats) }} f</strong>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card bg-info text-white stats-card">
                    <div class="card-body text-center">
                        <h6 class="card-title mb-0">Bénéfices</h6>
                        <strong>{{ number_format($benefices) }} f</strong>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card bg-warning text-white stats-card">
                    <div class="card-body text-center">
                        <h6 class="card-title mb-0">Stock</h6>
                        <strong>{{ $nombreProduits }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sections Ventes et Achats -->
        <div class="mt-4">
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#ventes">Ventes</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#achats">Achats</button>
                </li>
            </ul>

            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="ventes">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Client</th>
                                    <th>Produit</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($dernieresVentes as $vente)
                                    <tr>
                                        <td>{{ $vente->date->format('d/m/Y') }}</td>
                                        <td>{{ $vente->client->nom }}</td>
                                        <td>
                                            @foreach ($vente->lignes as $ligne)
                                                {{ $ligne->produit->nom }} ({{ $ligne->quantite }})<br>
                                            @endforeach
                                        </td>
                                        <td>{{ number_format($vente->montant_total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="tab-pane fade" id="achats">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Fournisseur</th>
                                    <th>Produit</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($derniersAchats as $achat)
                                    <tr>
                                        <td>{{ $achat->date->format('d/m/Y') }}</td>
                                        <td>{{ $achat->fournisseur->nom }}</td>
                                        <td>{{ $achat->produit->nom }}</td>
                                        <td>{{ number_format($achat->montant_total) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.bottom-nav')

    <!-- Modal de filtres -->
    <div class="modal fade" id="filterModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Filtres</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="GET">
                        <div class="mb-3">
                            <label class="form-label">Période</label>
                            <select class="form-select" name="periode">
                                <option value="jour">Aujourd'hui</option>
                                <option value="semaine">Cette semaine</option>
                                <option value="mois">Ce mois</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Appliquer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://kit.fontawesome.com/yourcode.js"></script>
</body>

</html>
