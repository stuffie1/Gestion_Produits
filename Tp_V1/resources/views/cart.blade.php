<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>Document</title>
</head>

<body>
    <nav class="navbar bg-dark text-light px-5 py-3  mb-5">
        <a class="navbar-brand text-white" href="/produits">Gestion de Produits</a>
        <a class="nav-link text-white btn btn-info" href="{{ route('produits.cart') }}">Cart</a>
    </nav>
    @if (!$produits)
        <div class="w-100 my-5">
            <h1 class="text-secondary mx-auto col-4">Theire is no Items yet</h1>
        </div>
    @else
        <div class="container col-9 px-5 mt-4 d-flex justify-content-between">
            <h1 class="mx-5">Panier</h1>
            <form action="{{route('produits.deleteallfromcart')}}" method="POST">
                @method('DELETE')
                @csrf
                <button type="submit" class="mt-3 btn btn-danger me-2">Delete All</button>
            </form>
        </div>
        <div class="container w-100 mt-4">
            @foreach ($produits as $produit)
                <div class="col-9 py-3 mb-5 d-flex mx-auto justify-content-between">
                    <div class="d-flex">
                        <div
                            style="height:60px;width:60px;background-repeat:no-repeat;background-size:cover;background-image: url({{ asset('storage/' . $produit['Image']) }})">
                        </div>
                        <div>
                            <h3 class="text-dark mx-5">{{ $produit['Libelle'] }}</h3>
                            <div class="d-flex mx-5">
                                <p class="text-secondary ">{{ $produit['Prix'] }} DH</p>
                                <p class="text-secondary ms-2">Quantity :{{ $produit['quantity'] }} </p>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between">
                        <form action="{{ route('produits.deletefromcart', ['id' => $produit->id]) }}" method="POST">
                            @method('DELETE')
                            @csrf
                            <button type="submit" class="mt-3 btn btn-outline-danger me-2">Delete</button>
                        </form>
                        <form action="{{ route('produits.devisone', ['id' => $produit->id]) }}"method="POST" >
                            @csrf
                            <button type="submit" class=" mt-3  btn btn-outline-success text- me-2">Buy now</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center">
               
               <a class=" mt-3  btn btn-success text- me-2" href="{{route('produits.devis')}}">Buy all now</a>
        </div>
    @endif
</body>

</html>
