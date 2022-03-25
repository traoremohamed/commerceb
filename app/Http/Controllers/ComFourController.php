<?php

namespace App\Http\Controllers;

use App\Helpers\Crypt;
use App\Models\Commandefour;
use App\Models\Fournisseur;
use App\Models\LigneComfour;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ComFourController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Resultat = DB::table('commandefour as comf')
            ->select('comf.flag_comf','comf.annule_comf',  'fournisseur.lib_fourn',
                'comf.num_comf' ,'agence.lib_agce','comf.created_at','comf.date_val_comf','comf.prix_ttc_comf' )
            ->join('agence', 'comf.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('fournisseur', 'comf.num_fourn', '=', 'fournisseur.num_fourn', 'inner')
            ->get();
        return view('comfour.index', compact('Resultat'));
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
        $TFournisseur = DB::table('fournisseur')
            ->where('flag_fourn', '=', true)
            ->orderBy('lib_fourn',)->get();
        $TFournisseurList = "<option value='' > Sélectionner </option>";
        foreach ($TFournisseur as $comp) {
            $TFournisseurList .= "<option value='" . $comp->num_fourn . "'      >" . $comp->lib_fourn . "</option>";
        }

        if ($request->isMethod('post')) {
            $request->validate([
                'num_agce' => 'required',
                'num_fourn' => 'required'
            ]);
            $data = $request->all();

            $camp = new Commandefour();
            $camp->id_user = $idUser;
            $camp->num_agce = $data['num_agce'];
            $camp->num_fourn = $data['num_fourn'];
            $camp->save();
            $insertedId = Commandefour::latest()->first()->num_comf;
            //$insertedId);
            // Commandefour::create($request->all());
            return redirect('/comfour/edit/' . Crypt::UrlCrypt($insertedId))->with('success', 'Succes : Enregistrement reussi ');
        }
        return view('comfour.create',
            compact(
                'TFournisseurList',
                'AgenceList',
                'flagValide',
                'flagAnnule'
            ));
    }


    public function edit(Request $request, $id = null)
    {
        $idComFour = Crypt::UrldeCrypt($id);

        $idAgceCon = Auth::user()->num_agce;
        $idUser = Auth::user()->id;

        if ($idComFour == null) {
            return redirect('/comfour/create');
        }
        $comFour = DB::table('commandefour')
            ->where('num_comf', '=', $idComFour)
            ->first();

        $flagValide = $comFour->flag_comf;
        $flagAnnule = $comFour->annule_comf;

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
            if ($comp->num_agce == $comFour->num_agce) {$val = 'selected ';} else { $val = "";}
            $AgenceList .= "<option value='" . $comp->num_agce . "'  $val   >" . $comp->lib_agce . "</option>";
        }
        $TFournisseur = DB::table('fournisseur')
            ->where('flag_fourn', '=', true)
            ->orderBy('lib_fourn',)->get();
        $TFournisseurList = "<option value='' > Sélectionner </option>";
        foreach ($TFournisseur as $comp) {
            if ($comp->num_fourn == $comFour->num_fourn) {$val = 'selected ';} else { $val = "";}
            $TFournisseurList .= "<option value='" . $comp->num_fourn . "'    $val  >" . $comp->lib_fourn . "</option>";
        }
        /******************* Mise a jour de la commande ****************************/
        if ($request->isMethod('post')) {
            $data = $request->all();
            //dd($request->input());
            if ($data['action'] === 'Enregistrer') {
                Commandefour::where('num_comf', '=', $idComFour)->update(['num_fourn' => $data['num_fourn'], 'num_agce' => $data['num_agce']]);
                return redirect('/comfour/edit/' . Crypt::UrlCrypt($idComFour))->with('success', 'Succes : Enregistrement reussi ');
            }
            if ($data['action'] === 'Valider') {
                Commandefour::where('num_comf', '=', $idComFour)->update(['flag_comf' => true], ['num_fourn' => $data['num_fourn'], 'num_agce' => $data['num_agce']]);
                return redirect('/comfour/edit/' . Crypt::UrlCrypt($idComFour))->with('success', 'Succes : Validation reussi ');
            }
        }

        /******************** Mise a jour des produit **********************************/
        if ($request->isMethod('post')) {
            $data = $request->all();
            if ($data['action'] === 'Ajouter') {
                if (!isset($data['num_prod'])){
                    return redirect('/comfour/edit/' . Crypt::UrlCrypt($idComFour))->with('echec', 'Echec : veuillez selection un produit ');
                }
                $ligneComfour = DB::table('ligne_comfour')
                    ->where([['num_prod', '=', $data['num_prod']],['num_comf', '=', $idComFour]])
                    ->first();
                if (isset($ligneComfour)){
                    return redirect('/comfour/edit/' . Crypt::UrlCrypt($idComFour))->with('echec', 'Echec : Le produit a déja été saisi ');
                }
                $infosfournisseur = DB::table('commandefour')
                    ->join('fournisseur','commandefour.num_fourn','fournisseur.num_fourn')
                    ->where([['num_comf','=',$idComFour]])
                    ->first();
                $tva = DB::table('tauxtva')->first();
                $camp = new LigneComfour();
                $camp->num_comf = $idComFour;
                $camp->num_prod = $data['num_prod'];
                $camp->qte_lcomfour = trim($data['qte_lcomfour']);
                $tvaval = $tva->val_taxe;
                if ($infosfournisseur->flag_tva_fourn == true){
                    $prixttc = trim($data['prix_ttc_lcomfour']) + (trim($data['prix_ttc_lcomfour'])*$tvaval/100);
                    $camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                    $camp->prix_ttc_lcomfour = $prixttc;
                    $camp->prix_tva_lcomfour = trim($data['prix_ttc_lcomfour'])*$tvaval/100;
                    $camp->tot_ttc_lcomfour = $prixttc * trim($data['qte_lcomfour']);
                    $camp->tot_ht_lcomfour = trim($data['prix_ttc_lcomfour']) * trim($data['qte_lcomfour']);
                    $camp->tot_tva_lcomfour = (trim($data['prix_ttc_lcomfour'])*$tvaval/100) * trim($data['qte_lcomfour']);
                    $camp->taux_tva_lcomfour = $tva->val_taxe;
                }else{
                    $camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                    $camp->prix_ttc_lcomfour = trim($data['prix_ttc_lcomfour']);
                    $camp->prix_tva_lcomfour = 0;
                    $camp->tot_ttc_lcomfour = trim($data['prix_ttc_lcomfour']) * trim($data['qte_lcomfour']);
                    $camp->tot_ht_lcomfour = trim($data['prix_ttc_lcomfour']) * trim($data['qte_lcomfour']);
                    $camp->tot_tva_lcomfour = 0;
                    $camp->taux_tva_lcomfour = $tva->val_taxe;
                }

                $camp->save();

                return redirect('/comfour/edit/' . Crypt::UrlCrypt($idComFour))->with('success', 'Succes : Enregistrement reussi ');
            }

            if ($data['action'] === 'Modifier') {
                //dd($data);
                $ligneComfour = DB::table('ligne_comfour')
                    ->join('produit', 'ligne_comfour.num_prod', '=', 'produit.num_prod', 'inner')
                    ->where('num_comf', '=', $idComFour)
                    ->get();

                $infosfournisseur = DB::table('commandefour')
                    ->join('fournisseur','commandefour.num_fourn','fournisseur.num_fourn')
                    ->where([['num_comf','=',$idComFour]])
                    ->first();

                $tva = DB::table('tauxtva')->first();
                $tvaval = $tva->val_taxe;
                foreach($ligneComfour as $re):

                    $form =  $data["num_prod/$re->code_prod"];
                    $form1 =  $data["qte_lcomfour/$re->code_prod"];
                    $form2 =  $data["prix_ttc_lcomfour/$re->code_prod"];

                    if ($infosfournisseur->flag_tva_fourn == true){

                        $prixttc = trim($form2) + (trim($form2)*$tvaval/100);

                        //$camp->prix_ht_lcomfour = trim($data['prix_ttc_lcomfour']);
                        //$camp->prix_ttc_lcomfour = $prixttc;
                        //$camp->prix_tva_lcomfour =

                        LigneComfour::where([['num_prod', '=', $form],['num_comf', '=', $idComFour]])->update([
                            'qte_lcomfour' => $form1,
                            'prix_ht_lcomfour' => trim($form2),
                            'prix_ttc_lcomfour' => $prixttc,
                            'prix_tva_lcomfour' => trim($form2)*$tvaval/100,
                            'tot_ttc_lcomfour' => $prixttc * $form1,
                            'tot_ht_lcomfour' => trim($form2) * $form1,
                            'tot_tva_lcomfour' => (trim($form2)*$tvaval/100) * $form1,
                            'taux_tva_lcomfour' => $tvaval,
                        ]);

                    }else{
                        LigneComfour::where([['num_prod', '=', $form],['num_comf', '=', $idComFour]])->update([
                            'qte_lcomfour' => $form1,
                            'prix_ht_lcomfour' => trim($form2),
                            'prix_ttc_lcomfour' => trim($form2),
                            'prix_tva_lcomfour' => 0,
                            'tot_ttc_lcomfour' => trim($form2) * $form1,
                            'tot_ht_lcomfour' => trim($form2) * $form1,
                            'tot_tva_lcomfour' => 0,
                            'taux_tva_lcomfour' => $tvaval,
                        ]);
                    }



                endforeach;

                return redirect('/comfour/edit/' . Crypt::UrlCrypt($idComFour))->with('success', 'Succes : Enregistrement reussi ');
            }
        }

        $Produit = DB::table('produit')
            ->where('flag_prod', '=', true)
            ->orderBy('lib_prod',)->get();
        $ProduitList = "<option value='' > Sélectionner </option>";
        foreach ($Produit as $comp) {
            $ProduitList .= "<option value='" . $comp->num_prod . "' >" . $comp->code_prod . ' : ' . $comp->lib_prod . "</option>";
        }
        $ligneComfour = DB::table('ligne_comfour')
            ->join('produit', 'ligne_comfour.num_prod', '=', 'produit.num_prod', 'inner')
            ->where('num_comf', '=', $idComFour)
            ->get();

        return view('comfour.edit',
            compact(
                'TFournisseurList',
                'AgenceList',
                'flagValide',
                'flagAnnule', 'comFour', 'ProduitList', 'ligneComfour'
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
        $ligneComfour = DB::table('ligne_comfour')
            ->where('num_lcomfour', '=', $idLcomf)
            ->first();
        DB::table('ligne_comfour')->where('num_lcomfour', $idLcomf)->delete();
         return redirect('/comfour/edit/' . Crypt::UrlCrypt($ligneComfour->num_comf))->with('success', 'Succes : Suppression reussi ');

    }

    public function etat($id=null){

        $idComfour = Crypt::UrldeCrypt($id);

        $idAgceCon = Auth::user()->num_agce;
        $idUser = Auth::user()->id;

        if ($idComfour == null) {
            return redirect('/comfour/create');
        }
        $Result  = DB::table('commandefour as com')
            ->join('agence', 'com.num_agce', '=', 'agence.num_agce', 'inner')
            ->join('fournisseur', 'com.num_fourn', '=', 'fournisseur.num_fourn', 'inner')
            ->where('num_comf', '=', $idComfour)
            ->first();
//dd($Result);
        $flagValide = $Result->flag_comf;
        $flagAnnule = $Result->annule_comf;

        $ligneResult = DB::table('ligne_comfour')
            ->join('produit', 'ligne_comfour.num_prod', '=', 'produit.num_prod', 'inner')
            ->where('num_comf', '=', $idComfour)
            ->get();
        //dd($ligneResult);
        return view('comfour.etat',
            compact(

                'flagValide',
                'flagAnnule', 'Result',  'ligneResult'
            ));

    }



}
