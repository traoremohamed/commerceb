<?php

namespace App\Http\Controllers;

use App\Models\SousRayon;
use DB;
use Hash;
use Auth;
use Session;
use Illuminate\Http\Request;

class SousRayonController extends Controller
{
    public function sousrayon(){
        $sousrayons =DB::table('sous_rayon')
            ->join('rayon','sous_rayon.code_ray','rayon.code_ray')
            ->join('famille','rayon.code_fa','famille.num_fam')
            ->get();

        //  dd($sousrayons);

        return view('sousrayon.index',compact('sousrayons'));
    }

    public function creersousrayon(Request $request){

        $idutil = Auth::user()->id;

        if ($request->isMethod('post')) {

            $data = $request->all();

            // $idrayon = DB::table('p_rayon')->latest('code_clt')->first();

            $pray = new SousRayon();

            $pray->code_sr = $data['code_sr'];
            $pray->lib_sr = $data['lib_sr'];
            $pray->code_ray = $data['code_ray'];

            $pray->save();

            return redirect('/sousrayon')->with('success','enregistrement effectué');

        }

        $rayons =  DB::select(DB::raw('select  * from rayon '),

        );

        $rayon = "<option selected > Selectionner un rayon :</option>";
        foreach ($rayons as $comp) {
            $rayon .= "<option value='" . $comp->code_ray  . "'>" . $comp->lib_ray . "</option>";
        }

        return view('sousrayon.creer',compact('rayon'));

    }
}
