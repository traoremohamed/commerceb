<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property float $num_comc
 * @property float $num_cli
 * @property float $code_comc
 * @property string $date_cre_comc
 * @property string $date_val_comc
 * @property float $mont_comc
 * @property boolean $flag_tva_comc
 * @property boolean $flag_comc
 * @property boolean $solde_comc
 * @property boolean $annule_comc
 * @property float $id_user
 * @property float $num_agce
 * @property string $comment_comc
 * @property boolean $flag_bl_comc
 * @property float $tot_ttc_lcomc
 * @property float $tot_ht_lcomc
 * @property float $tot_tva_lcomc
 * @property float $prix_ttc_comc
 * @property float $prix_ht_comc
 * @property float $prix_tva_comc
 * @property boolean $flag_bl
 * @property string $created_at
 * @property string $updated_at
 * @property Client $client
 * @property BonLivraison[] $bonLivraisons
 * @property LigneCom[] $ligneComs
 */
class Commandeclient extends Model
{
    /**
     * The table associated with the model.
     * 
     * @var string
     */
    protected $table = 'commandeclient';

    /**
     * The primary key for the model.
     * 
     * @var string
     */
    protected $primaryKey = 'num_comc';

    /**
     * The "type" of the auto-incrementing ID.
     * 
     * @var string
     */
    protected $keyType = 'float';

    /**
     * @var array
     */
    protected $fillable = ['num_cli', 'code_comc', 'date_cre_comc', 'date_val_comc', 'mont_comc', 'flag_tva_comc', 'flag_comc', 'solde_comc', 'annule_comc', 'id_user', 'num_agce', 'comment_comc', 'flag_bl_comc', 'tot_ttc_lcomc', 'tot_ht_lcomc', 'tot_tva_lcomc', 'prix_ttc_comc', 'prix_ht_comc', 'prix_tva_comc', 'flag_bl', 'created_at', 'updated_at'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function client()
    {
        return $this->belongsTo('App\Models\Client', 'num_cli', 'num_cli');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function bonLivraisons()
    {
        return $this->hasMany('App\Models\BonLivraison', 'num_comc', 'num_comc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ligneComs()
    {
        return $this->hasMany('App\Models\LigneCom', 'num_comc', 'num_comc');
    }
}
