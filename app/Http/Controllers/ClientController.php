<?php

namespace App\Http\Controllers;

use App\Helpers\GenerateCode as Gencode;
use App\Models\Agence;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $Resultat = DB::table('client')
            ->join('type_client', 'client.num_typecli', '=', 'type_client.num_typecli', 'inner')
            ->join('agence', 'client.num_agce', '=', 'agence.num_agce', 'inner')
            ->get();
        return view('client.index', compact('Resultat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        $idAgceCon = Auth::user()->num_agce;
        // dd($idAgceCon);
        if (isset($idAgceCon)) {
            $Agence = DB::table('agence')
                ->where([['flag_agce', '=', true], ['num_agce', '=', $idAgceCon]])
                ->orderBy('lib_agce',)->get();
        } else {
            $Agence = DB::table('agence')
                ->where('flag_agce', '=', true)
                ->orderBy('lib_agce',)->get();
        }

        $AgenceList = "<option value='' > Sélectionner </option>";
        foreach ($Agence as $comp) {
            //   if ($comp->type_client_num == $client->type_client_num) $val='selected ';
            $AgenceList .= "<option value='" . $comp->num_agce . "'      >" . $comp->lib_agce . "</option>";
        }
        $Tclient = DB::table('type_client')
            ->where('flag_typecli', '=', true)
            ->orderBy('lib_typecli',)->get();
        $TclientList = "<option value='' > Sélectionner </option>";
        foreach ($Tclient as $comp) {
            //   if ($comp->type_client_num == $client->type_client_num) $val='selected ';
            $TclientList .= "<option value='" . $comp->num_typecli . "'      >" . $comp->lib_typecli . "</option>";
        }
        $codeClient='C-'.Gencode::randStrGen(4,5);
        return view('client.create', compact('AgenceList', 'TclientList','codeClient'));
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
            'nom_cli' => 'required',
            'num_agce' => 'required',
            'num_typecli' => 'required'
        ]);
        if ($request->isMethod('post')) {
            Client::create($request->all());
            return redirect()->route('client.index')->with('success', 'Enregistrement reussi.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Client $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Client $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        $idAgceCon = Auth::user()->num_agce;
        // dd($idAgceCon);
        if (isset($idAgceCon)) {
            $Agence = DB::table('agence')
                ->where([['flag_agce', '=', true], ['num_agce', '=', $idAgceCon]])
                ->orderBy('lib_agce',)->get();
        } else {
            $Agence = DB::table('agence')
                ->where('flag_agce', '=', true)
                ->orderBy('lib_agce',)->get();
        }

        $AgenceList = "<option value='' > Sélectionner </option>";
        foreach ($Agence as $comp) {
               if ($comp->num_agce == $client->num_agce) $val='selected ';
            $AgenceList .= "<option value='" . $comp->num_agce . "'   $val   >" . $comp->lib_agce . "</option>";
        }
        $Tclient = DB::table('type_client')
            ->where('flag_typecli', '=', true)
            ->orderBy('lib_typecli',)->get();
        $TclientList = "<option value='' > Sélectionner </option>";
        foreach ($Tclient as $comp) {
               if ($comp->num_typecli == $client->num_typecli) $val='selected ';
            $TclientList .= "<option value='" . $comp->num_typecli . "'   $val   >" . $comp->lib_typecli . "</option>";
        }
        return view('client.edit', compact('client','AgenceList', 'TclientList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Client $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $request->validate([
            'nom_cli' => 'required',
            'num_agce' => 'required',
            'num_typecli' => 'required'
        ]);
        $client->update($request->all());
        return redirect()->route('client.index')->with('success', 'Mise à jour reussie.');
    }

}
