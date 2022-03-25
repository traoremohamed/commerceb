<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_lcomfour
 * @property float $num_prod
 * @property float $num_comf
 * @property float $qte_lcomfour
 * @property float $prix_ttc_lcomfour
 * @property float $prix_ht_lcomfour
 * @property float $prix_tva_lcomfour
 * @property float $prix_net_lcomfour
 * @property boolean $flag_tva_lcomfour
 * @property float $remise_lcomfour
 * @property float $tot_ttc_lcomfour
 * @property float $tot_ht_lcomfour
 * @property float $tot_tva_lcomfour
 * @property float $mt_remise_lcomfour
 * @property float $taux_tva_lcomfour
 * @property Commandefour $commandefour
 * @property Produit $produit
 */
class LigneComfour extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'ligne_comfour';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'num_lcomfour';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'float';

    /**
     * @var array
     */
    protected $fillable = ['num_prod', 'num_comf', 'qte_lcomfour', 'prix_ttc_lcomfour', 'prix_ht_lcomfour', 'prix_tva_lcomfour', 'prix_net_lcomfour', 'flag_tva_lcomfour', 'remise_lcomfour', 'tot_ttc_lcomfour', 'tot_ht_lcomfour', 'tot_tva_lcomfour', 'mt_remise_lcomfour', 'taux_tva_lcomfour'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commandefour()
    {
        return $this->belongsTo('App\Models\Commandefour', 'num_comf', 'num_comf');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produit()
    {
        return $this->belongsTo('App\Models\Produit', 'num_prod', 'num_prod');
    }
}
