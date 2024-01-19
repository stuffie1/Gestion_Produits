<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProduitsStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Unique;

class GestionProduitsController extends Controller
{
  
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $produits = session('produits',[]); 
        // dd($produits);
        return view('produits.index',['produits'=>$produits]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
 
        return view('produits.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProduitsStoreRequest $request)
    {
        $produits = session('produits',[]);
        array_push($produits,[
            'Id'=>uniqid(),
            'Libelle' => $request->libelle,
            'Marque' => $request->marque,
            'Prix' => $request->prix,
            'Stock' => $request->stock,
            'Image' => $request->file('image')->store('ProduitsImages','local'),
        ]);
        session(['produits' => $produits]);
        
        return redirect('/produits');
        // dd($request->file('image')->store('ProduitsImages','public'));
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $produits=session('produits',[]);
        foreach($produits as $produit){
             if($produit['Id']==$id){
                $infoproduit=$produit;
                return view("produits.show",['id'=>$id,'produit'=>$infoproduit]);
             }
             // dd($id);
             
            }
            return view('produits.error');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produits = session('produits',[]); 
        return view('produits.edit',compact('produits','id'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProduitsStoreRequest $request,  $id)
    {
        $produits = session('produits',[]);
        foreach($produits as $key => $produit){
             if($produit['Id']==$id){

                 $produits[$key]=[
                     'Id'=>$id,
                     'Libelle' => $request->libelle,
                     'Marque' => $request->marque,
                     'Prix' => $request->prix,
                     'Stock' => $request->stock,
                     'Image' => $request->file('image')->store('ProduitsImages','local'),
                 ];
             }
        }
        session(['produits' => $produits]);
        return redirect('/produits');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
       $produits = session('produits', []);

        foreach ($produits as $key => $produit) {
            if ($produit['Id'] == $id) {
                unset($produits[$key]);
                break;
            }
        }
        

        session(['produits'=>$produits]);
        // dd($produits);
        return redirect('/produits');
    }
}
