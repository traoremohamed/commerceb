<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_br
 * @property float $num_comf
 * @property string $date_cre_br
 * @property string $date_val_br
 * @property int $flag_tva_br
 * @property float $mont_br
 * @property boolean $flag_br
 * @property boolean $solde_br
 * @property boolean $annule_br
 * @property float $id_user
 * @property float $prix_ht_br
 * @property float $prix_tva_br
 * @property float $prix_ttc_br
 * @property float $num_agce
 * @property float $num_fourn
 * @property string $created_at
 * @property string $updated_at
 * @property Commandefour $commandefour
 * @property LigneBr[] $ligneBrs
 */
class ReceptionFour extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'reception_four';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'num_br';

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
    protected $fillable = ['num_comf', 'date_cre_br', 'date_val_br', 'flag_tva_br', 'mont_br',
        'flag_br', 'solde_br', 'annule_br', 'id_user', 'prix_ht_br', 'prix_tva_br', 'prix_ttc_br',
        'num_agce', 'num_fourn', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function commandefour()
    {
        return $this->belongsTo('App\Models\Commandefour', 'num_comf', 'num_comf');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ligneBrs()
    {
        return $this->hasMany('App\Models\LigneBr', 'num_br', 'num_br');
    }
}
