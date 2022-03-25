<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_prod
 * @property float $num_sousfam
 * @property float $code_prod
 * @property float $num_agce
 * @property string $lib_prod
 * @property string $code_barre_prod
 * @property float $prix_ht
 * @property float $prix_ttc
 * @property float $prix_achat_prod
 * @property float $prix_revient_prod
 * @property float $coef_vente_prod
 * @property int $flag_tva_prod
 * @property int $taux_tva_prod
 * @property string $created_at
 * @property string $updated_at
 * @property SousFamille $sousFamille
 */
class Produit extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'produit';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'num_prod';

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
    protected $fillable = ['num_sousfam', 'code_prod', 'lib_prod', 'prix_ht', 'prix_ttc',
                            'prix_achat_prod', 'prix_revient_prod', 'coef_vente_prod', 'flag_tva_prod',
                            'taux_tva_prod', 'flag_prod', 'code_barre_prod','created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function sousFamille()
    {
        return $this->belongsTo('App\Models\SousFamille', 'num_sousfam', 'num_sousfam');
    }
}
