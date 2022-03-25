<?php

namespace App\Http\Controllers;

use App\Helpers\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TableaudebordController extends Controller
{
    public function  mouvementstock(Request $request){

        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = '';
        $date1=0;
        $date2=0;
        $ProduitList1=0;


        if ($request->isMethod('post')) {

            $data = $request->all();

            //  dd($data);

            $condition = '';

            if (isset($data['date1'])) {
                $date1 = $data['date1'];
                $condition = $condition . ' and  msp.date_mvstck > ' . "'$date1'";
            }

            if (isset($data['date2'])) {
                $date2 = $data['date2'];
                $condition = $condition . '  and msp.date_mvstck < ' . "'$date2'";
            }


            if (isset($data['prod'])) {
                $ProduitList1 = $data['prod'];
                $condition = $condition . ' and p.num_prod= ' . $ProduitList1;
            }

            $recherches =  DB::select(
                DB::raw(
                    'select * from mvt_stock_prod msp
                          inner join produit p on msp.num_prod = p.num_prod
                          where 1=1   ' . $condition . '
                        '),

            );

            //dd($recherches);
            if(count($recherches)==0){

                return redirect('/mouvementstock')
                    ->with('errors', 'Aucun resultat trouvés');
            }

        }

        $Produit = DB::table('produit')
            ->where('flag_prod', '=', true)
            ->orderBy('lib_prod',)->get();
        $ProduitList = "<option value='' > Sélectionner </option>";
        foreach ($Produit as $comp) {
            $ProduitList .= "<option value='" . $comp->num_prod . "' >" . $comp->code_prod . ' : ' . $comp->lib_prod . "</option>";
        }

        return view('tableaudebord.mouvementstock', compact(
            'ProduitList','recherches', 'date1', 'date2', 'ProduitList1'
        ));
    }

    public function  apercuemouvementstock($id=null, $id1=null, $id2=null){

        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = [];





        //  dd($data);

        $condition = '';

        if ($id != 0) {
            $date1 = $id;
            $condition = $condition . ' and  msp.date_mvstck > ' . "'$date1'";
        }

        if ($id1 != 0) {
            $date2 = $id1;
            $condition = $condition . ' and  msp.date_mvstck < ' . "'$date2'";
        }



        if ($id2 != 0) {
            $ProduitList1 = $id2;
            $condition = $condition . ' and p.num_prod= ' . $ProduitList1;
        }

        $recherches =  DB::select(
            DB::raw(
                'select * from mvt_stock_prod msp
                          inner join produit p on msp.num_prod = p.num_prod
                          where 1=1   ' . $condition . '
                        '),

        );

        return view('tableaudebord.apercuemouvementstock',compact(
            'recherches', 'id', 'id1', 'id2'
        ));
    }

    public function  historiqueventedirecte(Request $request){

        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = '';
        $date1=0;
        $date2=0;


        if ($request->isMethod('post')) {

            $data = $request->all();

            //  dd($data);

            $condition = '';

            if (isset($data['date1'])) {
                $date1 = $data['date1'];
                $condition = $condition . ' and  bl.date_val_bl > ' . "'$date1'";
            }

            if (isset($data['date2'])) {
                $date2 = $data['date2'];
                $condition = $condition . '  and bl.date_val_bl < ' . "'$date2'";
            }


            $recherches =  DB::select(
                DB::raw(
                    "select left (c.code_comc, 1) as code, f.solde_fact, f.prix_ttc_fact , bl.date_val_bl ,
                            c.code_comc , c.solde_comc, c2.nom_cli , c2.prenom_cli
                            from facture f
                            inner join bon_livraison bl on f.num_bl = bl.num_bl
                            inner join commandeclient c on bl.num_comc = c.num_comc
                            inner join client c2 on c.num_cli = c2.num_cli
                            where left (c.code_comc, 1) = 'V' and f.solde_fact = true " . $condition . "
                        "),

            );

            //dd($recherches);
            if(count($recherches)==0){

                return redirect('/historiqueventedirecte')
                    ->with('errors', 'Aucun resultat trouvés');
            }

        }



        return view('tableaudebord.historiqueventedirecte',compact(
            'recherches', 'date1', 'date2',
        ));

        return view('tableaudebord.historiqueventedirecte');
    }

    public function  apercuehistoriqueventedirecte($id=null, $id1=null){

        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = [];





        //  dd($data);

        $condition = '';

        if ($id != 0) {
            $date1 = $id;
            $condition = $condition . ' and  bl.date_val_bl > ' . "'$date1'";
        }

        if ($id1 != 0) {
            $date2 = $id1;
            $condition = $condition . ' and  bl.date_val_bl < ' . "'$date2'";
        }


        $recherches =  DB::select(
            DB::raw(
                "select left (c.code_comc, 1) as code, f.solde_fact, f.prix_ttc_fact , bl.date_val_bl ,
                            c.code_comc , c.solde_comc, c2.nom_cli , c2.prenom_cli
                            from facture f
                            inner join bon_livraison bl on f.num_bl = bl.num_bl
                            inner join commandeclient c on bl.num_comc = c.num_comc
                            inner join client c2 on c.num_cli = c2.num_cli
                            where left (c.code_comc, 1) = 'V' and f.solde_fact = true " . $condition . "
                        "),

        );

        return view('tableaudebord.apercuehistoriqueventedirecte',compact(
            'recherches', 'id', 'id1',
        ));
    }

    public function  historiquecreanceclient(Request $request){

        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = '';
        $date1=0;
        $date2=0;
        $TClientList1=0;


        if ($request->isMethod('post')) {

            $data = $request->all();

            //  dd($data);

            $condition = '';

            if (isset($data['date1'])) {
                $date1 = $data['date1'];
                $condition = $condition . ' and  f.date_val_fact > ' . "'$date1'";
            }

            if (isset($data['date2'])) {
                $date2 = $data['date2'];
                $condition = $condition . '  and f.date_val_fact < ' . "'$date2'";
            }


            if (isset($data['prod'])) {
                $TClientList1 = $data['prod'];
                $condition = $condition . ' and c.num_cli = ' . $TClientList1;
            }

            $recherches =  DB::select(
                DB::raw(
                    'select f.num_fact, f.code_fact , f.prix_ttc_fact , c.nom_cli , c.prenom_cli ,
                            (select sum(r.montant_ttc_reg) from reglement r where r.num_fact = f.num_fact) as montantpaye, f.date_val_fact
                            from facture f
                            inner join client c on f.num_cli = c.num_cli
                            where f.solde_fact = false and f.flag_fact = true   ' . $condition . '
                        '),

            );

            //dd($recherches);
            if(count($recherches)==0){

                return redirect('/historiquecreanceclient')
                    ->with('errors', 'Aucun resultat trouvés');
            }

        }


        $TClient = DB::table('client')
            ->where('flag_cli', '=', true)
            ->orderBy('nom_cli',)->get();
        $TClientList = "<option value='' > Sélectionner </option>";
        foreach ($TClient as $comp) {
            $TClientList .= "<option value='" . $comp->num_cli . "'      >" . $comp->code_cli . ' : ' . $comp->nom_cli . ' ' . $comp->prenom_cli . "</option>";
        }

        return view('tableaudebord.historiquecreanceclient',compact(
            'TClientList','recherches', 'date1', 'date2', 'TClientList1'
        ));
    }

    public function  apercuehistoriquecreanceclientt($id=null, $id1=null, $id2=null){

        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = [];





        //  dd($data);

        $condition = '';

        if ($id != 0) {
            $date1 = $id;
            $condition = $condition . ' and  f.date_val_fact > ' . "'$date1'";
        }

        if ($id1 != 0) {
            $date2 = $id1;
            $condition = $condition . ' and  f.date_val_fact < ' . "'$date2'";
        }



        if ($id2 != 0) {
            $TClientList1 = $id2;
            $condition = $condition . ' and c.num_cli = ' . $TClientList1;
        }

        $recherches =  DB::select(
            DB::raw(
                'select f.num_fact, f.code_fact , f.prix_ttc_fact , c.nom_cli , c.prenom_cli ,
                            (select sum(r.montant_ttc_reg) from reglement r where r.num_fact = f.num_fact) as montantpaye, f.date_val_fact
                            from facture f
                            inner join client c on f.num_cli = c.num_cli
                            where f.solde_fact = false and f.flag_fact = true ' . $condition . '
                        '),

        );

        return view('tableaudebord.apercuehistoriquecreanceclientt',compact(
            'recherches', 'id', 'id1', 'id2'
        ));
    }

    public function  historiqueventeindirecte(Request $request, $id = null){

        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = '';
        $date1=0;
        $date2=0;
        $TClientList1=0;
        $statut=0;


        if ($request->isMethod('post')) {

            $data = $request->all();

            //  dd($data);

            $condition = '';

            if (isset($data['date1'])) {
                $date1 = $data['date1'];
                $condition = $condition . ' and  bl.date_val_bl > ' . "'$date1'";
            }

            if (isset($data['date2'])) {
                $date2 = $data['date2'];
                $condition = $condition . '  and bl.date_val_bl < ' . "'$date2'";
            }


            if (isset($data['prod'])) {
                $TClientList1 = $data['prod'];
                $condition = $condition . ' and c.num_cli = ' . $TClientList1;
            }

            if (isset($data['statut'])) {
                $statut = $data['statut'];
                $condition = $condition . ' and f.solde_fact = ' . $statut;
            }

            $recherches =  DB::select(
                DB::raw(
                    "select left (c.code_comc, 1) as code, f.solde_fact, f.flag_fact, f.prix_ttc_fact , bl.date_val_bl ,
                            c.code_comc , c.solde_comc, c2.nom_cli , c2.prenom_cli
                            from facture f
                            inner join bon_livraison bl on f.num_bl = bl.num_bl
                            inner join commandeclient c on bl.num_comc = c.num_comc
                            inner join client c2 on c.num_cli = c2.num_cli
                            where left (c.code_comc, 1) = 'B' " . $condition . "
                        "),

            );

            //dd($recherches);
            if(count($recherches)==0){

                return redirect('/historiqueventeindirecte')
                    ->with('errors', 'Aucun resultat trouvés');
            }

        }


        $TClient = DB::table('client')
            ->where('flag_cli', '=', true)
            ->orderBy('nom_cli',)->get();
        $TClientList = "<option value='' > Sélectionner </option>";
        foreach ($TClient as $comp) {
            $TClientList .= "<option value='" . $comp->num_cli . "'      >" . $comp->code_cli . ' : ' . $comp->nom_cli . ' ' . $comp->prenom_cli . "</option>";
        }

        return view('tableaudebord.historiqueventeindirecte',compact(
            'TClientList','recherches', 'date1', 'date2', 'TClientList1','statut'
        ));
    }

    public function  apercuehistoriqueventeindirecte($id=null, $id1=null, $id2=null, $id3=null){

        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = [];


        //  dd($data);

        $condition = '';

        if ($id != 0) {
            $date1 = $id;
            $condition = $condition . ' and  bl.date_val_bl > ' . "'$date1'";
        }

        if ($id1 != 0) {
            $date2 = $id1;
            $condition = $condition . ' and  bl.date_val_bl < ' . "'$date2'";
        }



        if ($id2 != 0) {
            $TClientList1 = $id2;
            $condition = $condition . ' and c.num_cli = ' . $TClientList1;
        }

        if ($id3 == true) {
            $statut = $id3;
            $condition = $condition . ' and f.solde_fact = ' . $statut;
        }elseif ($id3 === false){
            $statut = $id3;
            $condition = $condition . ' and f.solde_fact = ' . $statut;
        }else{
            $condition = $condition;
        }

        $recherches =  DB::select(
            DB::raw(
                "select left (c.code_comc, 1) as code, f.solde_fact, f.flag_fact, f.prix_ttc_fact , bl.date_val_bl ,
                            c.code_comc , c.solde_comc, c2.nom_cli , c2.prenom_cli
                            from facture f
                            inner join bon_livraison bl on f.num_bl = bl.num_bl
                            inner join commandeclient c on bl.num_comc = c.num_comc
                            inner join client c2 on c.num_cli = c2.num_cli
                            where left (c.code_comc, 1) = 'B'  " . $condition . "
                        "),

        );

        return view('tableaudebord.apercuehistoriqueventeindirecte',compact(
            'recherches', 'id', 'id1', 'id2'
        ));
    }

    public function historiquereglement(Request $request){
        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = '';
        $date1=0;
        $date2=0;
        $TClientList1=0;
        $typereglement=0;

        if ($request->isMethod('post')) {

            $data = $request->all();

            //  dd($data);

            $condition = '';

            if (isset($data['date1'])) {
                $date1 = $data['date1'];
                $condition = $condition . ' and  r.created_at > ' . "'$date1'";
            }

            if (isset($data['date2'])) {
                $date2 = $data['date2'];
                $condition = $condition . '  and r.created_at < ' . "'$date2'";
            }


            if (isset($data['prod'])) {
                $TClientList1 = $data['prod'];
                $condition = $condition . ' and c.num_cli = ' . $TClientList1;
            }

            if (isset($data['num_mpaie'])) {
                $typereglement = $data['num_mpaie'];
                $condition = $condition . ' and mp.num_mpaie = ' . $typereglement;
            }

            $recherches =  DB::select(
                DB::raw(
                    "select f.num_fact , f.solde_fact , f.prix_ttc_fact , r.montant_ttc_reg , r.created_at ,
                            mp.lib_mpaie , c.nom_cli , c.prenom_cli , c.num_cli, mp.num_mpaie
                            from facture f
                            inner join reglement r on f.num_fact = r.num_fact
                            inner join mode_paiement mp on r.num_mpaie = mp.num_mpaie
                            inner join client c on f.num_cli = c.num_cli
                            where f.flag_fact = true " . $condition . "
                        "),

            );

            //dd($recherches);
            if(count($recherches)==0){

                return redirect('/historiquereglement')
                    ->with('errors', 'Aucun resultat trouvés');
            }

        }



        $TClient = DB::table('client')
            ->where('flag_cli', '=', true)
            ->orderBy('nom_cli',)->get();
        $TClientList = "<option value='' > Sélectionner </option>";
        foreach ($TClient as $comp) {
            $TClientList .= "<option value='" . $comp->num_cli . "'      >" . $comp->code_cli . ' : ' . $comp->nom_cli . ' ' . $comp->prenom_cli . "</option>";
        }

        $Modepaie = DB::table('mode_paiement')
            ->where('flag_mpaie', '=', true)
            ->orderBy('lib_mpaie',)
            ->get();
        $ModepaieList = "<option value='' > Sélectionner </option>";

        foreach ($Modepaie as $comp) {
            $ModepaieList .= "<option value='" . $comp->num_mpaie . "' >" . $comp->lib_mpaie . "</option>";
        }

        return view('tableaudebord.historiquereglement', compact(
            'recherches','TClientList', 'ModepaieList','date1', 'date2','typereglement','TClientList1'
        ));
    }

    public function historiquebonreception(Request $request){

        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = '';
        $date1=0;
        $date2=0;
        $TClientList1=0;
        $statut=0;


        if ($request->isMethod('post')) {

            $data = $request->all();

            //  dd($data);

            $condition = '';

            if (isset($data['date1'])) {
                $date1 = $data['date1'];
                $condition = $condition . ' and  bl.date_val_bl > ' . "'$date1'";
            }

            if (isset($data['date2'])) {
                $date2 = $data['date2'];
                $condition = $condition . '  and bl.date_val_bl < ' . "'$date2'";
            }


            if (isset($data['prod'])) {
                $TClientList1 = $data['prod'];
                $condition = $condition . ' and f.num_fourn = ' . $TClientList1;
            }

            if (isset($data['statut'])) {
                $statut = $data['statut'];
                $condition = $condition . ' and rf.flag_br = ' . $statut;
            }

            $recherches =  DB::select(
                DB::raw(
                    "select rf.num_br , rf.date_cre_br , rf.flag_br , rf.mont_br , rf.annule_br , rf.prix_ttc_br , rf.prix_ht_br , rf.prix_tva_br ,
                            f.lib_fourn
                            from reception_four rf
                            inner join commandefour c on rf.num_comf = c.num_comf
                            inner join fournisseur f on c.num_fourn = f.num_fourn  " . $condition . "
                        "),

            );

            //dd($recherches);
            if(count($recherches)==0){

                return redirect('/historiquebonreception')
                    ->with('errors', 'Aucun resultat trouvés');
            }

        }


        $TClient = DB::table('fournisseur')
            ->where('flag_fourn', '=', true)
            ->orderBy('num_fourn',)->get();
        $TClientList = "<option value='' > Sélectionner </option>";
        foreach ($TClient as $comp) {
            $TClientList .= "<option value='" . $comp->num_fourn . "'      >" . $comp->num_fourn . ' : ' . $comp->lib_fourn .  "</option>";
        }

        return view('tableaudebord.historiquebonreception', compact(
            'recherches','TClientList', 'date1', 'date2','statut', 'TClientList1'
        ));
    }

    public function historiquebonlivraison(Request $request){

        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = '';
        $date1=0;
        $date2=0;
        $TClientList1=0;
        $statut=0;


        if ($request->isMethod('post')) {

            $data = $request->all();

            //  dd($data);

            $condition = '';

            if (isset($data['date1'])) {
                $date1 = $data['date1'];
                $condition = $condition . ' and  bl.date_val_bl > ' . "'$date1'";
            }

            if (isset($data['date2'])) {
                $date2 = $data['date2'];
                $condition = $condition . '  and bl.date_val_bl < ' . "'$date2'";
            }


            if (isset($data['prod'])) {
                $TClientList1 = $data['prod'];
                $condition = $condition . ' and c.num_cli = ' . $TClientList1;
            }

            if (isset($data['statut'])) {
                $statut = $data['statut'];
                $condition = $condition . ' and bl.flag_bl = ' . $statut;
            }

            $recherches =  DB::select(
                DB::raw(
                    "select bl.num_bl , bl.num_comc , bl.date_val_bl , bl.prix_ttc_bl , bl.prix_ht_bl , bl.prix_tva_bl ,
                            c2.num_cli , c2.nom_cli  , c2.prenom_cli, bl.flag_bl
                            from bon_livraison bl
                            inner join commandeclient c on bl.num_comc = c.num_comc
                            inner join client c2 on c.num_cli = c2.num_cli
                            where left (c.code_comc, 1) = 'B' " . $condition . "
                        "),

            );

            //dd($recherches);
            if(count($recherches)==0){

                return redirect('/historiquebonlivraison')
                    ->with('errors', 'Aucun resultat trouvés');
            }

        }


        $TClient = DB::table('client')
            ->where('flag_cli', '=', true)
            ->orderBy('nom_cli',)->get();
        $TClientList = "<option value='' > Sélectionner </option>";
        foreach ($TClient as $comp) {
            $TClientList .= "<option value='" . $comp->num_cli . "'      >" . $comp->code_cli . ' : ' . $comp->nom_cli . ' ' . $comp->prenom_cli . "</option>";
        }

        return view('tableaudebord.historiquebonlivraison', compact(
            'recherches','TClientList', 'date1', 'date2', 'statut', 'TClientList1'
        ));
    }

    public function historiqueproformat(Request $request){

        $idutil = Auth::user()->id;
        $naroles = Menu::get_menu_profil($idutil);

        $recherches = '';
        $date1=0;
        $date2=0;
        $TClientList1=0;
        $statut=0;


        if ($request->isMethod('post')) {

            $data = $request->all();

            //  dd($data);

            $condition = '';

            if (isset($data['date1'])) {
                $date1 = $data['date1'];
                $condition = $condition . ' and  c.date_cre_comc > ' . "'$date1'";
            }

            if (isset($data['date2'])) {
                $date2 = $data['date2'];
                $condition = $condition . '  and c.date_cre_comc < ' . "'$date2'";
            }


            if (isset($data['prod'])) {
                $TClientList1 = $data['prod'];
                $condition = $condition . ' and c.num_cli = ' . $TClientList1;
            }

            if (isset($data['statut'])) {
                $statut = $data['statut'];
                $condition = $condition . ' and c.flag_comc = ' . $statut;
            }

            $recherches =  DB::select(
                DB::raw(
                    "select c.num_comc , c.code_comc , c.date_cre_comc , c.prix_ttc_comc , c.mont_comc , c2.nom_cli , c2.prenom_cli , c.flag_comc
                            from commandeclient c
                            inner join client c2 on c.num_cli = c2.num_cli
                            where left (c.code_comc, 1) = 'B'" . $condition . "
                        "),

            );

            //dd($recherches);
            if(count($recherches)==0){

                return redirect('/historiqueproformat')
                    ->with('errors', 'Aucun resultat trouvés');
            }

        }


        $TClient = DB::table('client')
            ->where('flag_cli', '=', true)
            ->orderBy('nom_cli',)->get();
        $TClientList = "<option value='' > Sélectionner </option>";
        foreach ($TClient as $comp) {
            $TClientList .= "<option value='" . $comp->num_cli . "'      >" . $comp->code_cli . ' : ' . $comp->nom_cli . ' ' . $comp->prenom_cli . "</option>";
        }

        return view('tableaudebord.historiqueproformat', compact(
            'recherches','TClientList', 'date1', 'date2', 'statut', 'TClientList1'
        ));
    }

    public function detailbonreception($id=null){

        $Resultat = DB::select(
            DB::raw(
                "
                select p.code_prod , p.lib_prod , lb.qte_lbr, lb.num_br  from ligne_br lb
                inner join produit p on lb.num_prod = p.num_prod
                where lb.num_br=:numbr
                      "),
                array(
                    'numbr' => $id
                )

        );

        return view('tableaudebord.detailbonreception',compact(
            'Resultat'
        ));
    }

    public function detailbonlivraison($id=null){

        $Resultat = DB::select(
            DB::raw(
                "
                select p.code_prod , p.lib_prod , lb.qte_lbl , lb.num_bl  from ligne_bl lb
                inner join produit p on lb.num_prod = p.num_prod
                where lb.num_bl=:numbr
                      "),
            array(
                'numbr' => $id
            )

        );

        return view('tableaudebord.detailbonlivraison',compact(
            'Resultat'
        ));
    }

    public function detailprofromat($id=null){

        $Resultat = DB::select(
            DB::raw(
                "
               select p.code_prod , p.lib_prod , lc.qte_lcomc  , lc.num_comc  from  ligne_com lc
                inner join produit p on lc.num_prod  = p.num_prod
                where lc.num_comc=:numbr
                      "),
            array(
                'numbr' => $id
            )

        );

        return view('tableaudebord.detailprofromat',compact(
            'Resultat'
        ));
    }


}
