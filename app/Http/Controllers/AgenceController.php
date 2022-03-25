<?php

namespace App\Http\Controllers;

use App\Models\Agence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AgenceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Resultat = DB::table('agence')->get();
        return view('agence.index', compact('Resultat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('agence.create');
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
            'lib_agce' => 'required'
        ]);
        if ($request->isMethod('post')) {
            Agence::create($request->all());
            return redirect()->route('agence.index')->with('success', 'Enregistrement reussi.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Agence $agence
     * @return \Illuminate\Http\Response
     */
    public function edit(Agence $agence)
    {
        return view('agence.edit', compact('agence'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Agence $agence
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agence $agence)
    {
        $request->validate([
            'lib_agce' => 'required'
        ]);
        $agence->update($request->all());
        return redirect()->route('agence.index')->with('success', 'Mise à jour reussie.');
    }

}
