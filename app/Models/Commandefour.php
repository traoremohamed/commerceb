<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_comf
 * @property float $num_agce
 * @property float $num_fourn
 * @property string $date_val_comf
 * @property int $flag_tva_comf
 * @property float $mont_comf
 * @property boolean $flag_comf
 * @property boolean $solde_comf
 * @property boolean $annule_comf
 * @property float $id_user
 * @property float $prix_ht_comf
 * @property float $prix_ttc_comf
 * @property boolean $flag_comf_br
 * @property string $comment_com
 * @property string $created_at
 * @property string $updated_at
 * @property Agence $agence
 * @property Fournisseur $fournisseur
 * @property LigneComfour[] $ligneComfours
 * @property ReceptionFour[] $receptionFours
 */
class Commandefour extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'commandefour';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'num_comf';

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
    protected $fillable = ['num_agce', 'num_fourn', 'date_val_comf', 'flag_tva_comf', 'mont_comf',
        'flag_comf', 'solde_comf', 'annule_comf', 'id_user', 'prix_ht_comf', 'prix_ttc_comf', 'flag_comf_br',
        'comment_com', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agence()
    {
        return $this->belongsTo('App\Models\Agence', 'num_agce', 'num_agce');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function fournisseur()
    {
        return $this->belongsTo('App\Models\Fournisseur', 'num_fourn', 'num_fourn');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ligneComfours()
    {
        return $this->hasMany('App\Models\LigneComfour', 'num_comf', 'num_comf');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function receptionFours()
    {
        return $this->hasMany('App\Models\ReceptionFour', 'num_comf', 'num_comf');
    }
}
