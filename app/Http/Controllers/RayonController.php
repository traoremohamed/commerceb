<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Auth;
use Session;
use App\Models\Rayon;
use Illuminate\Http\Request;

class RayonController extends Controller
{
    public function rayon(){
        $rayons =DB::table('rayon')->get();
//dd($rayons);
        return view('rayon.index',compact('rayons'));
    }

    public function creerrayon(Request $request){

        $idutil = Auth::user()->id;

        if ($request->isMethod('post')) {

            $data = $request->all();

            // $idrayon = DB::table('p_rayon')->latest('code_clt')->first();

            $pray = new Rayon();

            $pray->code_ray = $data['code_ray'];
            $pray->lib_ray = $data['lib_ray'];
            $pray->code_fa = $data['code_fa'];

            $pray->save();

            return redirect('/rayon')->with('success','enregistrement effectué');

        }

        $famillearticles =  DB::select(DB::raw('select  * from famille '),

        );

        $famillearticle = "<option selected > Selectionner une famille :</option>";
        foreach ($famillearticles as $comp) {
            $famillearticle .= "<option value='" . $comp->num_fam  . "'>" . $comp->lib_fam . "</option>";
        }

        return view('rayon.creer',compact('famillearticle'));
    }
}
