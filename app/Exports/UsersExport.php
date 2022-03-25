<?php

namespace App\Exports;

use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class UsersExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return DB::table('vue_stock_prod_fam')
            ->select( 'num_prod','code_prod','code_barre_prod', 'lib_prod','lib_sousfam','lib_fam',
                DB::raw('SUM(qte_sortie) as qte_sortie' ),
                DB::raw('SUM(qte_entree) as qte_entree' ) )
            ->groupBy('num_prod', 'lib_prod','code_prod', 'code_barre_prod','lib_sousfam','lib_fam' )
            ->get();
    }
}
