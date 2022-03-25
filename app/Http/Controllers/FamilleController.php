<?php

namespace App\Http\Controllers;

use App\Models\Famille;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FamilleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Resultat = DB::table('famille')->get();
        return view('famille.index', compact('Resultat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('famille.create');
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
            'lib_fam' => 'required'
        ]);
        if ($request->isMethod('post')) {
            Famille::create($request->all());
            return redirect()->route('famille.index')->with('success', 'Enregistrement reussi.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Famille $famille
     * @return \Illuminate\Http\Response
     */
    public function edit(Famille $famille)
    {
        return view('famille.edit', compact('famille'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Famille $famille
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Famille $famille)
    {
        $request->validate([
            'lib_fam' => 'required'
        ]);
        $famille->update($request->all());
        return redirect()->route('famille.index')->with('success', 'Mise à jour reussie.');
    }

}
