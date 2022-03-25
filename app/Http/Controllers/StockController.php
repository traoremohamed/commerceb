<?php

namespace App\Http\Controllers;

use App\Exports\UsersExport;
use App\Helpers\Crypt;
use App\Models\Commandefour;
use App\Models\Fournisseur;
use App\Models\LigneComfour;
use App\Models\ReceptionFour;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class StockController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Resultat = DB::table('vue_stock_prod_fam')
            ->select( 'num_prod','code_prod','code_barre_prod', 'lib_prod','lib_sousfam','lib_fam',
                    DB::raw('SUM(qte_sortie) as qte_sortie' ),
                    DB::raw('SUM(qte_entree) as qte_entree' ))
            ->groupBy('num_prod', 'lib_prod','code_prod', 'code_barre_prod','lib_sousfam','lib_fam' )
            ->get();
        /*
         * DB::table('vue_stock_prod')
            ->select( 'num_prod','code_prod','code_barre_prod', 'lib_prod',
                    DB::raw('SUM(qte_sortie) as qte_sortie' ),
                    DB::raw('SUM(qte_entree) as qte_entree' ))
            ->groupBy('num_prod', 'lib_prod','code_prod', 'code_barre_prod' )
            ->get();*/
        //return Excel::download($Resultat, 'stock.xlsx');

        return view('stockproduit.index', compact('Resultat'));
    }
    public function export()
    {
        $Resultat = DB::table('vue_stock_prod_fam')
            ->select( 'num_prod','code_prod','code_barre_prod', 'lib_prod','lib_sousfam','lib_fam',
                DB::raw('SUM(qte_sortie) as qte_sortie' ),
                DB::raw('SUM(qte_entree) as qte_entree' ))
            ->groupBy('num_prod', 'lib_prod','code_prod', 'code_barre_prod','lib_sousfam','lib_fam' )
            ->get();
        /*
         * DB::table('vue_stock_prod')
            ->select( 'num_prod','code_prod','code_barre_prod', 'lib_prod',
                    DB::raw('SUM(qte_sortie) as qte_sortie' ),
                    DB::raw('SUM(qte_entree) as qte_entree' ))
            ->groupBy('num_prod', 'lib_prod','code_prod', 'code_barre_prod' )
            ->get();*/
        return Excel::download(new UsersExport(), 'stock.xlsx');

        //return view('stockproduit.index', compact('Resultat'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request, $id = null)
    {
        $idCombr = Crypt::UrldeCrypt($id);

        $idAgceCon = Auth::user()->num_agce;
        $idUser = Auth::user()->id;

        if ($idCombr == null) {
            return redirect('/receptionfour');
        }
        $Receptionfour = DB::table('reception_four')
            ->join('commandefour', 'reception_four.num_comf', '=', 'commandefour.num_comf', 'inner')
            ->join('agence', 'reception_four.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('fournisseur', 'reception_four.num_fourn', '=', 'fournisseur.num_fourn', 'inner')
            ->where('num_br', '=', $idCombr)
            ->first();

        $flagValide = $Receptionfour->flag_br;
        $flagAnnule = $Receptionfour->annule_br;

        if (isset($idAgceCon)) {
            $Agence = DB::table('agence')
                ->where([['flag_agce', '=', true], ['num_agce', '=', $idAgceCon]])
                ->orderBy('lib_agce',)->get();
        } else {
            $Agence = DB::table('agence')
                ->where('flag_agce', '=', true)
                ->orderBy('lib_agce',)->get();
        }

        $AgenceList = "";
        $val = "";

        foreach ($Agence as $comp) {
            if ($comp->num_agce == $Receptionfour->num_agce) $val = 'selected ';
            $AgenceList .= "<option value='" . $comp->num_agce . "'  $val   >" . $comp->lib_agce . "</option>";
        }
        $TFournisseur = DB::table('fournisseur')
            ->where('flag_fourn', '=', true)
            ->orderBy('lib_fourn',)->get();
        $TFournisseurList = "<option value='' > Sélectionner </option>";
        foreach ($TFournisseur as $comp) {
            if ($comp->num_fourn == $Receptionfour->num_fourn) $val = 'selected ';
            $TFournisseurList .= "<option value='" . $comp->num_fourn . "'    $val  >" . $comp->lib_fourn . "</option>";
        }
        /******************* Mise a jour de la commande ****************************/
        if ($request->isMethod('post')) {
            $data = $request->all();

            if ($data['action'] === 'Valider') {
                ReceptionFour::where('num_br', '=', $idCombr)->update(['flag_br' => true]);
                return redirect('/receptionfour/edit/' . Crypt::UrlCrypt($idCombr))->with('success', 'Succes : Validation reussi ');
            }

            if ($data['action'] === 'Annuler') {
                ReceptionFour::where('num_br', '=', $idCombr)->update(['annule_br' => true]);
                return redirect('/receptionfour/edit/' . Crypt::UrlCrypt($idCombr))->with('success', 'Succes : Annulation reussi ');
            }
        }

        /******************** Mise a jour des produit **********************************/


        $ligneReceptionfour = DB::table('ligne_br')
            ->join('produit', 'ligne_br.num_prod', '=', 'produit.num_prod', 'inner')
            ->where('num_br', '=', $idCombr)
            ->get();

        return view('receptionfour.edit',
            compact(
                'TFournisseurList',
                'AgenceList',
                'flagValide',
                'flagAnnule', 'Receptionfour',  'ligneReceptionfour'
            ));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id = null)
    {

        $idLcomf= Crypt::UrldeCrypt($id);
        $ligneComfour = DB::table('ligne_br')
            ->where('num_lcomfour', '=', $idLcomf)
            ->first();
        DB::table('ligne_br')->where('num_lcomfour', $idLcomf)->delete();
         return redirect('/comfour/edit/' . Crypt::UrlCrypt($ligneComfour->num_comf))->with('success', 'Succes : Suppression reussi ');

    }

}
