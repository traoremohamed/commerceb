<?php

namespace App\Http\Controllers;

use App\Helpers\Crypt;
use App\Helpers\GenerateCode as Gencode;
use App\Models\Commandeclient;
use App\Models\LigneCom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComclientController extends Controller
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
               select * from commandeclient c
inner join agence a on c.num_agce = a.num_agce
inner join client c2 on c.num_cli = c2.num_cli
where left (c.code_comc, 1) = 'B' and c.flag_comc = false
                      "),
            array(

            )

        );

        /*DB::table('commandeclient')
            ->join('agence', 'commandeclient.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('client', 'commandeclient.num_cli', '=', 'client.num_cli', 'inner')
            ->get();*/
        return view('comclient.index', compact('Resultat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $idAgceCon = Auth::user()->num_agce;
        $idUser = Auth::user()->id;
        $flagValide = null;
        $flagAnnule = null;

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
        foreach ($Agence as $comp) {
            $AgenceList .= "<option value='" . $comp->num_agce . "'      >" . $comp->lib_agce . "</option>";
        }
        $TClient = DB::table('client')
            ->where('flag_cli', '=', true)
            ->orderBy('nom_cli',)->get();
        $TClientList = "<option value='' > Sélectionner </option>";
        foreach ($TClient as $comp) {
            $TClientList .= "<option value='" . $comp->num_cli . "'      >" . $comp->code_cli . ' : ' . $comp->nom_cli . ' ' . $comp->prenom_cli . "</option>";
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'num_agce' => 'required',
                'num_cli' => 'required'
            ]);
            $data = $request->all();
            $codeCMD = 'BC-' . Gencode::randStrGen(4, 6);
            $camp = new Commandeclient();
            $camp->id_user = $idUser;
            $camp->code_comc = $codeCMD;
            $camp->num_agce = $data['num_agce'];
            $camp->num_cli = $data['num_cli'];
            $camp->save();
            $insertedId = Commandeclient::latest()->first()->num_comc;

            return redirect('/comclient/edit/' . Crypt::UrlCrypt($insertedId))->with('success', 'Succes : Enregistrement reussi ');
        }
        return view('comclient.create',
            compact(
                'TClientList',
                'AgenceList',
                'flagValide',
                'flagAnnule'
            ));
    }


    public function edit(Request $request, $id = null)
    {
        $idComcli = Crypt::UrldeCrypt($id);
        //dd($idComcli);die();
        $idAgceCon = Auth::user()->num_agce;
        $idUser = Auth::user()->id;

        if ($idComcli == null) {
            return redirect('/comclient/create');
        }
        $comCli = DB::table('commandeclient')
            ->where('num_comc', '=', $idComcli)
            ->first();

        $flagValide = $comCli->flag_comc;
        $flagAnnule = $comCli->annule_comc;

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
            if ($comp->num_agce == $comCli->num_agce) {
                $val = 'selected ';
            } else {
                $val = "";
            }
            $AgenceList .= "<option value='" . $comp->num_agce . "'  $val   >" . $comp->lib_agce . "</option>";
        }

        $TClient = DB::table('client')
            ->where('flag_cli', '=', true)
            ->orderBy('nom_cli',)->get();
        $TClientList = "<option value='' > Sélectionner </option>";
        $val = "";
        foreach ($TClient as $comp) {
            if ($comp->num_cli == $comCli->num_cli) {
                $val = 'selected="selected" ';
            } else {
                $val = "";
            }
            $TClientList .= "<option value='" . $comp->num_cli . "' $val  >" . $comp->code_cli . ' : ' . $comp->nom_cli . ' ' . $comp->prenom_cli . "</option>";
        }
        /******************* Mise a jour de la commande ****************************/
        if ($request->isMethod('post')) {
            $data = $request->all();
            //dd($request->input());
            if ($data['action'] === 'Enregistrer') {
                Commandeclient::where('num_comc', '=', $idComcli)->update(['num_cli' => $data['num_cli'], 'num_agce' => $data['num_agce']]);
                return redirect('/comclient/edit/' . Crypt::UrlCrypt($idComcli))->with('success', 'Succes : Enregistrement reussi ');
            }
            if ($data['action'] === 'Valider') {
                Commandeclient::where('num_comc', '=', $idComcli)->update(['flag_comc' => true], ['num_cli' => $data['num_cli'], 'num_agce' => $data['num_agce']]);
                return redirect('/comclient/edit/' . Crypt::UrlCrypt($idComcli))->with('success', 'Succes : Validation reussi ');
            }
        }

        /******************** Mise a jour des produit **********************************/
        if ($request->isMethod('post')) {
            $data = $request->all();
            // dd($data);
            if (($data['action'] === 'Ajouter' and $data['num_prod'] !== null) or ($data['action'] === 'Ajouter' and $data['code_barre_prod'] !== null)) { //
                $ligneComCli = DB::table('ligne_com')
                    ->where([['num_prod', '=', $data['num_prod']], ['num_comc', '=', $idComcli]])
                    ->first();
                //dd($ligneComCli);
                if (isset($ligneComCli)) {
                    return redirect('/comclient/edit/' . Crypt::UrlCrypt($idComcli))->with('echec', 'Echec : Le produit a déja été saisi ');
                }
                /**********recupere le client pour la commande afin de verifie s'il doit etre affecter par la tva******/
                $recupclient = DB::table('commandeclient')
                    ->join('client','commandeclient.num_cli','client.num_cli')
                    ->where([['commandeclient.num_comc','=',$idComcli]])
                    ->first();
                if ($recupclient->tva_cli == 1){
                    $ligneProduit = DB::table('produit')
                        ->where('num_prod', '=', $data['num_prod'])
                        //->orWhere('code_barre_prod', '=', $data['code_barre_prod'])
                        ->first();
                    //dd($ligneProduit);
                    //($data['num_prod'].$ligneProduit);
                    // Prix de vente normal x (1-Taux de remise (en %)/100) = prix de vente remisé
                    $remisettc = 0;
                    if (isset($recupclient->taux_remise_cli)){
                        if ($recupclient->taux_remise_cli > 0) {
                            $c = $recupclient->taux_remise_cli / 100;
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
                    }else{
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
                    }

                }else{
                    $ligneProduit = DB::table('produit')
                        ->where('num_prod', '=', $data['num_prod'])
                        //->orWhere('code_barre_prod', '=', $data['code_barre_prod'])
                        ->first();
                    //dd($ligneProduit);
                    //($data['num_prod'].$ligneProduit);
                    // Prix de vente normal x (1-Taux de remise (en %)/100) = prix de vente remisé
                    $remisettc = 0;
                    if (isset($recupclient->taux_remise_cli)){
                        if ($recupclient->taux_remise_cli > 0) {
                            $c = $recupclient->taux_remise_cli / 100;
                            $remise = (1 - $c);
                            $prxRemise = trim($ligneProduit->prix_ht * $remise);
                            $remisettc = trim($ligneProduit->prix_ht - $prxRemise);
                            /*********** remise ht************/
                            $prxRemiseht = trim($ligneProduit->prix_ht * $remise);
                            $remiseht = trim($ligneProduit->prix_ht - $prxRemiseht);
                            /*********** remise tva ************/
                            $prxRemisetva = trim(($ligneProduit->prix_ht-$ligneProduit->prix_ht) * $remise);
                            $remisetva = trim($ligneProduit->prix_ht-$ligneProduit->prix_ht - $prxRemisetva);

                        } else {
                            $remisettc = 0;
                            $prxRemise = trim($ligneProduit->prix_ht);
                            $prxRemiseht = trim($ligneProduit->prix_ht);
                            $prxRemisetva = trim($ligneProduit->prix_ht-$ligneProduit->prix_ht);
                        }
                    }else{
                        if ($data['remise_lcomc'] > 0) {
                            $c = $data['remise_lcomc'] / 100;
                            $remise = (1 - $c);
                            $prxRemise = trim($ligneProduit->prix_ht * $remise);
                            $remisettc = trim($ligneProduit->prix_ht - $prxRemise);
                            /*********** remise ht************/
                            $prxRemiseht = trim($ligneProduit->prix_ht * $remise);
                            $remiseht = trim($ligneProduit->prix_ht - $prxRemiseht);
                            /*********** remise tva ************/
                            $prxRemisetva = trim(($ligneProduit->prix_ht-$ligneProduit->prix_ht) * $remise);
                            $remisetva = trim($ligneProduit->prix_ht-$ligneProduit->prix_ht - $prxRemisetva);

                        } else {
                            $remisettc = 0;
                            $prxRemise = trim($ligneProduit->prix_ht);
                            $prxRemiseht = trim($ligneProduit->prix_ht);
                            $prxRemisetva = trim($ligneProduit->prix_ht-$ligneProduit->prix_ht);
                        }
                    }

                }



                $camp = new LigneCom();
                $camp->num_comc = $idComcli;
                $camp->num_prod = $ligneProduit->num_prod;
                $camp->qte_lcomc = trim($data['qte_lcomc']);
                if (isset($recupclient->taux_remise_cli)){
                    $camp->remise_lcomc = trim($recupclient->taux_remise_cli);
                }else{
                    $camp->remise_lcomc = trim($data['remise_lcomc']);
                }
                $camp->remise_ttc_lcomc = trim($remisettc);
                $camp->prix_ttc_lcomc = $prxRemise;
                $camp->prix_ht_lcomc = $prxRemiseht;
                $camp->prix_tva_lcomc = $prxRemisetva;
                $camp->tot_ttc_lcomc = trim($prxRemise) * trim($data['qte_lcomc']);
                $camp->tot_ht_lcomc = trim($prxRemiseht) * trim($data['qte_lcomc']);
                $camp->tot_tva_lcomc = trim($prxRemisetva) * trim($data['qte_lcomc']);
                $camp->save();

                return redirect('/comclient/edit/' . Crypt::UrlCrypt($idComcli))->with('success', 'Succes : Enregistrement reussi ');
            }


            if ($data['action'] === 'Modifier') {
                //dd($data);
                $ligneComCli = DB::table('ligne_com')
                    ->join('produit', 'ligne_com.num_prod', '=', 'produit.num_prod', 'inner')
                    ->where('num_comc', '=', $idComcli)
                    ->get();

                $infosclient = DB::table('commandeclient')
                    ->join('client','commandeclient.num_cli','client.num_cli')
                    ->where([['num_comc','=',$idComcli]])
                    ->first();

                $tva = DB::table('tauxtva')->first();
                $tvaval = $tva->val_taxe;
                foreach($ligneComCli as $re):

                    $form =  $data["num_prod/$re->code_prod"];
                    $form1 =  $data["qte_lcomc/$re->code_prod"];
                    $form2 =  $data["prix_ht_lcomc/$re->code_prod"];
                    $form3 =  $data["prix_ttc_lcomc/$re->code_prod"];
                    $form4 =  $data["remise_lcomc/$re->code_prod"];

                    $lignecomclientrecp = DB::table('ligne_com')
                        ->join('produit','ligne_com.num_prod', 'produit.num_prod')
                        ->where([['produit.code_prod','=',$re->code_prod],['ligne_com.num_comc','=',$idComcli]])
                        ->first();

                    if ($form4 > 0) {
                        $c = $form4 / 100;
                        $remise = (1 - $c);
                        $prxRemise = trim($form3 * $remise);
                        $remisettc = trim($form3 - $prxRemise);
                        /*********** remise ht************/
                        $prxRemiseht = trim($form2 * $remise);
                        $remiseht = trim($form2 - $prxRemiseht);
                        /*********** remise tva ************/
                        $prxRemisetva = trim(($lignecomclientrecp->prix_ttc_lcomc-$lignecomclientrecp->prix_ht_lcomc) * $remise);
                        $remisetva = trim($lignecomclientrecp->prix_ttc_lcomc-$lignecomclientrecp->prix_ht_lcomc - $prxRemisetva);

                        if ($infosclient->tva_cli == 1){


                            if ($lignecomclientrecp->prix_ttc_lcomc != $form3){

                                $prixttc = $form3;

                                $prixht = $form2 - (trim($form2)*$tvaval/100);

                                //$prixttc = $prixht + ;


                                //$camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                                //$camp->prix_ttc_lcomfour = $prixttc;
                                //$camp->prix_tva_lcomfour =

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($prixht- $remiseht),
                                    'prix_ttc_lcomc' => trim((($prixht- $remiseht)*$tvaval/100) + ($prixht- $remiseht)),
                                    'prix_tva_lcomc' =>  trim(($prixht- $remiseht)*$tvaval/100),
                                    'tot_ttc_lcomc' => trim(((($prixht- $remiseht)*$tvaval/100) + ($prixht- $remiseht)) * $form1),
                                    'tot_ht_lcomc' => trim(($prixht- $remiseht) * $form1),
                                    'tot_tva_lcomc' => trim((($prixht- $remiseht)*$tvaval/100) * $form1),
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);

                            }elseif ($lignecomclientrecp->prix_ht_lcomc != $form2){

                                //$htre = $form2-$remiseht;
                                $prixttc = trim($form2- $remiseht)  + (trim($form2 - $remiseht)*$tvaval/100) ;



                                //$camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                                //$camp->prix_ttc_lcomfour = $prixttc;
                                //$camp->prix_tva_lcomfour =

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($form2-$remiseht),
                                    'prix_ttc_lcomc' => trim($prixttc),
                                    'prix_tva_lcomc' => trim($form2-$remiseht)*$tvaval/100,
                                    'tot_ttc_lcomc' => trim($prixttc * $form1),
                                    'tot_ht_lcomc' => trim(($form2-$remiseht) * $form1),
                                    'tot_tva_lcomc' => trim((($form2-$remiseht)*$tvaval/100) * $form1),
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);
                            }else{

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($form2-$remiseht),
                                    'prix_ttc_lcomc' => trim($form3),
                                    'prix_tva_lcomc' => trim($form2-$remiseht)*$tvaval/100,
                                    'tot_ttc_lcomc' => trim($form3) * $form1,
                                    'tot_ht_lcomc' => trim($form2-$remiseht) * $form1,
                                    'tot_tva_lcomc' => (trim($form2-$remiseht)*$tvaval/100) * $form1,
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);
                            }



                        }else{




                            if ($lignecomclientrecp->prix_ttc_lcomc != $form3){

                                $prixttc = $form3;

                                $prixht = $prixttc - (trim($prixttc)*$tvaval/100);



                                //$camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                                //$camp->prix_ttc_lcomfour = $prixttc;
                                //$camp->prix_tva_lcomfour =

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($prixttc-$remiseht),
                                    'prix_ttc_lcomc' => trim($prixttc-$remiseht),
                                    'prix_tva_lcomc' => 0,
                                    'tot_ttc_lcomc' => trim($prixttc-$remiseht) * $form1,
                                    'tot_ht_lcomc' => trim($prixttc-$remiseht) * $form1,
                                    'tot_tva_lcomc' => 0,
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);

                            }elseif ($lignecomclientrecp->prix_ht_lcomc != $form2){

                                $prixttc = trim($form2) + (trim($form2)*$tvaval/100);



                                //$camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                                //$camp->prix_ttc_lcomfour = $prixttc;
                                //$camp->prix_tva_lcomfour =

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($form2-$remiseht),
                                    'prix_ttc_lcomc' => trim($form2-$remiseht),
                                    'prix_tva_lcomc' => 0,
                                    'tot_ttc_lcomc' => trim($form2-$remiseht) * $form1,
                                    'tot_ht_lcomc' => trim($form2-$remiseht) * $form1,
                                    'tot_tva_lcomc' => 0,
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);
                            }else{

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($form2-$remiseht),
                                    'prix_ttc_lcomc' => trim($form2-$remiseht),
                                    'prix_tva_lcomc' => 0,
                                    'tot_ttc_lcomc' => trim($form2-$remiseht) * $form1,
                                    'tot_ht_lcomc' => trim($form2-$remiseht) * $form1,
                                    'tot_tva_lcomc' => 0,
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);
                            }


                        }

                    } else {
                        $remisettc = 0;
                        $remiseht = 0;
                        $prxRemise = trim($lignecomclientrecp->prix_ttc_lcomc);
                        $prxRemiseht = trim($lignecomclientrecp->prix_ht_lcomc);
                        $prxRemisetva = trim($lignecomclientrecp->prix_ttc_lcomc-$lignecomclientrecp->prix_ht_lcomc);


                        if ($infosclient->tva_cli == 1){


                            if ($lignecomclientrecp->prix_ttc_lcomc != $form3){

                                $prixttc = $form3;

                                $prixht = $prixttc - (trim($prixttc)*$tvaval/100);



                                //$camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                                //$camp->prix_ttc_lcomfour = $prixttc;
                                //$camp->prix_tva_lcomfour =

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($prixht),
                                    'prix_ttc_lcomc' => trim($prixttc),
                                    'prix_tva_lcomc' =>  trim($prixttc*$tvaval/100),
                                    'tot_ttc_lcomc' => trim($prixttc * $form1),
                                    'tot_ht_lcomc' => trim($prixht * $form1),
                                    'tot_tva_lcomc' => trim(($prixttc*$tvaval/100) * $form1),
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);

                            }elseif ($lignecomclientrecp->prix_ht_lcomc != $form2){

                                $prixttc = trim($form2) + (trim($form2)*$tvaval/100);



                                //$camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                                //$camp->prix_ttc_lcomfour = $prixttc;
                                //$camp->prix_tva_lcomfour =

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($form2),
                                    'prix_ttc_lcomc' => trim($prixttc),
                                    'prix_tva_lcomc' => trim($form2)*$tvaval/100,
                                    'tot_ttc_lcomc' => trim($prixttc * $form1),
                                    'tot_ht_lcomc' => trim($form2 * $form1),
                                    'tot_tva_lcomc' => trim(($form2*$tvaval/100) * $form1),
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);
                            }else{

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($form2),
                                    'prix_ttc_lcomc' => trim($form3),
                                    'prix_tva_lcomc' => trim($form2)*$tvaval/100,
                                    'tot_ttc_lcomc' => trim($form3) * $form1,
                                    'tot_ht_lcomc' => trim($form2) * $form1,
                                    'tot_tva_lcomc' => (trim($form2)*$tvaval/100) * $form1,
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);
                            }



                        }else{




                            if ($lignecomclientrecp->prix_ttc_lcomc != $form3){

                                $prixttc = $form3;

                                $prixht = $prixttc - (trim($prixttc)*$tvaval/100);



                                //$camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                                //$camp->prix_ttc_lcomfour = $prixttc;
                                //$camp->prix_tva_lcomfour =

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($prixttc),
                                    'prix_ttc_lcomc' => trim($prixttc),
                                    'prix_tva_lcomc' => 0,
                                    'tot_ttc_lcomc' => trim($prixttc) * $form1,
                                    'tot_ht_lcomc' => trim($prixttc) * $form1,
                                    'tot_tva_lcomc' => 0,
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);

                            }elseif ($lignecomclientrecp->prix_ht_lcomc != $form2){

                                $prixttc = trim($form2) + (trim($form2)*$tvaval/100);



                                //$camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                                //$camp->prix_ttc_lcomfour = $prixttc;
                                //$camp->prix_tva_lcomfour =

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($form2),
                                    'prix_ttc_lcomc' => trim($form2),
                                    'prix_tva_lcomc' => 0,
                                    'tot_ttc_lcomc' => trim($form2) * $form1,
                                    'tot_ht_lcomc' => trim($form2) * $form1,
                                    'tot_tva_lcomc' => 0,
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);
                            }else{

                                LigneCom::where([['num_prod', '=', $form],['num_comc', '=', $idComcli]])->update([
                                    'qte_lcomc' => $form1,
                                    'prix_ht_lcomc' => trim($form2),
                                    'prix_ttc_lcomc' => trim($form2),
                                    'prix_tva_lcomc' => 0,
                                    'tot_ttc_lcomc' => trim($form2) * $form1,
                                    'tot_ht_lcomc' => trim($form2) * $form1,
                                    'tot_tva_lcomc' => 0,
                                    'remise_lcomc' => $form4,
                                    'remise_ttc_lcomc' => $remiseht,
                                ]);
                            }


                        }
                    }


                endforeach;

                return redirect('/comclient/edit/' . Crypt::UrlCrypt($idComcli))->with('success', 'Succes : Enregistrement reussi ');
            }
        }

        $Produit = DB::table('produit')
            ->where('flag_prod', '=', true)
            ->orderBy('lib_prod',)->get();
        $ProduitList = "<option value='' > Sélectionner </option>";
        foreach ($Produit as $comp) {
            $ProduitList .= "<option value='" . $comp->num_prod . "' >" . $comp->code_prod . ' : ' . $comp->lib_prod . "</option>";
        }
        $ligneComCli = DB::table('ligne_com')
            ->join('produit', 'ligne_com.num_prod', '=', 'produit.num_prod', 'inner')
            ->where('num_comc', '=', $idComcli)
            ->get();

        //dd($ligneComCli);die();
        return view('comclient.edit',
            compact(
                'TClientList',
                'AgenceList',
                'flagValide',
                'flagAnnule', 'comCli', 'ProduitList', 'ligneComCli'
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
        return redirect('/comclient/edit/' . Crypt::UrlCrypt($ligneComCli->num_comc))->with('success', 'Succes : Suppression reussi ');

    }


    public function etat(Request $request, $id = null)
    {
        $idComcli = Crypt::UrldeCrypt($id);

        $idAgceCon = Auth::user()->num_agce;
        $idUser = Auth::user()->id;

        if ($idComcli == null) {
            return redirect('/comclient/create');
        }
        $Result  = DB::table('commandeclient as com')
            ->join('agence', 'com.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('client', 'com.num_cli', '=', 'client.num_cli', 'inner')
            ->where('num_comc', '=', $idComcli)
            ->first();

        $flagValide = $Result->flag_comc;
        $flagAnnule = $Result->annule_comc;

        $ligneResult = DB::table('ligne_com')
            ->join('produit', 'ligne_com.num_prod', '=', 'produit.num_prod', 'inner')
            ->where('num_comc', '=', $idComcli)
            ->get();

        return view('comclient.etat',
            compact(

                'flagValide',
                'flagAnnule', 'Result',  'ligneResult'
            ));
    }


}
