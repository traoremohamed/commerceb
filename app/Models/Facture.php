<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_fact
 * @property float $num_bl
 * @property string $code_fact
 * @property string $date_cre_fact
 * @property string $date_val_fact
 * @property boolean $flag_tva_fact
 * @property float $mont_fact
 * @property boolean $flag_fact
 * @property boolean $solde_fact
 * @property boolean $annule_fact
 * @property float $num_cli
 * @property float $id_user
 * @property float $num_agce
 * @property float $prix_ttc_fact
 * @property float $prix_ht_fact
 * @property float $prix_tva_fact
 * @property string $created_at
 * @property string $updated_at
 * @property BonLivraison $bonLivraison
 * @property LigneFact[] $ligneFacts
 */
class Facture extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'facture';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'num_fact';

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
    protected $fillable = ['num_bl', 'code_fact', 'date_cre_fact', 'date_val_fact', 'flag_tva_fact', 'mont_fact', 'flag_fact', 'solde_fact', 'annule_fact', 'num_cli', 'id_user', 'num_agce', 'prix_ttc_fact', 'prix_ht_fact', 'prix_tva_fact', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function bonLivraison()
    {
        return $this->belongsTo('App\BonLivraison', 'num_bl', 'num_bl');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ligneFacts()
    {
        return $this->hasMany('App\LigneFact', 'num_fact', 'num_fact');
    }
}
