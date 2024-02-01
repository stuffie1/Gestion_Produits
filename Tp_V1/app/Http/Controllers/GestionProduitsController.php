<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProduitsStoreRequest;
use App\Models\Cart;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Unique;
use App\Exports\ProduitsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ProduitsImport;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;


class GestionProduitsController extends Controller
{
  
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $produits = Produit::get(); 
        // dd($produits);
        if ($produits->isEmpty()) {
            return view('produits.index', ['produits' => null]);
        }
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
     
        //  $request->image=$request->file('image')->store('ProduitsImages','public');
         $produit=[
            'Libelle'=>$request->libelle,
            'Marque'=>$request->marque,
            'Prix'=>$request->prix,
            'Stock'=>$request->stock,
            'Image'=>$request->hasFile('image')?$request->file('image')->store('images','public'):null,
         ];
        // dd($produit);
            $produits = Produit::create($produit);
            $last=Produit::latest()->first();
            return redirect()->route('produits.show',['produit'=>$last->id])->with('message_created',"the produit  created successfuly");

        
        
        // dd($request->file('image')->store('ProduitsImages','public'));
    }

    /**
     * Display the specified resource.
     */
    public function show( $id)
    {
        $produit=Produit::find($id);
        if($produit){

            return view('produits.show',compact('produit'));
        }
        return view('produits.error');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $produit=Produit::find($id);
        if($produit){

            return view('produits.edit',compact('produit'));
        }
        return view('produits.error');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProduitsStoreRequest $request,  $id)
    {
        $produit=Produit::find($id);
        if(!$produit){
            return view('produits.error');
 
        }
        $produitedit=[
            'Libelle'=>$request->libelle,
            'Marque'=>$request->marque,
            'Prix'=>$request->prix,
            'Stock'=>$request->stock,
            'Image'=>$request->hasFile('image')? $request->file('image')->store('images','public'):$request->old_image,
         ];
        $produit->update($produitedit);
        return redirect()->route('produits.show',['produit'=>$id])->with('message_updated',"the produit updated successfuly");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( $id)
    {
       $produit = Produit::find($id);
       if ($produit){
           $produit->delete($id);
           return redirect()->route('produits.index')->with('message_deleted',"the produit deleted successfuly");
        }
        

    }
    public function export() 
    {
        
        return Excel::download(new ProduitsExport, 'produits.xlsx');
    }


    public function import(Request $request) 
    {
        Excel::import(new ProduitsImport,$request->file('exel'));
        
        return redirect('/produits')->with('success', 'All good!');
    }
    public function acceuil() 
    {
        $produits = Produit::where('Stock','>',0)->get(); 
        if ($produits->isEmpty()) {
            return view('acceuil', ['produits' => null]);
        }
        return view('acceuil',['produits'=>$produits]);
    }
    public function cart() 
    {
        $cart = Cart::get(); 
        $produits=[];
        if ($cart->isEmpty()) {
            return view('cart', ['produits' => null]);
        }
        // dd($cart);
        foreach($cart as $produit){
            $product=Produit::find($produit['produit_id']);
            $product['quantity']=$produit['quantity'];
            array_push($produits,$product);

        }
        return view('cart',['produits'=>$produits]);
    }
    public function addtocart(Request $request) 
    {
        $produit = Produit::findOrFail($request->produit); 
        $cart = Cart::where('produit_id', $produit->id)->first();
                if(!$cart){
            Cart::create([
                 'quantity'=>1,
                 'produit_id'=>$produit->id
            ]);
        }else{
            $cart->update([
                'quantity'=>$cart->quantity+1,
            ]);
        }
        $produit->update([
            'Stock'=>$produit['Stock']-1
        ]);
        return redirect()->route('produits.cart');
    }
    public function deletefromcart($id){
        $cart = Cart::where('produit_id',$id)->first();
        $produit = Produit::where('id', $cart['produit_id'])->first();
        $produit->update([
            'Stock'=>$produit->Stock+$cart->quantity
        ]);
        $cart->forcedelete();
        return redirect()->back();
    }
    public function deleteallfromcart(){
        $cart = Cart::get();
        foreach($cart as $produit){
            $product = Produit::find($produit['produit_id']);
            $product->update([
              'Stock'=>$product->Stock+$produit->quantity
            ]);

        }
        DB::table('carts')->truncate();
        return redirect()->route('produits.acceuil');
    }
    public function downloadAll() {
        $qt=0;
        $data=[
            'fournisseur'=>'Omar Elmehammedy',
            'adress'=>'ofppt-ismontic',
            'date'=>now(),
            'fl'=>20,
            'remise'=>10,
        ];
        $items=[];
        $cart=Cart::get();
        foreach($cart as $product){
             $produit=Produit::find($product->produit_id);
             array_push($items,[
                'produit_name'=>$produit->Libelle,
                'quantity'=>$product->quantity,
                'prix'=>$produit->Prix,
             ]);
             $qt=$qt+($product->quantity*$produit->Prix);
        };

        $data['qt']=$qt;
        $data['products']=$items;
        $pdf = Pdf::loadView('pdf',['produits' => $data]);
        DB::table('carts')->truncate();
        return $pdf->download();
    }
    public function download($id) {
        $qt=0;
        $data=[
            'fournisseur'=>'Omar Elmehammedy',
            'adress'=>'ofppt-ismontic',
            'date'=>now(),
            'fl'=>20,
            'remise'=>10,
        ];
        $items=[];
        $cart=Cart::where('produit_id',$id)->first();
        
             $produit=Produit::find($id);
             array_push($items,[
                'produit_name'=>$produit->Libelle,
                'quantity'=>$cart->quantity,
                'prix'=>$produit->Prix,
             ]);
             $qt=$qt+($cart->quantity*$produit->Prix);
       

        $data['qt']=$qt;
        $data['products']=$items;
        $pdf = Pdf::loadView('pdf',['produits' => $data]);
        $cart->forcedelete();
        return $pdf->download();
    }
    
}

