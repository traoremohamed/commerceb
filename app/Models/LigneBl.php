<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_lbl
 * @property float $num_bl
 * @property float $num_prod
 * @property float $qte_lbl
 * @property float $prix_ttc_lbl
 * @property float $prix_ht_lbl
 * @property float $prix_tva_lbl
 * @property boolean $flag_tva_lbl
 * @property float $tot_ttc_lbl
 * @property float $tot_ht_lbl
 * @property float $tot_tva_lbl
 * @property string $created_at
 * @property string $updated_at
 * @property float $num_lcomc
 * @property float $remise_lbl
 * @property float $remise_ttc_lbl
 * @property integer $flag_stock_ln
 * @property BonLivraison $bonLivraison
 */
class LigneBl extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ligne_bl';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'num_lbl';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'float';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = false;

    /**
     * @var array
     */
    protected $fillable = ['num_bl', 'num_prod', 'qte_lbl', 'prix_ttc_lbl', 'prix_ht_lbl', 'prix_tva_lbl', 'flag_tva_lbl', 'tot_ttc_lbl', 'tot_ht_lbl', 'tot_tva_lbl', 'created_at', 'updated_at', 'num_lcomc', 'remise_lbl', 'remise_ttc_lbl', 'flag_stock_ln'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bonLivraison()
    {
        return $this->belongsTo('App\BonLivraison', 'num_bl', 'num_bl');
    }
}
