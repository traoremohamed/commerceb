<?php

namespace App\Http\Controllers;

use App\Helpers\Crypt;
use App\Models\BonLivraison;
use App\Models\Commandefour;
use App\Models\Client;
use App\Models\Reglement;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class FactureController extends Controller
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
               select * from facture f
                inner join bon_livraison bl on f.num_bl = bl.num_bl
                inner join commandeclient c on bl.num_comc = c.num_comc
                inner join agence a on bl.num_agce = a.num_agce
                inner join client c2 on bl.num_cli = c2.num_cli
                where left (c.code_comc, 1) = 'B' and f.solde_fact = false
                      "),
            array(

            )

        );

        /*DB::table('facture as fa')
                 ->select('fa.flag_fact','fa.annule_fact', 'bl.num_bl','code_cli','client.nom_cli','client.prenom_cli',
                     'fa.code_fact', 'fa.num_fact' ,'agence.lib_agce','fa.date_cre_fact','fa.date_val_fact','fa.prix_ttc_fact','code_comc')
            ->join('bon_livraison as bl', 'fa.num_bl', '=', 'bl.num_bl', 'inner')
            ->join('commandeclient', 'bl.num_comc', '=', 'commandeclient.num_comc', 'inner')
            ->join('agence', 'fa.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('client', 'fa.num_cli', '=', 'client.num_cli', 'inner')
            ->get();*/
        return view('facture.index', compact('Resultat'));
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
            return redirect('/facture');
        }
        $Result = DB::table('facture as fa')
            ->select('fa.flag_fact','fa.annule_fact', 'bl.num_bl','code_cli','client.nom_cli','client.prenom_cli',
                'fa.code_fact',  'fa.solde_fact', 'fa.num_fact' ,'agence.lib_agce','fa.date_cre_fact','fa.date_val_fact','fa.prix_ttc_fact','code_comc')
            ->join('bon_livraison as bl', 'fa.num_bl', '=', 'bl.num_bl', 'inner')
            ->join('commandeclient', 'bl.num_comc', '=', 'commandeclient.num_comc', 'inner')
            ->join('agence', 'fa.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('client', 'fa.num_cli', '=', 'client.num_cli', 'inner')
            ->where('fa.num_fact', '=', $idCombl)
            ->first();

        $flagValide = $Result->flag_fact;
        $flagAnnule = $Result->annule_fact;
        $flagsolde = $Result->solde_fact;


        /******************* Mise a jour de la commande ****************************/
        if ($request->isMethod('post')) {
            $data = $request->all();
            $ligneResultRegMtt = DB::table('reglement as reg')
                ->select( DB::raw('SUM(montant_ttc_reg) as montant_ttc_reg' ) )
                ->where('num_fact', '=', $idCombl)
                ->first();
            $RestApaye= $Result->prix_ttc_fact-$ligneResultRegMtt->montant_ttc_reg;
            if ( $data['montant_ttc_reg'] > $RestApaye) {
                return redirect('/facture/edit/' . Crypt::UrlCrypt($idCombl))->with('echec', 'Echec : Le montant saisi est superieur au reste à payer de la facture ');
            }

            if ($data['montant_ttc_reg'] == 0 or $data['montant_ttc_reg'] == null  ) {
                return redirect('/facture/edit/' . Crypt::UrlCrypt($idCombl))->with('echec', 'Echec : Veuillez saisir le montant du reglement ');
            }
            if ($data['num_mpaie'] ==null  ) {
                return redirect('/facture/edit/' . Crypt::UrlCrypt($idCombl))->with('echec', 'Echec : Veuillez selectionner le mode de reglement ');
            }
            if ($data['action'] === 'Payer') {
                Reglement::create($request->all());
                return redirect('/facture/edit/' . Crypt::UrlCrypt($idCombl))->with('success', 'Succes : Paiement reussi ');
            }
        }

        /******************** Mise a jour des produit **********************************/


        $ligneResult = DB::table('ligne_fact as lf')
            ->join('produit', 'lf.num_prod', '=', 'produit.num_prod', 'inner')
            ->where('num_fact', '=', $idCombl)
            ->get();

        $ligneResultReg = DB::table('reglement as reg')
            ->select('reg.created_at','reg.montant_ttc_reg','reg.num_mpaie','lib_mpaie')
            ->join('mode_paiement as mp', 'reg.num_mpaie', '=', 'mp.num_mpaie', 'inner')
            ->where('num_fact', '=', $idCombl)
            ->orderBy('created_at','desc')
            ->get();
        //dd($ligneResultReg);
        $Modepaie = DB::table('mode_paiement')
            ->where('flag_mpaie', '=', true)
            ->orderBy('lib_mpaie',)
            ->get();
        $ModepaieList = "<option value='' > Sélectionner </option>";

        foreach ($Modepaie as $comp) {
            $ModepaieList .= "<option value='" . $comp->num_mpaie . "' >" . $comp->lib_mpaie . "</option>";
        }
        return view('facture.edit',
            compact(
                'flagValide','ModepaieList','flagsolde',
                'flagAnnule', 'Result',  'ligneResult',  'ligneResultReg'
            ));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function etat(Request $request, $id = null)
    {
        $idCombl = Crypt::UrldeCrypt($id);

        if ($idCombl == null) {
            return redirect('/facture');
        }
        $Result = DB::table('facture as fa')
            ->select('fa.flag_fact','fa.annule_fact', 'bl.num_bl','code_cli','client.nom_cli','client.prenom_cli','client.tel_cli','client.cel_cli','client.adresse_geo_cli',
                'fa.code_fact', 'cpte_contr_cli', 'fa.solde_fact', 'fa.num_fact' ,'agence.lib_agce','fa.date_cre_fact','fa.date_val_fact','fa.prix_ttc_fact','fa.prix_tva_fact','fa.prix_ht_fact','code_comc')
            ->join('bon_livraison as bl', 'fa.num_bl', '=', 'bl.num_bl', 'inner')
            ->join('commandeclient', 'bl.num_comc', '=', 'commandeclient.num_comc', 'inner')
            ->join('agence', 'fa.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('client', 'fa.num_cli', '=', 'client.num_cli', 'inner')
            ->where('fa.num_fact', '=', $idCombl)
            ->first();

        $flagValide = $Result->flag_fact;
        $flagAnnule = $Result->annule_fact;
        $flagsolde = $Result->solde_fact;

        /******************** Mise a jour des produit **********************************/


        $ligneResult = DB::table('ligne_fact as lf')
            ->join('produit', 'lf.num_prod', '=', 'produit.num_prod', 'inner')
            ->where('num_fact', '=', $idCombl)
            ->get();

        $ligneResultReg = DB::table('reglement as reg')
            ->select('reg.created_at','reg.montant_ttc_reg','reg.num_mpaie','lib_mpaie')
            ->join('mode_paiement as mp', 'reg.num_mpaie', '=', 'mp.num_mpaie', 'inner')
            ->where('num_fact', '=', $idCombl)
            ->orderBy('created_at','desc')
            ->get();

        return view('facture.etat',
            compact(
                'flagValide','flagsolde',
                'flagAnnule', 'Result',  'ligneResult',  'ligneResultReg'
            ));

    }

}
