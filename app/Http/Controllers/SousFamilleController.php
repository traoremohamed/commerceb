<?php

namespace App\Http\Controllers;

use App\Models\SousFamille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SousFamilleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Resultat = DB::table('sous_famille')
            ->join('famille', 'sous_famille.num_fam', '=', 'famille.num_fam', 'inner')
            ->get();
        return view('sousfamille.index', compact('Resultat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $Famille = DB::table('famille')
            ->where('flag_fam', '=', true)
            ->orderBy('lib_fam',)->get();
        $FamilleList = "<option value='' > Sélectionner </option>";
        foreach ($Famille as $comp) {
            $FamilleList .= "<option value='" . $comp->num_fam . "'      >" . $comp->lib_fam . "</option>";
        }
        return view('sousfamille.create',compact('FamilleList'));
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
            'lib_sousfam' => 'required',
            'num_fam' => 'required'
        ]);
        if ($request->isMethod('post')) {
            SousFamille::create($request->all());
            return redirect()->route('sousfamille.index')->with('success', 'Enregistrement reussi.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\SousFamille $sousfamille
     * @return \Illuminate\Http\Response
     */
    public function edit(SousFamille $sousfamille)
    {
        $Famille = DB::table('famille')
            ->where('flag_fam', '=', true)
            ->orderBy('lib_fam',)->get();
        $FamilleList = "<option value='' > Sélectionner </option>";
        foreach ($Famille as $comp) {
               if ($comp->num_fam == $sousfamille->num_fam) $val='selected ';
            $FamilleList .= "<option value='" . $comp->num_fam . "'  $val    >" . $comp->lib_fam . "</option>";
        }
        return view('sousfamille.edit', compact('sousfamille','FamilleList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\SousFamille $sousfamille
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SousFamille $sousfamille)
    {

        $request->validate([
            'lib_sousfam' => 'required',
            'num_fam' => 'required'
        ]);
        $sousfamille->update($request->all());
        return redirect()->route('sousfamille.index')->with('success', 'Mise à jour reussie.');
    }

}
