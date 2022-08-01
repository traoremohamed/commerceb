<?php

namespace App\Http\Controllers;

use App\Helpers\Crypt;
use App\Models\BonLivraison;
use App\Models\Commandefour;
use App\Models\Client;
use App\Models\LigneBl;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BonlivraisonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $Resultat = DB::select(
            DB::raw(
                "
               select * from bon_livraison bl
                inner join commandeclient c on bl.num_comc = c.num_comc
                inner join agence a on bl.num_agce = a.num_agce
                inner join client c2 on bl.num_cli = c2.num_cli
                where left (c.code_comc, 1) = 'B' and bl.flag_bl = false
                      "),
            array(

            )

        );

        /*DB::table('bon_livraison as bl')
                 ->select('bl.flag_bl','bl.annule_bl', 'bl.num_bl','code_cli','client.nom_cli','client.prenom_cli',
                     'bl.num_comc' ,'agence.lib_agce','bl.date_cre_bl','bl.date_val_bl','bl.prix_ttc_bl','code_comc')
            ->join('commandeclient', 'bl.num_comc', '=', 'commandeclient.num_comc', 'inner')
            ->join('agence', 'bl.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('client', 'bl.num_cli', '=', 'client.num_cli', 'inner')
            ->get();*/
        return view('bonlivraison.index', compact('Resultat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */

    public function edit(Request $request, $id = null)
    {
        $idCombl = Crypt::UrldeCrypt($id);

        if ($idCombl == null) {
            return redirect('/bonlivraison');
        }



        /******************* Mise a jour de la commande ****************************/
        if ($request->isMethod('post')) {
           // exit('test');
            $data = $request->all();

            if ($data['action'] === 'Valider') {
                BonLivraison::where('num_bl', '=', $idCombl)->update(['flag_bl' => true]);
                return redirect('/bonlivraison/edit/' . Crypt::UrlCrypt($idCombl))->with('success', 'Succes : Validation reussi ');
            }

            if ($data['action'] === 'Annuler') {
                BonLivraison::where('num_bl', '=', $idCombl)->update(['annule_bl' => true]);
                return redirect('/bonlivraison/edit/' . Crypt::UrlCrypt($idCombl))->with('success', 'Succes : Annulation reussi ');
            }

            if ($data['action'] === 'Modifier') {

                $ligneResult = DB::table('ligne_bl')
                    ->join('produit', 'ligne_bl.num_prod', '=', 'produit.num_prod', 'inner')
                    ->where('num_bl', '=', $idCombl)
                    ->get();

                foreach($ligneResult as $re):

                    $form =  $data["num_lbl/$re->code_prod"];
                    $form1 =  $data["qte_lbl/$re->code_prod"];

                    LigneBl::where('num_lbl', '=', $form)->update(['qte_lbl' => $form1]);

                endforeach;


                //BonLivraison::where('num_bl', '=', $idCombl)->update(['flag_bl' => true]);
                return redirect('/bonlivraison/edit/' . Crypt::UrlCrypt($idCombl))->with('success', 'Succes : Validation reussi ');
            }
        }

        /******************** Mise a jour des produit **********************************/

        $Result = DB::table('bon_livraison as bl')
            ->select('bl.flag_bl','bl.annule_bl','bl.num_bl','code_cli','client.nom_cli','client.prenom_cli',
                'bl.num_comc' , 'bl.num_fact' ,'agence.lib_agce','bl.date_cre_bl','bl.date_val_bl','bl.prix_ttc_bl')
            ->join('commandeclient', 'bl.num_comc', '=', 'commandeclient.num_comc', 'inner')
            ->join('agence', 'bl.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('client', 'bl.num_cli', '=', 'client.num_cli', 'inner')
            ->where('num_bl', '=', $idCombl)
            ->first();

        $flagValide = $Result->flag_bl;
        $flagAnnule = $Result->annule_bl;

        $ligneResult = DB::table('ligne_bl')
            ->join('produit', 'ligne_bl.num_prod', '=', 'produit.num_prod', 'inner')
            ->where('num_bl', '=', $idCombl)
            ->get();

        return view('bonlivraison.edit',
            compact(
                'flagValide',
                'flagAnnule', 'Result',  'ligneResult'
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
        $ligneComfour = DB::table('ligne_bl')
            ->where('num_lcomfour', '=', $idLcomf)
            ->first();
        DB::table('ligne_bl')->where('num_lcomfour', $idLcomf)->delete();
         return redirect('/comfour/edit/' . Crypt::UrlCrypt($ligneComfour->num_comc))->with('success', 'Succes : Suppression reussi ');

    }



    public function etat(Request $request, $id = null)
    {
        $idCombl = Crypt::UrldeCrypt($id);

        if ($idCombl == null) {
            return redirect('/bonlivraison');
        }
        $Result = DB::table('bon_livraison as bl')
            ->select('bl.flag_bl','bl.annule_bl','bl.num_bl','code_cli','client.nom_cli','client.prenom_cli','client.tel_cli','client.cel_cli','client.adresse_geo_cli',
                'bl.num_comc' , 'cpte_contr_cli','bl.num_fact' ,'agence.lib_agce','bl.date_cre_bl','bl.date_val_bl','bl.prix_ttc_bl','bl.prix_ht_bl','bl.prix_tva_bl')
            ->join('commandeclient', 'bl.num_comc', '=', 'commandeclient.num_comc', 'inner')
            ->join('agence', 'bl.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('client', 'bl.num_cli', '=', 'client.num_cli', 'inner')
            ->where('num_bl', '=', $idCombl)
            ->first();

        $flagValide = $Result->flag_bl;
        $flagAnnule = $Result->annule_bl;



        $ligneResult = DB::table('ligne_bl')
            ->join('produit', 'ligne_bl.num_prod', '=', 'produit.num_prod', 'inner')
            ->where('num_bl', '=', $idCombl)
            ->get();

        return view('bonlivraison.etat',
            compact(
                'flagValide',
                'flagAnnule', 'Result',  'ligneResult'
            ));
    }


}
