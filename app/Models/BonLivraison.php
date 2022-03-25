<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_bl
 * @property float $num_comc
 * @property float $num_fact
 * @property string $date_cre_bl
 * @property string $date_val_bl
 * @property boolean $flag_tva_bl
 * @property float $mont_bl
 * @property boolean $flag_bl
 * @property boolean $solde_bl
 * @property boolean $annule_bl
 * @property float $num_cli
 * @property float $id_user
 * @property float $num_agce
 * @property float $prix_ttc_bl
 * @property float $prix_ht_bl
 * @property float $prix_tva_bl
 * @property string $created_at
 * @property string $updated_at
 * @property Commandeclient $commandeclient
 * @property LigneBl[] $ligneBls
 */
class BonLivraison extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'bon_livraison';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'num_bl';

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
    protected $fillable = ['num_comc', 'num_fact', 'date_cre_bl', 'date_val_bl', 'flag_tva_bl', 'mont_bl', 'flag_bl', 'solde_bl', 'annule_bl', 'num_cli', 'id_user', 'num_agce', 'prix_ttc_bl', 'prix_ht_bl', 'prix_tva_bl', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commandeclient()
    {
        return $this->belongsTo('App\Models\Commandeclient', 'num_comc', 'num_comc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ligneBls()
    {
        return $this->hasMany('App\Models\LigneBl', 'num_bl', 'num_bl');
    }
}
