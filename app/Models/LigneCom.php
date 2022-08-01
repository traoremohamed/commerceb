<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_bl_lcomc
 * @property float $num_prod
 * @property float $num_agce_vente
 * @property float $num_comc
 * @property float $qte_lcomc
 * @property float $remise_lcomc
 * @property float $remise_ttc_lcomc
 * @property float $prix_ttc_lcomc
 * @property float $prix_ht_lcomc
 * @property float $prix_tva_lcomc
 * @property boolean $flag_tva_lcomc
 * @property float $tot_ttc_lcomc
 * @property float $tot_ht_lcomc
 * @property float $tot_tva_lcomc
 * @property string $created_at
 * @property string $updated_at
 * @property Produit $produit
 * @property Commandeclient $commandeclient
 */
class LigneCom extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'ligne_com';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'num_bl_lcomc';

    /**
     * The "type" of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'float';

    /**
     * @var array
     */
    protected $fillable = ['num_prod', 'num_agce_vente', 'num_comc', 'qte_lcomc',  'remise_lcomc',  'remise_ttc_lcomc', 'prix_ttc_lcomc', 'prix_ht_lcomc', 'prix_tva_lcomc', 'flag_tva_lcomc', 'tot_ttc_lcomc', 'tot_ht_lcomc', 'tot_tva_lcomc', 'created_at', 'updated_at'];

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
    public function commandeclient()
    {
        return $this->belongsTo('App\Models\Commandeclient', 'num_comc', 'num_comc');
    }
}
