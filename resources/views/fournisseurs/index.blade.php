<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Fournisseurs</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>
    <div class="container-fluid px-3 pb-5 mb-5">
        <div class="d-flex justify-content-between align-items-center py-3">
            <h2 class="h4 mb-0">Fournisseurs</h2>
            <div>
                <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addFournisseurModal">
                    <i class="fas fa-plus"></i> Fournisseur
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

        <!-- Barre de recherche -->
        <div class="mb-3">
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher un fournisseur...">
        </div>

        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="fournisseursTable">
                    @foreach ($fournisseurs as $fournisseur)
                        <tr>
                            <td>{{ $fournisseur->nom }}</td>
                            <td>{{ $fournisseur->telephone }}</td>
                            <td>{{ $fournisseur->email }}</td>
                            <td>
                                <div class="d-flex gap-2">
                                    <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                        data-bs-target="#editFournisseurModal{{ $fournisseur->id }}">
                                        <i class="fas fa-edit"></i> 
                                    </button>
                                    <form action="{{ route('fournisseurs.destroy', $fournisseur->id) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Ajout Fournisseur -->
    <div class="modal fade" id="addFournisseurModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Nouveau Fournisseur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('fournisseurs.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Téléphone</label>
                            <input type="tel" name="telephone" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adresse</label>
                            <textarea name="adresse" class="form-control" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modals Modification Fournisseurs -->
    @foreach ($fournisseurs as $fournisseur)
        <div class="modal fade" id="editFournisseurModal{{ $fournisseur->id }}">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Modifier Fournisseur</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <form action="{{ route('fournisseurs.update', $fournisseur) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" name="nom" class="form-control" required
                                    value="{{ $fournisseur->nom }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Téléphone</label>
                                <input type="tel" name="telephone" class="form-control"
                                    value="{{ $fournisseur->telephone }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ $fournisseur->email }}">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Adresse</label>
                                <textarea name="adresse" class="form-control" rows="2">{{ $fournisseur->adresse }}</textarea>
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
        // Fonction de recherche
        document.getElementById('searchInput').addEventListener('keyup', function() {
            const searchValue = this.value.toLowerCase();
            const rows = document.getElementById('fournisseursTable').getElementsByTagName('tr');

            for (let row of rows) {
                const nom = row.getElementsByTagName('td')[0].textContent.toLowerCase();
                const tel = row.getElementsByTagName('td')[1].textContent.toLowerCase();
                const email = row.getElementsByTagName('td')[2].textContent.toLowerCase();

                if (nom.includes(searchValue) || tel.includes(searchValue) || email.includes(searchValue)) {
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
    @include('partials.bottom-nav')
</body>

</html>
