<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Rapport des ventes</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f5f5f5;
        }

        .total {
            text-align: right;
            font-weight: bold;
            margin-top: 20px;
        }

        ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        li {
            margin-bottom: 8px;
            padding: 5px 0;
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>Rapport des Ventes</h1>
        <p>Date: {{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Client</th>
                <th>Produits</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($ventes as $vente)
                <tr>
                    <td>{{ $vente->created_at->format('d/m/Y H:i:s') }}</td>
                    <td>{{ $vente->client->nom }}</td>
                    <td>
                        <ul>
                            @foreach ($vente->lignes as $ligne)
                                <li>
                                    {{ $ligne->produit->nom }} ({{ $ligne->quantite }}) &nbsp;&nbsp;|&nbsp;&nbsp;
                                    {{ number_format($ligne->quantite * $ligne->prix_unitaire) }} f
                                </li>
                            @endforeach
                        </ul>
                    </td>
                    <td>{{ number_format($vente->montant_total) }} f</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total">
        Total des ventes: {{ number_format($total, 2) }} f
    </div>
</body>

</html>
