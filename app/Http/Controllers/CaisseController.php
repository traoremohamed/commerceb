<?php

namespace App\Http\Controllers;

use App\Helpers\Crypt;
use App\Helpers\GenerateCode as Gencode;
use App\Models\BonLivraison;
use App\Models\Commandeclient;
use App\Models\Facture;
use App\Models\LigneCom;
use App\Models\Reglement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaisseController extends Controller
{
    public function ventecaisse(Request $request, $id=null, $id1=null){

        $idCombl = Crypt::UrldeCrypt($id);
        $idNumfact = Crypt::UrldeCrypt($id1);
        $idAgceCon = Auth::user()->num_agce;
        $idUser = Auth::user()->id;
        $ligneResult = [];
        $flagValide = null;
        $flagAnnule = null;
        $flagsolde = null;
        $ligneResultReg = [];

        if ($idCombl == null) {

            $codeCMD = 'VBC-' . Gencode::randStrGen(4, 6);
            $camp = new Commandeclient();
            $camp->id_user = $idUser;
            $camp->code_comc = $codeCMD;
            $camp->num_agce = $idAgceCon;
            $camp->num_cli = '1'; //$data['num_cli'];
            $camp->save();
            $insertedId = Commandeclient::latest()->first()->num_comc;



            return redirect('/ventecaisse/edit/' . Crypt::UrlCrypt($insertedId));

        }elseif($idCombl != null and $idNumfact != null){

            $Result = DB::table('facture as fa')
                ->select('fa.flag_fact','fa.annule_fact', 'bl.num_bl','code_cli','client.nom_cli','client.prenom_cli',
                    'fa.code_fact',  'fa.solde_fact', 'fa.num_fact' ,'agence.lib_agce','fa.date_cre_fact','fa.date_val_fact','fa.prix_ttc_fact','code_comc','bl.num_comc','date_cre_comc','prix_ttc_comc')
                ->join('bon_livraison as bl', 'fa.num_bl', '=', 'bl.num_bl', 'inner')
                ->join('commandeclient', 'bl.num_comc', '=', 'commandeclient.num_comc', 'inner')
                ->join('agence', 'fa.num_agce', '=', 'agence.num_agce', 'inner')
                ->join('client', 'fa.num_cli', '=', 'client.num_cli', 'inner')
                ->where('fa.num_fact', '=', $idNumfact)
                ->first();

            //dd($Result);die();


            $flagValide = $Result->flag_fact;
            $flagAnnule = $Result->annule_fact;
            $flagsolde = $Result->solde_fact;


            $ligneResult = DB::table('ligne_com')
                ->join('produit', 'ligne_com.num_prod', '=', 'produit.num_prod', 'inner')
                ->where('num_comc', '=', $idCombl)
                ->get();

            $ligneResultReg = DB::table('reglement as reg')
                ->select('reg.created_at','reg.montant_ttc_reg','reg.num_mpaie','lib_mpaie')
                ->join('mode_paiement as mp', 'reg.num_mpaie', '=', 'mp.num_mpaie', 'inner')
                ->where('num_fact', '=', $idNumfact)
                ->orderBy('created_at','desc')
                ->get();

            if ($request->isMethod('post')) {
                $data = $request->all();
                $ligneResultRegMtt = DB::table('reglement as reg')
                    ->select( DB::raw('SUM(montant_ttc_reg) as montant_ttc_reg' ) )
                    ->where('num_fact', '=', $idNumfact)
                    ->first();
                $RestApaye= $Result->prix_ttc_fact-$ligneResultRegMtt->montant_ttc_reg;
                if ( $data['montant_ttc_reg'] > $RestApaye) {
                    return redirect('/ventecaisse/edit/' . Crypt::UrlCrypt($idCombl). '/'. Crypt::UrlCrypt($idNumfact))->with('echec', 'Echec : Le montant saisi est superieur au reste à payer de la facture ');
                }

                if ($data['montant_ttc_reg'] == 0 or $data['montant_ttc_reg'] == null  ) {
                    return redirect('/ventecaisse/edit/' . Crypt::UrlCrypt($idCombl). '/'. Crypt::UrlCrypt($idNumfact))->with('echec', 'Echec : Veuillez saisir le montant du reglement ');
                }
                if ($data['num_mpaie'] ==null  ) {
                    return redirect('/ventecaisse/edit/' . Crypt::UrlCrypt($idCombl). '/'. Crypt::UrlCrypt($idNumfact))->with('echec', 'Echec : Veuillez selectionner le mode de reglement ');
                }
                if ($data['action'] === 'Payer') {
                    Reglement::create($request->all());
                    return redirect('/ventecaisse/edit/' . Crypt::UrlCrypt($idCombl). '/'. Crypt::UrlCrypt($idNumfact))->with('success', 'Succes : Paiement reussi ');
                }
            }


        }elseif($idCombl != null and $idNumfact == null){

            $Result = DB::table('commandeclient')
                ->join('client','commandeclient.num_cli','client.num_cli')
                ->join('agence','commandeclient.num_agce', 'agence.num_agce')
                ->where([['num_comc', '=', $idCombl]])
                ->first();
//exit('test');
            if ($request->isMethod('post')) {
               // exit('test');
                $data = $request->all();
                // dd($data);die();
                if (($data['action'] === 'Ajouter' and $data['num_prod'] !== null) or ($data['action'] === 'Ajouter' and $data['code_barre_prod'] !== null)) { //
                    $ligneComCli = DB::table('ligne_com')
                        ->where([['num_prod', '=', $data['num_prod']], ['num_comc', '=', $idCombl]])
                        ->first();
                    if (isset($ligneComCli)) {
                        return redirect('/ventecaisse/edit/' . Crypt::UrlCrypt($idCombl))->with('echec', 'Echec : Le produit a déja été saisi ');
                    }
                    /**********recupere le client pour la commande afin de verifie s'il doit etre affecter par la tva******/
                    $recupclient = DB::table('commandeclient')
                        ->join('client','commandeclient.num_cli','client.num_cli')
                        ->where([['commandeclient.num_comc','=',$idCombl]])
                        ->first();

                        $ligneProduit = DB::table('produit')
                            ->where('num_prod', '=', $data['num_prod'])
                            ->orWhere('code_barre_prod', '=', $data['code_barre_prod'])
                            ->first();
                        //($data['num_prod'].$ligneProduit);
                        // Prix de vente normal x (1-Taux de remise (en %)/100) = prix de vente remisé
                        $remisettc = 0;
                        if ($data['remise_lcomc'] > 0) {
                            $c = $data['remise_lcomc'] / 100;
                            $remise = (1 - $c);
                            $prxRemise = trim($ligneProduit->prix_ttc * $remise);
                            $remisettc = trim($ligneProduit->prix_ttc - $prxRemise);
                            /*********** remise ht************/
                            $prxRemiseht = trim($ligneProduit->prix_ht * $remise);
                            $remiseht = trim($ligneProduit->prix_ht - $prxRemiseht);
                            /*********** remise tva ************/
                            $prxRemisetva = trim(($ligneProduit->prix_ttc-$ligneProduit->prix_ht) * $remise);
                            $remisetva = trim($ligneProduit->prix_ttc-$ligneProduit->prix_ht - $prxRemisetva);

                        } else {
                            $remisettc = 0;
                            $prxRemise = trim($ligneProduit->prix_ttc);
                            $prxRemiseht = trim($ligneProduit->prix_ht);
                            $prxRemisetva = trim($ligneProduit->prix_ttc-$ligneProduit->prix_ht);
                        }



                    $camp = new LigneCom();
                    $camp->num_comc = $idCombl;
                    $camp->num_agce_vente = $idAgceCon;
                    $camp->num_prod = $ligneProduit->num_prod;
                    $camp->qte_lcomc = trim($data['qte_lcomc']);
                    $camp->remise_lcomc = trim($data['remise_lcomc']);
                    $camp->remise_ttc_lcomc = trim($remisettc);
                    $camp->prix_ttc_lcomc = $prxRemise;
                    $camp->prix_ht_lcomc = $prxRemiseht;
                    $camp->prix_tva_lcomc = $prxRemisetva;
                    $camp->tot_ttc_lcomc = trim($prxRemise) * trim($data['qte_lcomc']);
                    $camp->tot_ht_lcomc = trim($prxRemiseht) * trim($data['qte_lcomc']);
                    $camp->tot_tva_lcomc = trim($prxRemisetva) * trim($data['qte_lcomc']);
                    $camp->save();

                    return redirect('/ventecaisse/edit/' . Crypt::UrlCrypt($idCombl))->with('success', 'Succes : Enregistrement reussi ');
                }
            }

            if ($request->isMethod('post')) {
                $data = $request->all();
                //dd($request->input());
                if ($data['action'] === 'Valider') {
                    Commandeclient::where('num_comc', '=', $idCombl)->update(['flag_comc' => true]);

                    $idComblb = BonLivraison::where([['num_comc', '=', $idCombl]])->first()->num_bl;

                    BonLivraison::where('num_bl', '=', $idComblb)->update(['flag_bl' => true]);

                    $idfactrec = Facture::where([['num_bl', '=', $idComblb]])->first()->num_fact;

                    return redirect('/ventecaisse/edit/' . Crypt::UrlCrypt($idCombl). '/'. Crypt::UrlCrypt($idfactrec))->with('success', 'Succes : Validation reussi ');
                }
            }

            $flagValide = false;
            $flagAnnule = false;
            $flagsolde = false;

            $ligneResult = DB::table('ligne_com')
                ->join('produit', 'ligne_com.num_prod', '=', 'produit.num_prod', 'inner')
                ->where('num_comc', '=', $idCombl)
                ->get();
            //dd($ligneResult);die();
            /*Commandeclient::where()->first();*/
        }else{

        }



        /* Commandeclient::where('num_comc', '=', $insertedId)->update(['flag_comc' => true]);
       BonLivraison::where([['num_comc', '=', $insertedId]])->update(['flag_bl' => true]);

        $idCombl = BonLivraison::where([['num_comc', '=', $insertedId]])->first()->num_fact;*/


       /* $Result = null;*/ /*DB::table('facture as fa')
            ->select('fa.flag_fact','fa.annule_fact', 'bl.num_bl','code_cli','client.nom_cli','client.prenom_cli',
                'fa.code_fact',  'fa.solde_fact', 'fa.num_fact' ,'agence.lib_agce','fa.date_cre_fact','fa.date_val_fact','fa.prix_ttc_fact','code_comc')
            ->join('bon_livraison as bl', 'fa.num_bl', '=', 'bl.num_bl', 'inner')
            ->join('commandeclient', 'bl.num_comc', '=', 'commandeclient.num_comc', 'inner')
            ->join('agence', 'fa.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('client', 'fa.num_cli', '=', 'client.num_cli', 'inner')
            ->where('fa.num_fact', '=', $idCombl)
            ->first();*/




        /******************* Mise a jour de la commande ****************************/


        /******************** Mise a jour des produit **********************************/


         /* DB::table('ligne_fact as lf')
            ->join('produit', 'lf.num_prod', '=', 'produit.num_prod', 'inner')
            ->where('num_fact', '=', $idCombl)
            ->get();*/


        //dd($ligneResultReg);
        $Modepaie = DB::table('mode_paiement')
            ->where('flag_mpaie', '=', true)
            ->orderBy('lib_mpaie',)
            ->get();
        $ModepaieList = "<option value='' > Sélectionner </option>";

        foreach ($Modepaie as $comp) {
            $ModepaieList .= "<option value='" . $comp->num_mpaie . "' >" . $comp->lib_mpaie . "</option>";
        }

        $Produit = DB::table('produit')
            ->where('flag_prod', '=', true)
            ->orderBy('lib_prod',)->get();
        $ProduitList = "<option value='' > Sélectionner </option>";
        foreach ($Produit as $comp) {
            $ProduitList .= "<option value='" . $comp->num_prod . "' >" . $comp->code_prod . ' : ' . $comp->lib_prod . "</option>";
        }
        $idAgceCon = Auth::user()->num_agce;

        return view('caisse.ventecaisse',
            compact(
                'flagValide','ModepaieList','flagsolde',
                'flagAnnule', 'Result',  'ligneResult',  'ligneResultReg','ProduitList','idCombl','idNumfact','idAgceCon'
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

        $idLcomf = Crypt::UrldeCrypt($id);
        $ligneComCli = DB::table('ligne_com')
            ->where('num_bl_lcomc', '=', $idLcomf)
            ->first();
        DB::table('ligne_com')->where('num_bl_lcomc', $idLcomf)->delete();
        return redirect('/ventecaisse/edit/' . Crypt::UrlCrypt($ligneComCli->num_comc))->with('success', 'Succes : Suppression reussi ');

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
                'fa.code_fact',  'fa.solde_fact', 'fa.num_fact' ,'agence.lib_agce','fa.date_cre_fact','fa.date_val_fact','fa.prix_ttc_fact','fa.prix_tva_fact','fa.prix_ht_fact','code_comc')
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

        return view('caisse.etat',
            compact(
                'flagValide','flagsolde',
                'flagAnnule', 'Result',  'ligneResult',  'ligneResultReg'
            ));

    }

}
