<?php

namespace App\Http\Controllers;

use App\Models\Famille;
use App\Models\TypeClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TypeClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $Resultat = DB::table('type_client')->get();
        return view('typeclient.index', compact('Resultat'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('typeclient.create');
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
            'lib_typecli' => 'required'
        ]);
        if ($request->isMethod('post')) {
            TypeClient::create($request->all());
            return redirect()->route('typeclient.index')->with('success', 'Enregistrement reussi.');
        }
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\TypeClient $typeclient
     * @return \Illuminate\Http\Response
     */
    public function edit(TypeClient $typeclient)
    {
        return view('typeclient.edit', compact('typeclient'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\TypeClient $typeclient
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TypeClient $typeclient)
    {
        $request->validate([
            'lib_typecli' => 'required'
        ]);
        $typeclient->update($request->all());
        return redirect()->route('typeclient.index')->with('success', 'Mise à jour reussie.');
    }

}
