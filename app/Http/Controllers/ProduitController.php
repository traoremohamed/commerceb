<?php

namespace App\Http\Controllers;

use App\Helpers\GenerateCode as Gencode;
use App\Models\Produit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $Resultat = DB::table('produit')
            ->join('sous_famille', 'produit.num_sousfam', '=', 'sous_famille.num_sousfam', 'inner')
            ->get();
        return view('produit.index', compact('Resultat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $TSfam = DB::table('sous_famille')
            ->where('flag_sousfam', '=', true)
            ->orderBy('lib_sousfam',)->get();
        $TSfamList = "<option value='' > Sélectionner </option>";
        foreach ($TSfam as $comp) {
            $TSfamList .= "<option value='" . $comp->num_sousfam . "'      >" . $comp->lib_sousfam . "</option>";
        }
        $codeProd = 'P-' . Gencode::randStrGen(4, 6);
        return view('produit.create', compact('TSfamList', 'codeProd'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'lib_prod' => 'required',
            'num_sousfam' => 'required'
        ]);
        if ($request->isMethod('post')) {
            Produit::create($request->all());
            return redirect()->route('produit.index')->with('success', 'Enregistrement reussi.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Produit $produit
     * @return \Illuminate\Http\Response
     */
    public function edit(Produit $produit)
    {

        //dd($produit->num_prod);

        $TSfam = DB::table('sous_famille')
            ->where('flag_sousfam', '=', true)
            ->orderBy('lib_sousfam',)->get();
        $TSfamList = "<option value='' > Sélectionner </option>";
        foreach ($TSfam as $comp) {
            $val ='';
            if ($comp->num_sousfam === $produit->num_sousfam) {
                $val = 'selected ';
            }
            $TSfamList .= "<option value='" . $comp->num_sousfam . "'    $val  >" . $comp->lib_sousfam . "</option>";
        }

        $recherches =  DB::select(
            DB::raw(
                'select * from mvt_stock_prod msp
                          inner join produit p on msp.num_prod = p.num_prod
                          where p.num_prod=:idprod
                        '),
                    array(
                        'idprod' => $produit->num_prod
                    )
                 );

        /*if(count($recherches)==0){

            $message = "Il y a eu de mouvement sr ce produit";
        }*/

        return view('produit.edit', compact('produit', 'TSfamList','recherches'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Produit $produit
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Produit $produit)
    {
        $request->validate([
            'lib_prod' => 'required',
            'num_sousfam' => 'required'
        ]);
        $produit->update($request->all());
        return redirect()->route('produit.index')->with('success', 'Mise à jour reussie.');
    }

}
