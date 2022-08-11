<?php

namespace App\Http\Controllers;

use App\Helpers\Crypt;
use App\Models\Commandefour;
use App\Models\Fournisseur;
use App\Models\LigneBr;
use App\Models\LigneComfour;
use App\Models\Produit;
use App\Models\ReceptionFour;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReceptionfourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Resultat = DB::table('reception_four')
            ->join('commandefour', 'reception_four.num_comf', '=', 'commandefour.num_comf', 'inner')
            ->join('agence', 'reception_four.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('fournisseur', 'reception_four.num_fourn', '=', 'fournisseur.num_fourn', 'inner')
            ->get();
        return view('receptionfour.index', compact('Resultat'));
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

      //  dd($idCombr);

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

            if ($data['action'] === 'Modifier') {

                //dd($data);

                $ligneReceptionfour = DB::table('ligne_br')
                    ->join('produit', 'ligne_br.num_prod', '=', 'produit.num_prod', 'inner')
                    ->where('num_br', '=', $idCombr)
                    ->get();

                $infosfournisseur = DB::table('reception_four')
                    ->join('fournisseur','reception_four.num_fourn','fournisseur.num_fourn')
                    ->where([['num_br','=',$idCombr]])
                    ->first();

                $tva = DB::table('tauxtva')->first();
                $tvaval = $tva->val_taxe;

                foreach($ligneReceptionfour as $re):

                    $form =  $data["num_prod/$re->code_prod"];
                    $form1 =  $data["qte_lbr/$re->code_prod"];
                    $form2 =  $data["prix_ht_lbr/$re->code_prod"];
                    $form4 =  $data["prix_vente/$re->code_prod"];

                    //dd($form4);exit();

                    $recupinfoproduit = DB::table('produit')->where([['num_prod','=',$form]])->first();

                    //dd($recupinfoproduit);
                    if ($recupinfoproduit->prix_ht > $form2){


                    if ($infosfournisseur->flag_tva_fourn == true){

                        $prixttc = trim($form2) + (trim($form2)*$tvaval/100);

                        //$camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                        //$camp->prix_ttc_lcomfour = $prixttc;
                        //$camp->prix_tva_lcomfour =

                        LigneBr::where([['num_prod', '=', $form],['num_br', '=', $idCombr]])->update([
                            'qte_lbr' => $form1,
                            'prix_ht_lbr' => trim($form2),
                            'prix_ttc_lbr' => $prixttc,
                            'prix_tva_lbr' => trim($form2)*$tvaval/100,
                            'tot_ttc_lbr' => $prixttc * $form1,
                            'tot_ht_lbr' => trim($form2) * $form1,
                            'tot_tva_lbr' => (trim($form2)*$tvaval/100) * $form1,
                            'taux_tva_lbr' => $tvaval,
                        ]);

                            if ($recupinfoproduit->flag_tva_prod == 1){
                                Produit::where([['num_prod', '=', $form]])->update([
                                    'prix_ht' => trim($form4 ),
                                    //'prix_ttc' => trim($form4),
                                    'prix_achat_prod' => trim($prixttc),
                                    'prix_revient_prod' => trim($form4 - $prixttc),
                                    'taux_marque' => trim(($form4 - $prixttc)/$form4)
                                ]);
                            }else{
                                Produit::where([['num_prod', '=', $form]])->update([
                                    'prix_ht' => $form4,
                                    'prix_ttc' => $form4,
                                    'prix_achat_prod' => $prixttc,
                                    'prix_revient_prod' => $form4 - $prixttc,
                                    'taux_marque' => ($form4 - $prixttc)/$form4
                                ]);
                            }




                    }else{
                        LigneBr::where([['num_prod', '=', $form],['num_br', '=', $idCombr]])->update([
                            'qte_lbr' => $form1,
                            'prix_ht_lbr' => trim($form2),
                            'prix_ttc_lbr' => trim($form2),
                            'prix_tva_lbr' => 0,
                            'tot_ttc_lbr' => trim($form2) * $form1,
                            'tot_ht_lbr' => trim($form2) * $form1,
                            'tot_tva_lbr' => 0,
                            'taux_tva_lbr' => $tvaval,
                        ]);

                            if ($recupinfoproduit->flag_tva_prod == 1){
                                Produit::where([['num_prod', '=', $form]])->update([
                                    'prix_ht' => $form4,
                                    //'prix_ttc' => $form4,
                                    'prix_achat_prod' => $form2,
                                    'prix_revient_prod' => $form4 - $form2,
                                    'taux_marque' => ($form4 - $form2)/$form4
                                ]);
                            }else{
                                Produit::where([['num_prod', '=', $form]])->update([
                                    'prix_ht' => $form4,
                                    'prix_ttc' => $form4,
                                    'prix_achat_prod' => $form2,
                                    'prix_revient_prod' => $form4 - $form2,
                                    'taux_marque' => ($form4 - $form2)/$form4
                                ]);
                            }


                    }
                    }else{
                        return redirect('/receptionfour/edit/' . Crypt::UrlCrypt($idCombr))->with('echec', 'Erreur : Le montant d achat ne doit pas etre superieur a la vente');

                    }



                endforeach;


                //ReceptionFour::where('num_br', '=', $idCombr)->update(['flag_br' => true]);
                return redirect('/receptionfour/edit/' . Crypt::UrlCrypt($idCombr))->with('success', 'Succes : Validation reussi ');
            }

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
//dd($ligneReceptionfour);
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
            return redirect('/receptionfour');
        }
        $Result = DB::table('reception_four as rf')
            ->select('rf.flag_br','rf.annule_br', 'cf.num_comf','fournisseur.lib_fourn','fournisseur.tel_fourn','fournisseur.cel_fourn','fournisseur.adr_fourn',
                'rf.num_br',  'rf.solde_br', 'rf.num_comf' ,'agence.lib_agce','rf.date_cre_br','rf.date_val_br','rf.prix_ttc_br','rf.prix_tva_br','rf.prix_ht_br')
            ->join('commandefour as cf', 'rf.num_comf', '=', 'cf.num_comf', 'inner')
            ->join('agence', 'cf.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('fournisseur', 'cf.num_fourn', '=', 'fournisseur.num_fourn', 'inner')
            ->where('rf.num_br', '=', $idCombl)
            ->first();

        $flagValide = $Result->flag_br;
        $flagAnnule = $Result->annule_br;
        $flagsolde = $Result->solde_br;

        /******************** Mise a jour des produit **********************************/


        $ligneResult = DB::table('ligne_br as lf')
            ->join('produit', 'lf.num_prod', '=', 'produit.num_prod', 'inner')
            ->where('num_br', '=', $idCombl)
            ->get();

        /*$ligneResultReg = DB::table('reglement as reg')
            ->select('reg.created_at','reg.montant_ttc_reg','reg.num_mpaie','lib_mpaie')
            ->join('mode_paiement as mp', 'reg.num_mpaie', '=', 'mp.num_mpaie', 'inner')
            ->where('num_fact', '=', $idCombl)
            ->orderBy('created_at','desc')
            ->get();*/

        return view('receptionfour.etat',
            compact(
                'flagValide','flagsolde',
                'flagAnnule', 'Result',  'ligneResult'
            ));

    }

}
