<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_br
 * @property float $num_prod
 * @property float $num_lbr
 * @property float $qte_lbr
 * @property float $prix_ttc_lbr
 * @property float $prix_ht_lbr
 * @property float $prix_tva_lbr
 * @property float $prix_net_lbr
 * @property boolean $flag_tva_lbr
 * @property int $taux_tva_lbr
 * @property float $remise_lbr
 * @property float $tot_ttc_lbr
 * @property float $tot_ht_lbr
 * @property float $tot_tva_lbr
 * @property integer $flag_stock_ln
 * @property float $frais_app_lbr
 * @property float $revient_lbr
 * @property float $mt_remise_lbr
 * @property float $num_lcomfour
 * @property integer $flag_stck_tpep
 * @property float $qte_rest_lbr
 * @property Produit $produit
 * @property string $created_at
 * @property string $updated_at
 * @property ReceptionFour $receptionFour
 */
class LigneBr extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ligne_br';

    /**
     * @var array
     */
    protected $fillable = ['num_lbr', 'qte_lbr', 'prix_ttc_lbr', 'prix_ht_lbr', 'prix_tva_lbr',
        'prix_net_lbr', 'flag_tva_lbr', 'taux_tva_lbr', 'remise_lbr', 'tot_ttc_lbr', 'tot_ht_lbr',
        'tot_tva_lbr', 'flag_stock_ln', 'frais_app_lbr', 'revient_lbr', 'mt_remise_lbr', 'num_lcomfour',
        'flag_stck_tpep', 'qte_rest_lbr', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function produit()
    {
        return $this->belongsTo('App\Models\Produit', 'num_prod', 'num_prod');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receptionFour()
    {
        return $this->belongsTo('App\Models\ReceptionFour', 'num_br', 'num_br');
    }
}
