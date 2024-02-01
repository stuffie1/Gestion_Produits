<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>pdf</title>
</head>

<body>
  <div class="container p-5 " >
    <center>
        <h1 class="mb-5">Devis d'achat</h1>
    </center>
    <h6>Fournisseur : {{$produits['fournisseur']}}</h6>
    <h6>Address : {{$produits['adress']}}</h6>
    <h6>Date : {{$produits['date']}}</h6>
    <table class="table w-100 my-5 table-hover  w-75 mx-auto text-center">
        <tr class="bg-dark text-light">
            <th class="py-3">Nome de Produit</th>
            <th class="py-3">Quantity</th>
            <th class="py-3">Prix unitaire </th>
            <th class="py-3">Montant Total</th>
        </tr>
        @foreach ($produits['products'] as $produit)
            <tr class="">
                <td class="py-3">{{ $produit['produit_name'] }}</td>
                <td class="py-3">{{ $produit['quantity'] }}</td>
                <td class="py-3">{{ $produit['prix'] }} DH</td>
                <td class=" py-3">
                   {{$produit['quantity'] * $produit['prix'] }}DH

                </td>
            </tr>
        @endforeach
    </table>

    <p class="mt-5">Sout total : {{$produits['qt']}}MAD</p>
    <p>Frais de livraison : {{$produits['fl']}}MAD</p>
    <p>Remis : {{$produits['remise']}}MAD</p>
    <p>Montant Total (TTC) : {{$produits['remise']+$produits['fl']+$produits['qt']}}MAD</p>
    <h5 class="mt-3"><strong>Condition de paiment :</strong></h5>
    <p>Modalité de Paiement : Net 30 jours à partir de la date de la facture.</p>
    <p>Frais de Retard : 2 % par mois pour les paiements en retard.</p>
    <p>Méthodes de Paiement Acceptées : Virement Bancaire, Carte de Crédit.</p>
    <h5 class="mt-3"><strong>Validate de l'offre :</strong></h5>
    <p>Validité de l'Offre : 30 jours à partir de la date de la proposition.</p>
    <p>Remise pour Acceptation Rapide : 5 % si l'offre est acceptée dans les 15 jours.</p>
    
    <h5 class="mt-3"><strong>Remarques :</strong></h5>
    <p>Spécifications du Produit : Veuillez consulter le document joint pour des spécifications détaillées.</p>
    <p>Options de Personnalisation : Contactez notre équipe commerciale pour toute demande de personnalisation.</p>
    
  </div>
</body>

</html>
